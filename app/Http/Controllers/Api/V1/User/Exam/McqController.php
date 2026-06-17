<?php

namespace App\Http\Controllers\Api\V1\User\Exam;

use App\Http\Controllers\Controller;
use App\Models\Mcq;
use Illuminate\Http\Request;

class McqController extends Controller
{
    public function index(Request $request)
    {
        $mcqs = Mcq::with(['admission', 'subject', 'topic'])
            ->when($request->admission_id, function ($q) use ($request) {
                $q->where('admission_id', $request->admission_id);
            })
            ->when($request->department_id, function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            })
            ->when($request->subject_id, function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            })
            ->when($request->topic_id, function ($q) use ($request) {
                $q->where('topic_id', $request->topic_id);
            })
            ->when($request->mcq_type, function ($q) use ($request) {
                $q->where('mcq_type', $request->mcq_type);
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'exam_datetime' => $item->exam_datetime,
                    'exam_duration' => $item->exam_duration,
                    'exam_mark' => $item->exam_mark,
                    'pass_mark' => $item->pass_mark,
                    'fee' => $item->fee,
                    'mcq_type' => $item->mcq_type,

                    'admission' => [
                        'id' => $item->admission?->id,
                        'name' => $item->admission?->name,
                    ],

                    'subject' => [
                        'id' => $item->subject?->id,
                        'name' => $item->subject?->name,
                    ],

                    'topic' => [
                        'id' => $item->topic?->id,
                        'name' => $item->topic?->name,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $mcqs
        ]);
    }

    public function show($id)
    {
        $mcq = Mcq::with([
            'admission',
            'department',
            'subject',
            'topic',
            'questions.answers'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $mcq->id,
                'title' => $mcq->title,
                'exam_datetime' => $mcq->exam_datetime,
                'exam_duration' => $mcq->exam_duration,
                'exam_mark' => $mcq->exam_mark,
                'pass_mark' => $mcq->pass_mark,
                'fee' => $mcq->fee,

                'admission' => $mcq->admission,
                'department' => $mcq->department,
                'subject' => $mcq->subject,
                'topic' => $mcq->topic,

                // QUESTIONS (IMPORTANT)
                'questions' => $mcq->questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'question' => $q->question,
                        'answers' => $q->answers->map(function ($a) {
                            return [
                                'id' => $a->id,
                                'answer' => $a->answer,
                            ];
                        }),
                    ];
                }),
            ]
        ]);
    }
}