<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mcq;
use App\Models\McqQuizAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
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

    public function onlineQuiz()
    {
        $pageTitle = 'Online Quiz Report List';

        $mcqs = Mcq::where('mcq_type', 5)
            ->whereHas('quizAnswers') 
            ->latest()
            ->get();

        return view('admin.mcq.quiz.report', compact('mcqs', 'pageTitle'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'MCQ Create';
        return view('admin.mcq.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'exam_datetime' => 'required',
            'exam_duration' => 'required',
            'exam_mark' => 'required',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:4', // Ensure exactly 4 answers
            'questions.*.answers.*.answer' => 'required|string', // Changed to match your input structure
            'questions.*.correct_answer' => 'required|integer|between:0,3', // Must be 0-3 (for 4 options)
        ]);

        try {
            DB::beginTransaction();

            $examDateTime = Carbon::parse($request->exam_datetime);
            $mcq = Mcq::create([
                'title'         => $request->title,
                'exam_datetime' => $examDateTime,
                'exam_duration' => $request->exam_duration,
                'exam_mark'     => $request->exam_mark,
                'mcq_type'      => 5, // 5 = manual
                'created_by'    => Auth::id(),
            ]);

            foreach ($request->questions as $qData) {
                $question = $mcq->questions()->create([
                    'question' => $qData['text'],
                ]);

                foreach ($qData['answers'] as $aIndex => $answerData) {
                    $question->answers()->create([
                        'answer' => $answerData['answer'],
                        'is_correct' => ((int)$qData['correct_answer'] === $aIndex) ? 1 : 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.mcq.index')->with('success', 'MCQ Exam created successfully!');

        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mcq = Mcq::with(['questions.answers'])->findOrFail($id); 
        $pageTitle = 'MCQ Details';

        return view('admin.mcq.show', compact('mcq', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mcq = Mcq::with(['questions.answers'])->findOrFail($id); 
        $pageTitle = 'Edit MCQ';

        return view('admin.mcq.edit', compact('mcq', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'exam_datetime' => 'required',
            'exam_duration' => 'required|integer|min:1',
            'exam_mark' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:1000',
            'questions.*.answers' => 'required|array|size:4',
            'questions.*.answers.*.answer' => 'required|string|max:255',
            'questions.*.correct_answer' => 'required|integer|between:0,3',
        ]);

        // Validate and get the clean data
        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $mcq = Mcq::findOrFail($id);

            $examDateTime = Carbon::parse($validated['exam_datetime']);

            // Update MCQ
            $mcq->update([
                'title'         => $validated['title'],
                'exam_datetime' => $examDateTime,
                'exam_duration' => $validated['exam_duration'],
                'exam_mark'     => $validated['exam_mark'],
                'updated_by'    => Auth::id(),
            ]);

            // Delete old questions and answers
            $mcq->questions()->delete();

            // Insert new questions and answers
            foreach ($validated['questions'] as $questionData) {
                $question = $mcq->questions()->create([
                    'question' => $questionData['text'],
                ]);

                foreach ($questionData['answers'] as $index => $answerData) {
                    $question->answers()->create([
                        'answer'     => $answerData['answer'],
                        'is_correct' => $questionData['correct_answer'] == $index ? 1 : 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.mcq.index')
                ->with('success', 'MCQ updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating MCQ: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mcq = Mcq::findOrFail($id);

        foreach ($mcq->questions as $question) {
            $question->answers()->delete();
            $question->delete();    
        }

        $mcq->delete();

        return redirect()->route('admin.mcq.index')
            ->with('success', 'MCQ deleted successfully!');
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

    public function onlineQuizShow(string $id)
    {
        $pageTitle = 'Online Quiz Details';

        $mcq = Mcq::with([
            'quizAnswers.user',
            'quizAnswers.question.answers'
        ])->findOrFail($id);

        return view('admin.mcq.quiz.show', compact('mcq', 'pageTitle'));
    }


    // Delete Online Quiz Answer
    public function onlineQuizDestroy(string $id)
    {
        $mcq = McqQuizAnswer::findOrFail($id);

        // Optional: you can log or check before deleting
        $mcq->delete();

        return redirect()->route('admin.online.quiz.report')->with('success', 'Online Quiz deleted successfully!');
    }

}
