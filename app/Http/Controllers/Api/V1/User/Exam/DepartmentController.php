<?php

namespace App\Http\Controllers\Api\V1\User\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::with('admission')
            ->where('status', 1)
            ->when($request->admission_id, function ($q) use ($request) {
                $q->where('admission_id', $request->admission_id);
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image
                        ? asset('upload/department/' . $item->image)
                        : null,

                    'admission' => [
                        'id' => $item->admission?->id,
                        'name' => $item->admission?->name,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }
}
