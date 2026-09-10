<?php

namespace App\Imports;

use App\Models\Mcq;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class PaperMcqImport implements ToCollection, WithHeadingRow
{
    protected $admissionId;
    protected $departmentId;
    protected $groupId;
    protected $subjectId;
    protected $paperFinalId;
    protected $createdBy;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct($admissionId, $departmentId, $groupId, $subjectId, $paperFinalId, $createdBy)
    {
        $this->admissionId = $admissionId;
        $this->departmentId = $departmentId;
        $this->groupId = $groupId;
        $this->subjectId = $subjectId;
        $this->paperFinalId = $paperFinalId;
        $this->createdBy = $createdBy;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if (empty($row['question'])) {
                continue;
            }

            try {
                $answers = [
                    ['answer' => $row['option_1'] ?? ''],
                    ['answer' => $row['option_2'] ?? ''],
                    ['answer' => $row['option_3'] ?? ''],
                    ['answer' => $row['option_4'] ?? ''],
                ];

                $correctAnswer = (int)($row['correct_answer_0_3'] ?? $row['correct_answer'] ?? 0);
                if ($correctAnswer < 0 || $correctAnswer > 3) {
                    Log::warning("Invalid correct answer at row " . ($index + 2) . ". Using 0.");
                    $correctAnswer = 0;
                }

                $mcq = Mcq::create([
                    'admission_id' => $this->admissionId,
                    'department_id' => $this->departmentId,
                    'group_id' => $this->groupId,
                    'subject_id' => $this->subjectId,
                    'paper_final_id' => $this->paperFinalId,
                    'mcq_type' => 3, // 3 for Paper Final MCQ
                    'question' => $row['question'],
                    'created_by' => $this->createdBy,
                ]);

                foreach ($answers as $aIndex => $answerData) {
                    if (!empty($answerData['answer'])) {
                        $mcq->answers()->create([
                            'answer' => $answerData['answer'],
                            'is_correct' => ($aIndex == $correctAnswer) ? 1 : 0,
                        ]);
                    }
                }

                $this->importedCount++;

            } catch (\Exception $e) {
                Log::error("Error importing MCQ at row " . ($index + 2) . ": " . $e->getMessage());
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}