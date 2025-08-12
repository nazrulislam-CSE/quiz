<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Topic List';
        $topics = Topic::all();
        return view('admin.topic.index', compact('topics', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Topic';
        $subjects = Subject::where('status', '1')->get();
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        return view('admin.topic.create', compact('subjects', 'admissions', 'departments', 'pageTitle'));
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

        Topic::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
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
            Topic::where('id', $topic->id)->update(['topic' => $filename]);
        }

        flash()->addSuccess("Topic created successfully.");
        return redirect()->route('admin.topic.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Topic Details';
        $topic = Topic::findOrFail($id);
        return view('admin.topic.show', compact('topic', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Topic';
        $topic = Topic::findOrFail($id);
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

        $topic = Topic::findOrFail($id);
        $topic->update([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
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
            Topic::where('id', $topic->id)->update(['topic' => $filename]);
        }

        flash()->addSuccess("Topic updated successfully.");
        return redirect()->route('admin.topic.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $topic = Topic::findOrFail($id);
        @unlink(public_path('upload/topic/'.$topic->image));
        $topic->delete();

        flash()->addSuccess("Topic deleted successfully.");
        return redirect()->route('admin.topic.index');
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
