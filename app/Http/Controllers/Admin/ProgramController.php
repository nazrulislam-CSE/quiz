<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\ProgramSubject;
use App\Models\ProgramTopic;
use Illuminate\Support\Carbon;
use Session;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Program List';
        $programs = Program::latest()->get();
        return view('admin.program.index',compact('pageTitle','programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Program Create';
        $subjects = ProgramSubject::where('status',1)->latest()->get();
        return view('admin.program.create',compact('pageTitle','subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',

            // subjects validation
            'subjects' => 'required|array|min:1',
            'subjects.*.program_subject_id' => 'required|exists:program_subjects,id',
            'subjects.*.topic_name' => 'required|string|max:255',
            'subjects.*.total_mcq' => 'required|integer|min:1',
            'subjects.*.time' => 'required|integer|min:1',
            'subjects.*.exam_fee' => 'required|numeric|min:0',
        ]);

        $program = new Program;

        $program->status = $request->status ?? 0;
        $program->name = $request->name;
        $program->slug = Str::slug($request->name);
        $program->description = $request->description;
        $program->created_at = Carbon::now();
        $program->save();

        // Image upload
        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/program/' . $program->image));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/program'), $filename);
            $program->image = $filename;
        }

        $program->save();

        // Save related ProgramTopics
        if ($request->has('subjects')) {
            foreach ($request->subjects as $subjectData) {
                ProgramTopic::create([
                    'program_id' => $program->id,
                    'program_subject_id' => $subjectData['program_subject_id'],
                    'topic_name' => $subjectData['topic_name'],
                    'total_mcq' => $subjectData['total_mcq'],
                    'time' => $subjectData['time'],
                    'exam_fee' => $subjectData['exam_fee'],
                ]);
            }
        }

        flash()->addSuccess("Program Created Successfully.");
        return redirect('/admin/program/index');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Program Show';
        $program = Program::with('topics')->find($id);
        return view('admin.program.show',compact('pageTitle','program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program = Program::with('topics')->findOrFail($id);
        $pageTitle = 'Program Edit';
        $subjects = ProgramSubject::where('status', 1)->latest()->get();
        return view('admin.program.edit', compact('program', 'pageTitle', 'subjects'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $program = Program::findOrFail($id);

        // Validation
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',

            // subjects validation
            'subjects' => 'nullable|array|min:1',
            'subjects.*.program_subject_id' => 'required_with:subjects|exists:program_subjects,id',
            'subjects.*.topic_name' => 'required_with:subjects|string|max:255',
            'subjects.*.total_mcq' => 'required_with:subjects|integer|min:1',
            'subjects.*.time' => 'required_with:subjects|integer|min:1',
            'subjects.*.exam_fee' => 'required_with:subjects|numeric|min:0',
        ]);

        // Program fields update
        $program->status = $request->status ?? 0;
        $program->name = $request->name;
        $program->slug = Str::slug($request->name);
        $program->description = $request->description;
        $program->updated_at = Carbon::now();

        // Image update
        if ($request->file('image')) {
            $file = $request->file('image');
            if ($program->image && file_exists(public_path('upload/program/' . $program->image))) {
                @unlink(public_path('upload/program/' . $program->image));
            }
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/program'), $filename);
            $program->image = $filename;
        }

        $program->save();

        // Update ProgramTopics
        if ($request->has('subjects')) {
            // Delete old topics
            ProgramTopic::where('program_id', $program->id)->delete();

            // Insert new topics
            foreach ($request->subjects as $subjectData) {
                ProgramTopic::create([
                    'program_id' => $program->id,
                    'program_subject_id' => $subjectData['program_subject_id'],
                    'topic_name' => $subjectData['topic_name'],
                    'total_mcq' => $subjectData['total_mcq'],
                    'time' => $subjectData['time'],
                    'exam_fee' => $subjectData['exam_fee'],
                ]);
            }
        }

        flash()->addSuccess("Program Updated Successfully.");
        return redirect('/admin/program/index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::find($id);

        try {
            if(file_exists($program->image)){
                unlink($program->image);
            }
        } catch (Exception $e) {

        }

        // Delete related ProgramTopics
        ProgramTopic::where('program_id', $program->id)->delete();

        // Delete program
        $program->delete();


        flash()->addError("Program Deleted Successfully.");
        $url = '/admin/program/index';
        return redirect($url);
    }
}
