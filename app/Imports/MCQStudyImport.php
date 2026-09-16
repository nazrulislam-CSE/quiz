<?php

namespace App\Imports;

use App\Models\Mcq;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class MCQStudyImport implements ToCollection, WithHeadingRow
{
    protected $admissionId;
    protected $departmentId;
    protected $subjectId;
    protected $topicId;
    protected $createdBy;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct($admissionId, $departmentId, $subjectId, $topicId, $createdBy)
    {
        $this->admissionId = $admissionId;
        $this->departmentId = $departmentId;
        $this->subjectId = $subjectId;
        $this->topicId = $topicId;
        $this->createdBy = $createdBy;
    }

    /**
     * Process the Excel collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Skip empty rows
            if (empty($row['question'])) {
                continue;
            }

            try {

                // Prepare answers array
                $answers = [
                    ['answer' => $row['option_1'] ?? ''],
                    ['answer' => $row['option_2'] ?? ''],
                    ['answer' => $row['option_3'] ?? ''],
                    ['answer' => $row['option_4'] ?? ''],
                ];

                // Validate correct answer index (0-3)
                $correctAnswer = (int) ($row['correct_answer'] ?? 0);

                if ($correctAnswer < 0 || $correctAnswer > 3) {

                    Log::warning(
                        "Invalid correct answer index at row " .
                        ($index + 2) .
                        ": " .
                        $correctAnswer .
                        ". Using 0 as default."
                    );

                    $correctAnswer = 0;
                }

                /*
                 * MCQ Type
                 *
                 * 1 = Exam
                 * 2 = Study
                 *
                 * Default = 2 (Study)
                 */
                $mcqType = (int) ($row['mcq_type'] ?? 2);

                if (!in_array($mcqType, [1, 2])) {

                    Log::warning(
                        "Invalid MCQ type at row " .
                        ($index + 2) .
                        ": " .
                        $mcqType .
                        ". Using 2 (Study) as default."
                    );

                    $mcqType = 2;
                }

                // Create MCQ
                $mcq = Mcq::create([
                    'admission_id' => $this->admissionId,
                    'department_id' => $this->departmentId,
                    'subject_id' => $this->subjectId,
                    'topic_id' => $this->topicId,

                    // 1 = Exam, 2 = Study
                    'mcq_type' => $mcqType,

                    'question' => $row['question'],
                    'created_by' => $this->createdBy,
                ]);

                // Save options/answers
                foreach ($answers as $aIndex => $answerData) {

                    if (!empty($answerData['answer'])) {

                        $mcq->answers()->create([
                            'answer' => $answerData['answer'],

                            // Correct answer
                            'is_correct' => ($aIndex == $correctAnswer) ? 1 : 0,
                        ]);
                    }
                }

                $this->importedCount++;

            } catch (\Exception $e) {

                Log::error(
                    "Error importing MCQ at row " .
                    ($index + 2) .
                    ": " .
                    $e->getMessage()
                );

                $this->errors[] =
                    "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
    }

    /**
     * Get the number of imported records
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }

    /**
     * Get errors that occurred during import
     */
    public function getErrors()
    {
        return $this->errors;
    }
}