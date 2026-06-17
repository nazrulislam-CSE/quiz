<?php

namespace App\Http\Controllers\Api\V1\User\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::with(['admission', 'department', 'group'])
            ->where('status', 1)
            ->when($request->admission_id, function ($q) use ($request) {
                $q->where('admission_id', $request->admission_id);
            })
            ->when($request->department_id, function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image
                        ? asset('upload/subject/' . $item->image)
                        : null,

                    'admission' => [
                        'id' => $item->admission?->id,
                        'name' => $item->admission?->name,
                    ],

                    'department' => [
                        'id' => $item->department?->id,
                        'name' => $item->department?->name,
                    ],

                    'group' => [
                        'id' => $item->group?->id,
                        'name' => $item->group?->name,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }
}
