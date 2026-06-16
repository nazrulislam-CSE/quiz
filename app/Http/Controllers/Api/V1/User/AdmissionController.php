<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::where('status', 1)
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
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $admissions
        ]);
    }
}