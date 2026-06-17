<?php

namespace App\Http\Controllers\Api\V1\User\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $topics = Topic::with('subject')
            ->where('status', 1)
            ->when($request->subject_id, function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            })
            ->when($request->type, function ($q) use ($request) {
                $q->where('type', $request->type);
            }) // 1 = MCQ Exam, 2 = Study
            ->orderBy('order', 'asc')
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'image' => $item->image
                        ? asset('upload/topic/' . $item->image)
                        : null,

                    'description' => $item->description,
                    'exam_duration' => $item->exam_duration,
                    'exam_mark' => $item->exam_mark,
                    'pass_mark' => $item->pass_mark,
                    'fee' => $item->fee,
                    'type' => $item->type,

                    'subject' => [
                        'id' => $item->subject?->id,
                        'name' => $item->subject?->name,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $topics
        ]);
    }
}
