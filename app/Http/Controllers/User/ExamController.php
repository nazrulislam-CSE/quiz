<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Voucher;  
use App\Models\Topic;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;
use App\Models\Mcq;
use Carbon;

class ExamController extends Controller
{
    public function submit(Request $request)
    {
        $pageTitle = "Exam Result";
        $answers = $request->input('answers', []);
        $mcqs = Mcq::with('answers')->whereIn('id', array_keys($answers))->get();

        $total = $mcqs->count();
        $correct = 0;
        $wrong = 0;

        foreach($mcqs as $mcq){
            $givenAnswerId = $answers[$mcq->id] ?? null;
            $correctAnswer = $mcq->answers->where('is_correct',1)->first();

            if($givenAnswerId && $correctAnswer && $givenAnswerId == $correctAnswer->id){
                $correct++;
            } else {
                $wrong++;
            }
        }

        $score = $total > 0 ? round(($correct/$total)*100, 2) : 0;

        // Calculate time taken using session
        $timeTaken = null;
        if ($request->session()->has('exam_start_time')) {
            $startTime = Carbon::parse($request->session()->get('exam_start_time'));
            $endTime = Carbon::now();
            $timeTaken = $endTime->diff($startTime)->format('%H:%I:%S');

            // Clear session after submission
            $request->session()->forget('exam_start_time');
        }

        return view('user.exam.result', compact('pageTitle','total', 'correct', 'wrong', 'score','timeTaken','mcqs'));
    }
}
