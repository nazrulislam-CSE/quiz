<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mcq;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        return view('admin.mcq.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
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

            foreach ($request->questions as $qData) {
                // Create MCQ
                $mcq = Mcq::create([
                    'title'         => $request->title,
                    'exam_duration' => $request->exam_duration,
                    'exam_mark'     => $request->exam_mark,
                    'mcq_type'      => 5, // 5 Manually MCQ
                    'question'      => $qData['text'],
                    'created_by'    => Auth::id(),
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
        $mcq = Mcq::with(['answers'])->findOrFail($id);
        $pageTitle = 'MCQ Details';
        return view('admin.mcq.show', compact('mcq', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mcq = Mcq::with(['answers'])->findOrFail($id);
        $pageTitle = 'Edit MCQ';

        return view('admin.mcq.edit', compact('mcq','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'exam_duration' => 'required|integer|min:1',
        'exam_mark' => 'required|integer|min:1',
        'questions' => 'required|array|min:1',
        'questions.*.text' => 'required|string|max:1000',
        'questions.*.answers' => 'required|array|size:4',
        'questions.*.answers.*.answer' => 'required|string|max:255',
        'questions.*.correct_answer' => 'required|integer|between:0,3',
    ]);

    DB::beginTransaction();
    try {
        $mcq = Mcq::findOrFail($id);

        $mcq->update([
            'title'         => $request->title,
            'exam_duration' => $request->exam_duration,
            'exam_mark'     => $request->exam_mark,
            'question'      => $validated['questions'][0]['text'], 
            'updated_by'    => Auth::id(),
        ]);

    
        $mcq->answers()->delete();

    
        foreach ($validated['questions'][0]['answers'] as $index => $answerData) {
            $mcq->answers()->create([
                'answer'     => $answerData['answer'],
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

    public function deleteQuestion(Request $request)
    {
        $question = McqQuestion::findOrFail($request->question_id);
        
        // Delete associated answers first
        $question->answers()->delete();
        
        // Then delete the question
        $question->delete();
        
        return response()->json(['success' => true]);
    }
}
