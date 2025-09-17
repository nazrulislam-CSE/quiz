<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mcq;
use App\Models\Department;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Admission;
use App\Models\Topic;
use App\Models\PaperFinal;
use App\Models\ModelTest;
use Illuminate\Support\Facades\DB;

class TopicMcqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Topic Wise MCQ List';
        $mcqs = Mcq::where('mcq_type',1)->latest()->get();
        return view('admin.topic.mcq.exam.index', compact('mcqs','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Topic Wise MCQ';
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $subjects = Subject::where('status', '1')->get();
        $topics = Topic::where('status', '1')->where('type',1)->get();
        return view('admin.topic.mcq.exam.create', compact('admissions','departments','subjects','topics','pageTitle'));
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
                    'mcq_type' => 1, // 1 for Topic Wise MCQ
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
            return redirect()->route('admin.topic.mcq.index')->with('success', 'MCQs saved successfully!');

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
        $pageTitle = 'Topic Wise MCQ Details';
        return view('admin.topic.mcq.exam.show', compact('mcq', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Topic Wise MCQ';
        $mcq = Mcq::findOrFail($id);
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $subjects = Subject::where('status', '1')->get();
        $topics = Topic::where('status', '1')->where('type',1)->get();
        return view('admin.topic.mcq.exam.edit', compact('mcq', 'admissions', 'departments','subjects','topics', 'pageTitle'));
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
            'questions.*.answers' => 'required|array|min:4', // Ensure exactly 4 answers
            'questions.*.answers.*.answer' => 'required|string', // Changed to match your input structure
            'questions.*.correct_answer' => 'required|integer|between:0,3', // Must be 0-3 (for 4 options)
        ]);

        try {
            DB::beginTransaction();

            $mcq = Mcq::findOrFail($id);
            $mcq->update([
                'admission_id' => $request->admission_id,
                'department_id' => $request->department_id,
                'subject_id' => $request->subject_id,
                'topic_id' => $request->topic_id,
                'question' => $request->questions[0]['text'], // Assuming single question for update
            ]);

            // Delete existing answers
            $mcq->answers()->delete();

            // Save new options
            foreach ($request->questions[0]['answers'] as $aIndex => $answerData) {
                $mcq->answers()->create([
                    'answer' => $answerData['answer'],
                    'is_correct' => ((int)$request->questions[0]['correct_answer'] == $aIndex) ? 1 : 0,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.topic.mcq.index')->with('success', 'MCQ updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update MCQ: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mcq = Mcq::findOrFail($id);
        $mcq->answers()->delete(); // Delete related answers first
        $mcq->delete();
        return redirect()->route('admin.topic.mcq.index')->with('success', 'MCQ deleted successfully!');
    }

    public function deleteQuestion(Request $request)
    {
        $question = McqQuestion::findOrFail($request->question_id);
        
        // Delete associated answers first
        $question->answers()->delete();
        
        // Then delete the question
        $question->delete();
        
        return response()->json(['success' => true]);
    }

    // AJAX Methods for Dynamic Dropdowns
    public function getDepartments($admission_id)
    {
        $departments = Department::whereHas('admission', function ($query) use ($admission_id) {
            $query->where('admission_id', $admission_id);
        })->where('status', '1')->get();

        return response()->json($departments);
    }
    public function getSubjects($department_id)
    {
        $subjects = Subject::whereHas('department', function ($query) use ($department_id) {
            $query->where('department_id', $department_id);
        })->where('status', '1')->get();

        return response()->json($subjects);
    }
    public function getTopics($subject_id)
    {
        $topics = Topic::where('subject_id', $subject_id)->where('status', '1')->where('type',1)->get();
        return response()->json($topics);
    }
}
