<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\AdmissionInfo;
use Illuminate\Http\Request;

class AdmissionInfoController extends Controller
{
    /**
     * Get All Active Admission Information
     */
    public function index(Request $request)
    {
        try {

            $admissions = AdmissionInfo::with([
                'units.subjects'
            ])
            ->where('status', 1)
            ->latest()
            ->get();

            $data = $admissions->map(function ($admission) {

                return [
                    'id' => $admission->id,
                    'institute_name' => $admission->institute_name,
                    'session' => $admission->session,
                    'form_start_date' => $admission->form_start_date,
                    'application_last_date' => $admission->application_last_date,

                    'image' => $admission->image
                        ? url($admission->image)
                        : null,

                    'status' => $admission->status,

                    'created_at' => $admission->created_at,
                    'updated_at' => $admission->updated_at,

                    'units' => $admission->units->map(function ($unit) {

                        return [
                            'id' => $unit->id,
                            'admission_info_id' => $unit->admission_info_id,
                            'unit' => $unit->unit,
                            'description' => $unit->description,
                            'note' => $unit->note,
                            'exam_date' => $unit->exam_date,
                            'exam_time' => $unit->exam_time,
                            'mark' => $unit->mark,

                            'subjects' => $unit->subjects,
                        ];

                    })->values(),

                ];

            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Admission information retrieved successfully.',
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve admission information.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}