<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mcq;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Admission;
use App\Models\Topic;

class McqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'MCQ List';
        $mcqs = Mcq::latest()->get();
        return view('admin.mcq.index', compact('mcqs','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'MCQ Create';
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $subjects = Subject::where('status', '1')->get();
        $topics = Topic::where('status', '1')->get();

        return view('admin.mcq.create', compact('admissions', 'departments', 'subjects','topics', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'department_id' => 'required|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*' => 'required|string',
            'questions.*.correct_answer' => 'required|integer',
        ]);



        foreach ($request->questions as $qIndex => $qData) {

            // Create MCQ
            $mcq = Mcq::create([
                'admission_id' => $request->admission_id,
                'department_id' => $request->department_id,
                'subject_id' => $request->subject_id,
                'topic_id' => $request->topic_id,
                'question' => $qData['text'],
                'created_by' => Auth::user()->id ?? '1',
            ]);

            // Save options
            if(isset($qData['answers']) && is_array($qData['answers'])) {
                foreach ($qData['answers'] as $aIndex => $answerText) {
                    $mcq->answers()->create([
                        'answer' => $answerText,
                        'is_correct' => ((int)$qData['correct_answer'] === $aIndex) ? 1 : 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.mcq.index')->with('success', 'MCQs saved successfully!');
    }

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mcq = Mcq::with(['answers', 'admission', 'department', 'subject', 'topic'])->findOrFail($id);
        $pageTitle = 'MCQ Details';
        return view('admin.mcq.show', compact('mcq', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mcq = Mcq::with(['answers'])->findOrFail($id);
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $subjects = Subject::where('status', '1')->get();
        $topics = Topic::where('status', '1')->get();
        $pageTitle = 'MCQ Edit';

        return view('admin.mcq.edit', compact('mcq', 'admissions', 'departments', 'subjects', 'topics', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'department_id' => 'required|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*' => 'required|string',
            'questions.*.correct_answer' => 'required|integer',
        ]);

        $mcq = Mcq::findOrFail($id);
        $mcq->update([
            'admission_id' => $request->admission_id,
            'department_id' => $request->department_id,
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'question' => $request->questions[0]['text'],
            'updated_by' => Auth::user()->id ?? '1',
        ]);

        // Update options
        foreach ($mcq->answers as $answer) {
            $answer->delete();
        }

        foreach ($request->questions[0]['answers'] as $aIndex => $answerText) {
            $mcq->answers()->create([
                'answer' => $answerText,
                'is_correct' => ((int)$request->questions[0]['correct_answer'] === $aIndex) ? 1 : 0,
            ]);
        }

        return redirect()->route('admin.mcq.index')->with('success', 'MCQ updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mcq = Mcq::findOrFail($id);
        $mcq->answers()->delete(); // Delete related answers
        $mcq->delete(); // Delete the MCQ itself

        return redirect()->route('admin.mcq.index')->with('success', 'MCQ deleted successfully!');
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
    public function getTopics($subject_id)
    {
        $topics = Topic::where('subject_id', $subject_id)->get();
        return response()->json($topics);
    }
}
