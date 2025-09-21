<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mcq;
use Illuminate\Support\Carbon;
use App\Models\Slider;
use App\Models\Counter;
use App\Models\About;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Admission;
use App\Models\Feature;
use App\Models\McqQuizAnswer;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status',1)->latest()->get();
        $abouts = About::where('status',1)->latest()->get();
        $counters = Counter::where('status',1)->latest()->get();
        $abouts = About::where('status',1)->latest()->get();
        $teachers = Teacher::where('status',1)->latest()->get();
        $students = Student::where('status',1)->latest()->get();
        $admissions = Admission::where('status',1)->where('type',2)->latest()->get();
        $features = Feature::where('status',1)->latest()->get();
    
        $pageTitle = 'Home';
        return view('frontend.index',compact('pageTitle','sliders','abouts','counters','teachers','students','admissions','features'));
    }

    public function onlineQuiz(Request $request){
        $pageTitle = 'Online Quiz';

        if(!auth()->check()){
            return redirect()->route('login')->with('error','অনুগ্রহ করে লগইন করুন।');
        }
        $quizs = Mcq::latest()->get();
        return view('frontend.exam.online_quiz',compact('pageTitle','quizs'));
    }

    public function onlineExam($id){
        $pageTitle = 'Online Quiz Exam';

        if(!auth()->check()){
            return redirect()->route('login')->with('error','অনুগ্রহ করে লগইন করুন।');
        }
        // quiz check
        $mcq = Mcq::with('answers')->findOrFail($id);
        return view('frontend.exam.online_quiz_exam', compact('pageTitle', 'mcq'));
    }

    public function submitExam(Request $request){

        $pageTitle = 'Online Quiz Exam Result';
        $request->validate([
            'quiz_id' => 'required|exists:mcqs,id',
            'answers' => 'required|array',
            'time_taken' => 'nullable|integer',
        ]);

        $user_id = Auth::user()->id ?? '0';
        $answers = $request->input('answers'); // [question_id => answer_id]
        $time_taken = $request->input('time_taken');

        foreach($answers as $question_id => $answer_id){
            McqQuizAnswer::updateOrCreate(
                ['user_id' => $user_id, 'question_id' => $question_id],
                ['answer_id' => $answer_id, 'time_taken' => $time_taken]
            );
        }

        // After submission, redirect to result page
        return redirect()->route('exam.result', ['quiz_id' => $request->quiz_id]);
    }

    public function result($quiz_id)
    {
        $quiz = Mcq::with('questions.answers')->findOrFail($quiz_id);
        $user_id = Auth::id();

        $results = [];

        foreach ($quiz->questions as $question) {
            $userAnswer = McqQuizAnswer::where('user_id', $user_id)
                ->where('question_id', $question->id)
                ->first();

            if(!$userAnswer) {
                $status = 'notAnswered';
            } else if($question->answers->where('is_correct',1)->first()->id == $userAnswer->answer_id) {
                $status = 'correct';
            } else {
                $status = 'wrong';
            }

            $results[] = [
                'question' => $question->question,
                'status' => $status
            ];
        }

        $total = $quiz->questions->count();
        $correct = count(array_filter($results, fn($r)=>$r['status']=='correct'));
        $wrong = count(array_filter($results, fn($r)=>$r['status']=='wrong'));
        $notAnswered = count(array_filter($results, fn($r)=>$r['status']=='notAnswered'));

        return view('frontend.exam.online_quiz_exam_result', compact('pageTitle','results', 'total', 'correct', 'wrong', 'notAnswered'));
    }
}
