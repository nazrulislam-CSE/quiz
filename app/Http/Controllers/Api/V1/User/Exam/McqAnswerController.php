<?php

namespace App\Http\Controllers\Api\V1\User\Exam;

use App\Http\Controllers\Controller;
use App\Models\McqAnswer;
use Illuminate\Http\Request;

class McqAnswerController extends Controller
{
    public function index(Request $request)
    {
        $answers = McqAnswer::query()
            ->when($request->mcq_id, function ($q) use ($request) {
                $q->where('mcq_id', $request->mcq_id);
            })
            ->when($request->mcq_question_id, function ($q) use ($request) {
                $q->where('mcq_question_id', $request->mcq_question_id);
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'answer' => $item->answer,

                    // ⚠️ only for admin/debug (remove in production if needed)
                    'is_correct' => $item->is_correct ? true : false,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $answers
        ]);
    }
}