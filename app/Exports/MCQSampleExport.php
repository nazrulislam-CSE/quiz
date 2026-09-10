<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class MCQSampleExport implements FromArray
{
    public function array(): array
    {
        return [
            [
                'Question',
                'Option 1',
                'Option 2',
                'Option 3',
                'Option 4',
                'Correct Answer (0-3)',
                'MCQ Type (1=Exam, 2=Study)',
            ],

            [
                'বাংলাদেশের রাজধানী কী?',
                'ঢাকা',
                'চট্টগ্রাম',
                'রাজশাহী',
                'খুলনা',
                0,
                2, // Study
            ],

            [
                'বাংলাদেশের জাতীয় ফুল কী?',
                'গোলাপ',
                'শাপলা',
                'জবা',
                'বেলি',
                1,
                1, // Exam
            ],
        ];
    }
}