<?php

namespace App\Http\Controllers\Api\V1\User\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;

class AdmissionController extends Controller
{
   public function index()
    {
        $admissions = Admission::with('departments')
            ->where('status', 1)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image
                        ? asset('upload/admission/' . $item->image)
                        : null,
                    'type' => $item->type,

                    // relation (optional)
                    'departments' => $item->departments->map(function ($dept) {
                        return [
                            'id' => $dept->id,
                            'name' => $dept->name,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $admissions
        ]);
    }
}
