<?php

namespace App\Imports;

use App\Models\Mcq;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class OnlineQuizMcqImport implements ToCollection, WithHeadingRow
{
    protected $mcqId;
    protected $createdBy;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct($mcqId, $createdBy)
    {
        $this->mcqId = $mcqId;
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
                    $correctAnswer = 0;
                }

                // Create question under the MCQ exam (same as manual store)
                $question = Mcq::find($this->mcqId)->questions()->create([
                    'question' => $row['question'],
                ]);

                foreach ($answers as $aIndex => $answerData) {
                    if (!empty($answerData['answer'])) {
                        $question->answers()->create([
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