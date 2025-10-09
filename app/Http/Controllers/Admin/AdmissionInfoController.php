<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionInfo;
use App\Models\AdmissionUnit;
use Illuminate\Support\Carbon;
use Session;
use Illuminate\Support\Facades\DB;


class AdmissionInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Admission Info List';
        $infos = AdmissionInfo::with('units.subjects')->latest()->get(); 
        return view('admin.admissioniinfo.index',compact('pageTitle','infos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Admission Info Create';
        return view('admin.admissioniinfo.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Validation
            $request->validate([
                'institute_name'       => 'required|string|max:255',
                'session'              => 'required|string|max:50',
                'form_start_date'      => 'nullable|date',
                'application_last_date'=> 'nullable|date',
                'image'                => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // 2. Image Upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('upload/info'), $imageName);
                $imagePath = 'upload/info/'.$imageName;
            }

            // 3. Save Admission Info
            $admissionInfo = AdmissionInfo::create([
                'institute_name'        => $request->institute_name,
                'session'               => $request->session,
                'form_start_date'       => $request->form_start_date,
                'application_last_date' => $request->application_last_date,
                'image'                 => $imagePath,
            ]);

            // 4. Save Units (if any)
            // if ($request->has('units')) {
            //     foreach ($request->units as $unit) {
            //         if (!empty($unit['unit'])) {
            //             AdmissionUnit::create([
            //                 'admission_info_id' => $admissionInfo->id,
            //                 'unit'        => $unit['unit'] ?? null,
            //                 'description' => $unit['description'] ?? null,
            //                 'note'        => $unit['note'] ?? null,
            //                 'exam_date'   => $unit['exam_date'] ?? null,
            //                 'exam_time'   => $unit['exam_time'] ?? null,
            //             ]);
            //         }
            //     }
            // }

            foreach ($request->units as $unitData) {
                $unit = $admission->units()->create([
                    'unit' => $unitData['unit'],
                    'description' => $unitData['description'],
                    'note' => $unitData['note'],
                    'exam_date' => $unitData['exam_date'],
                    'exam_time' => $unitData['exam_time'],
                ]);

                if (isset($unitData['subjects'])) {
                    foreach ($unitData['subjects'] as $subjectData) {
                        $unit->subjects()->create([
                            'subject' => $subjectData['subject'],
                            'mark' => $subjectData['mark'],
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.admission.info.index')
                            ->with('success', 'Admission Info created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: '.$e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $pageTitle = 'Admission Info Show';
        $info = AdmissionInfo::with('units.subjects')->findOrFail($id);
        return view('admin.admissioniinfo.show',compact('pageTitle','info'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $info = AdmissionInfo::with('units.subjects')->findOrFail($id);
        $pageTitle = 'Admission Info Edit';
        return view('admin.admissioniinfo.edit', compact('info','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'institute_name' => 'required|string|max:255',
            'session' => 'required|string|max:50',
            'form_start_date' => 'required|date',
            'application_last_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $info = AdmissionInfo::findOrFail($id);

        // image upload
        if ($request->hasFile('image')) {
            @unlink(public_path($info->image));
            $file = $request->file('image');
            $filename = date('YmdHi').'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/info/'), $filename);
            $info->image = 'upload/info/'.$filename;
        }

        // update main info
        $info->institute_name = $request->institute_name;
        $info->session = $request->session;
        $info->form_start_date = $request->form_start_date;
        $info->application_last_date = $request->application_last_date;
        $info->status = $request->status ?? 1;
        $info->save();

        // 🔹 update units (simple way: delete old & insert new)
        $info->units()->delete();

        // Re-insert all
        foreach ($request->units as $unitData) {
            $unit = $admission->units()->create([
                'unit' => $unitData['unit'],
                'description' => $unitData['description'],
                'note' => $unitData['note'],
                'exam_date' => $unitData['exam_date'],
                'exam_time' => $unitData['exam_time'],
            ]);

            if (!empty($unitData['subjects'])) {
                foreach ($unitData['subjects'] as $subjectData) {
                    $unit->subjects()->create([
                        'subject' => $subjectData['subject'],
                        'mark' => $subjectData['mark'],
                    ]);
                }
            }
        }

        // if ($request->has('units')) {
        //     foreach ($request->units as $unitData) {
        //         if (!empty($unitData['unit'])) {
        //             $info->units()->create([
        //                 'unit' => $unitData['unit'],
        //                 'description' => $unitData['description'] ?? null,
        //                 'note' => $unitData['note'] ?? null,
        //                 'exam_date' => $unitData['exam_date'] ?? null,
        //                 'exam_time' => $unitData['exam_time'] ?? null,
        //             ]);
        //         }
        //     }
        // }



        return redirect()->route('admin.admission.info.index')->with('success','Admission Info Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $info = AdmissionInfo::with('units.subjects')->findOrFail($id);

            // 🔹 Delete image from storage if exists
            if (!empty($info->image) && file_exists(public_path($info->image))) {
                @unlink(public_path($info->image));
            }

            // 🔹 Delete Admission Info (units and subjects will cascade delete automatically)
            $info->delete();

            flash()->addSuccess("Admission Info Deleted Successfully.");
            return redirect()->route('admin.admission.info.index');

        } catch (\Exception $e) {
            flash()->addError("Something went wrong: " . $e->getMessage());
            return redirect()->back();
        }
    }

}
