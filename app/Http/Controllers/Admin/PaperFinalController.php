<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaperFinal;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaperFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Paper Final List';
        $topics = PaperFinal::latest()->get();
        return view('admin.paper.index', compact('topics', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Paper Final';
        $subjects = Subject::where('status', '1')->get();
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        return view('admin.paper.create', compact('subjects', 'admissions', 'departments', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'exam_duration' => 'required|integer',
            'exam_mark' => 'required|integer',
            'fee' => 'required|numeric',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (PaperFinal::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $topic = PaperFinal::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'exam_duration' => $request->exam_duration,
            'exam_mark' => $request->exam_mark,
            'pass_mark' => $request->pass_mark,
            'fee' => $request->fee,
            'status' => $request->status ?? 1,
            'order' => $request->order ?? 0,
            'created_by' => Auth::user()->id ?? 1,
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/topic'), $filename);
            $topic->image = $filename;
            $topic->save();
        }

        flash()->addSuccess("Paper Final created successfully.");
        return redirect()->route('admin.paper.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Paper Final Details';
        $topic = PaperFinal::findOrFail($id);
        return view('admin.paper.show', compact('topic', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Topic';
        $topic = PaperFinal::findOrFail($id);
        $subjects = Subject::where('status', '1')->get();
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        return view('admin.topic.edit', compact('topic', 'subjects', 'admissions', 'departments', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'exam_duration' => 'required|integer',
            'exam_mark' => 'required|integer',
            'fee' => 'required|numeric',
        ]);

        $topic = PaperFinal::findOrFail($id);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (PaperFinal::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $topic->update([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'exam_duration' => $request->exam_duration,
            'exam_mark' => $request->exam_mark,
            'pass_mark' => $request->pass_mark,
            'fee' => $request->fee,
            'status' => $request->status ?? 1,
            'order' => $request->order ?? 0,
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/topic'), $filename);
            PaperFinal::where('id', $topic->id)->update(['topic' => $filename]);
        }

        flash()->addSuccess("Paper Final updated successfully.");
        return redirect()->route('admin.paper.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $topic = PaperFinal::findOrFail($id);
        @unlink(public_path('upload/topic/'.$topic->image));
        $topic->delete();

        flash()->addSuccess("Paper Final deleted successfully.");
        return redirect()->route('admin.paper.index');
    }

    public function getDepartments($admission_id)
    {
        $departments = Department::where('admission_id', $admission_id)->get();
        return response()->json($departments);
    }

    public function getSubjects($department_id)
    {
        $subjects = Subject::where('department_id', $department_id)->get();
        return response()->json($subjects);
    }

}
