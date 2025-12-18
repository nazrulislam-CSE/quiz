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
use App\Models\McqAnswer;
use Illuminate\Support\Facades\DB;

class TopicStudyMcqController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $pageTitle = 'Topic Wise Study MCQ List';

        $mcqs = Mcq::with(['admission', 'department', 'subject', 'topic'])
        ->where('mcq_type', 2) // Filter for Topic Wise Study MCQs
        ->select(
            'admission_id',
            'department_id',
            'subject_id',
            'topic_id',
            \DB::raw('COUNT(*) as total_questions'),
            \DB::raw('MAX(created_at) as latest_created_at') // ✅ max(created_at) added
        )
        ->groupBy('admission_id', 'department_id', 'subject_id', 'topic_id')
        ->orderBy('latest_created_at', 'desc') // ✅ sort by aggregated column
        ->get();


        return view('admin.topic.mcq.study.index', compact('mcqs', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Topic Wise Study MCQ';
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $subjects = Subject::where('status', '1')->get();
        $topics = Topic::where('status', '1')->where('type',2)->get();
        return view('admin.topic.mcq.study.create', compact('admissions','departments','subjects','topics','pageTitle'));
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
                    'mcq_type' => 2, // 2 for Study Question Topic Wise MCQ
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
            return redirect()->route('admin.topic.study.mcq.index')->with('success', 'MCQs saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to save MCQs: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($admission_id, $department_id, $subject_id, $topic_id)
    {
        $pageTitle = 'Study MCQ Questions Details';
        
        $questions = Mcq::with(['admission', 'department', 'subject', 'topic','answers'])
            ->where('admission_id', $admission_id)
            ->where('department_id', $department_id)
            ->where('subject_id', $subject_id)
            ->where('topic_id', $topic_id)
            ->where('mcq_type', 2)
            ->latest()
            ->get();
            
        $topicInfo = $questions->first();
        return view('admin.topic.mcq.study.show', compact('questions', 'pageTitle', 'topicInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($admission_id, $department_id, $subject_id, $topic_id)
    {
        $pageTitle = 'Edit Topic Wise Study MCQ';
        
        // Get all questions for this topic
        $questions = Mcq::with(['admission', 'department', 'subject', 'topic', 'answers'])
            ->where('admission_id', $admission_id)
            ->where('department_id', $department_id)
            ->where('subject_id', $subject_id)
            ->where('topic_id', $topic_id)
            ->where('mcq_type', 2)
            ->orderBy('id')
            ->get();

        // Get topic info from first question
        $topicInfo = $questions->first();
        
        // Get admissions, departments, subjects, topics for dropdowns
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $subjects = Subject::where('status', '1')->get();
        $topics = Topic::where('status', '1')->where('type',1)->get();

        return view('admin.topic.mcq.study.edit', compact(
            'questions', 
            'topicInfo', 
            'pageTitle',
            'admissions',
            'departments',
            'subjects',
            'topics'
        ));
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, $admission_id, $department_id, $subject_id, $topic_id)
    {
        DB::beginTransaction();

        try {
            /* ==================================================
            | 1️⃣ UPDATE EXISTING QUESTIONS
            ================================================== */
            $questionsData = $request->questions ?? [];

            foreach ($questionsData as $questionId => $questionData) {

                $mcq = Mcq::where('id', $questionId)
                    ->where('admission_id', $admission_id)
                    ->where('department_id', $department_id)
                    ->where('subject_id', $subject_id)
                    ->where('topic_id', $topic_id)
                    ->first();

                if (!$mcq) continue;

                // Update question text & correct answer
                $mcq->update([
                    'question' => $questionData['text'] ?? $mcq->question,
                    'correct_answer' => $questionData['correct_answer'] ?? $mcq->correct_answer,
                ]);

                // Update existing answers
                if (isset($questionData['answers'])) {
                    foreach ($questionData['answers'] as $answerId => $answerText) {

                        $answer = McqAnswer::where('id', $answerId)
                            ->where('mcq_id', $mcq->id)
                            ->first();

                        if ($answer) {
                            $answer->update([
                                'answer' => $answerText,
                                'is_correct' => ($questionData['correct_answer'] == $answerId),
                            ]);
                        }
                    }
                }

                // For old structure (option_a ... option_d)
                if (isset($questionData['option_a'])) {
                    $mcq->update([
                        'option_a' => $questionData['option_a'],
                        'option_b' => $questionData['option_b'],
                        'option_c' => $questionData['option_c'],
                        'option_d' => $questionData['option_d'],
                        'correct_answer' => $questionData['correct_answer'],
                    ]);
                }
            }

            /* ==================================================
            | 2️⃣ INSERT NEW QUESTIONS
            ================================================== */
            if ($request->has('new_questions')) {

                foreach ($request->new_questions as $newQuestion) {

                    // Skip empty question
                    if (empty($newQuestion['text'])) {
                        continue;
                    }

                    // Create new MCQ
                    $mcq = Mcq::create([
                        'admission_id' => $admission_id,
                        'department_id' => $department_id,
                        'subject_id' => $subject_id,
                        'topic_id' => $topic_id,
                        'question' => $newQuestion['text'],
                        'correct_answer' => $newQuestion['correct_answer'],
                        'mcq_type' => 2, // Topic Wise Study MCQ
                    ]);

                    // Save options into answers table
                    $options = [
                        'a' => $newQuestion['option_a'] ?? null,
                        'b' => $newQuestion['option_b'] ?? null,
                        'c' => $newQuestion['option_c'] ?? null,
                        'd' => $newQuestion['option_d'] ?? null,
                    ];

                    foreach ($options as $key => $value) {
                        if ($value) {
                            McqAnswer::create([
                                'mcq_id' => $mcq->id,
                                'answer' => $value,
                                'is_correct' => ($newQuestion['correct_answer'] == $key),
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Questions updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating questions: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($admission, $department, $subject, $topic)
    {
        $questions = Mcq::where('admission_id', $admission)
            ->where('department_id', $department)
            ->where('subject_id', $subject)
            ->where('topic_id', $topic)
            ->get();

        foreach ($questions as $question) {
            $question->answers()->delete();
            $question->delete();
        }

        return redirect()->back()->with(
            'success',
            'All questions deleted successfully!'
        );
    }

    public function singleDeleteQuestion(string $id)
    {
        $question = Mcq::findOrFail($id);

        if ($question->answers()->count() > 0) {
            $question->answers()->delete();
        }

        $question->delete();

        return redirect()->back()->with('success', 'Question deleted successfully!');
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
        $topics = Topic::where('subject_id', $subject_id)->where('status', '1')->where('type',2)->get();
        return response()->json($topics);
    }
}
