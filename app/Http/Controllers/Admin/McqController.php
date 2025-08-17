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
use Illuminate\Support\Facades\DB;

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
        $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'department_id' => 'required|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:4', // Ensure exactly 4 answers
            'questions.*.answers.*.answer' => 'required|string', // Changed to match your input structure
            'questions.*.correct_answer' => 'required|integer|between:0,3', // Must be 0-3 (for 4 options)
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->questions as $qData) {
                // Create MCQ
                $mcq = Mcq::create([
                    'admission_id' => $request->admission_id,
                    'department_id' => $request->department_id,
                    'subject_id' => $request->subject_id,
                    'topic_id' => $request->topic_id,
                    'question' => $qData['text'],
                    'created_by' => Auth::id(),
                ]);

                // Save options
                foreach ($qData['answers'] as $aIndex => $answerData) {
                    $mcq->answers()->create([
                        'answer' => $answerData['answer'],
                        'is_correct' => ((int)$qData['correct_answer'] == $aIndex) ? 1 : 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.mcq.index')->with('success', 'MCQs saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to save MCQs: ' . $e->getMessage());
        }
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
        $departments = Department::where('status', '1')
            ->where('admission_id', $mcq->admission_id)
            ->get();
        $subjects = Subject::where('status', '1')
            ->where('department_id', $mcq->department_id)
            ->get();
        $topics = Topic::where('status', '1')
            ->where('subject_id', $mcq->subject_id)
            ->get();
        
        $pageTitle = 'Edit MCQ';

        return view('admin.mcq.edit', compact('mcq', 'admissions', 'departments', 'subjects', 'topics', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'admission_id' => 'required|exists:admissions,id',
        'department_id' => 'required|exists:departments,id',
        'subject_id' => 'required|exists:subjects,id',
        'topic_id' => 'required|exists:topics,id',
        'questions' => 'required|array|min:1',
        'questions.*.text' => 'required|string|max:1000',
        'questions.*.answers' => 'required|array|min:2|max:6',
        'questions.*.answers.*' => 'required|string|max:255',
        'questions.*.correct_answer' => 'required|integer|min:0',
    ]);

    DB::beginTransaction();
    try {
        $mcq = Mcq::findOrFail($id);
        
        $mcq->update([
            'admission_id' => $validated['admission_id'],
            'department_id' => $validated['department_id'],
            'subject_id' => $validated['subject_id'],
            'topic_id' => $validated['topic_id'],
            'question' => $validated['questions'][0]['text'],
            'updated_by' => Auth::id(),
        ]);

        // Delete existing answers
        $mcq->answers()->delete();

        // Add new answers
        foreach ($validated['questions'][0]['answers'] as $index => $answerText) {
            $mcq->answers()->create([
                'answer' => $answerText,
                'is_correct' => $validated['questions'][0]['correct_answer'] == $index ? 1 : 0,
            ]);
        }

        DB::commit();
        
        return redirect()->route('admin.mcq.index')
            ->with('success', 'MCQ updated successfully!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()
            ->with('error', 'Error updating MCQ: ' . $e->getMessage());
    }
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
