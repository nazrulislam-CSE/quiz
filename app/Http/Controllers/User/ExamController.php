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
use App\Models\Group;
use App\Models\ModelTest;
use App\Models\PaperFinal;
use App\Models\Mcq;
use App\Models\ExamResult;
use App\Models\BalanceRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class ExamController extends Controller
{
    public function create(Request $request)
    {
        $pageTitle = "MCQ Exam";
        $submitted = false;
        $correct = 0;
        $wrong = 0;
        $score = 0;
        $total = 0;

        $admissions = Admission::where('status', 1)->orderBy('id', 'asc')->get();

        $selectedAdmission = $request->query('admission');
        $selectedDepartment = $request->query('department');
        $selectedGroup = $request->query('group');
        $selectedSubject = $request->query('subject');
        $selectedTopic = $request->query('topic');
        $selectedPaperFinal = $request->query('paper_final');
        $selectedModelTest = $request->query('model_test');
        $examStart = $request->query('exam');
        $studyMode = $request->query('study'); 

        $departments = collect();
        $groups = collect();
        $subjects = collect();
        $topics = collect();
        $paperFinals = collect();
        $modelTests = collect();
        $mcqs = collect();

        if ($selectedAdmission) {
            $departments = Department::where('admission_id', $selectedAdmission)
                ->where('status',1)->orderBy('id', 'asc')->get();
        }

        if ($selectedDepartment) {
            $groups = Group::where('department_id', $selectedDepartment)
                ->where('status',1)->orderBy('id', 'asc')->get();
            $subjects = Subject::where('department_id', $selectedDepartment)
                ->where('status',1)->orderBy('id', 'asc')->get();
        }

        if ($selectedGroup) {
            $subjects = Subject::where('group_id', $selectedGroup)
                ->where('status',1)->orderBy('id', 'asc')->get();
            $modelTests = ModelTest::where('group_id', $selectedGroup)
                ->where('status',1)->orderBy('id', 'asc')->get();
        }

        if ($selectedSubject) {
            $topics = Topic::where('subject_id', $selectedSubject)
                ->where('status',1)->orderBy('id', 'asc')->get();
            $paperFinals = PaperFinal::where('subject_id', $selectedSubject)
                ->where('status',1)->orderBy('id', 'asc')->get();
        }

        // Study mode (Topic ভিত্তিক)
        if ($selectedTopic && $studyMode) {
            $mcqs = Mcq::with('answers')
                ->where('topic_id', $selectedTopic)
                ->get();
        }

        // Exam start
        if ($examStart) {
            $user = Auth::user();

            if ($selectedTopic) {
                $selectedTopicData = Topic::find($selectedTopic);
                if (!$selectedTopicData) {
                    return redirect()->back()->with('error', 'টপিক পাওয়া যায়নি।');
                }
                $examFee = $selectedTopicData->fee;
                if ($user->main_wallet < $examFee) {
                    return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
                }
                $mcqs = Mcq::with('answers')->where('topic_id', $selectedTopic)->get();
            }

            if ($selectedPaperFinal) {
                $selectedPaper = PaperFinal::find($selectedPaperFinal);
                if (!$selectedPaper) {
                    return redirect()->back()->with('error', 'পেপার ফাইনাল পাওয়া যায়নি।');
                }
                $examFee = $selectedPaper->fee;
                if ($user->main_wallet < $examFee) {
                    return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
                }
                $mcqs = $selectedPaper->mcqs()->with('answers')->get();
            }

            if ($selectedModelTest) {
                $selectedModel = ModelTest::find($selectedModelTest);
                if (!$selectedModel) {
                    return redirect()->back()->with('error', 'মডেল টেস্ট পাওয়া যায়নি।');
                }
                $examFee = $selectedModel->fee;
                if ($user->main_wallet < $examFee) {
                    return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
                }
                $mcqs = $selectedModel->mcqs()->with('answers')->get();
            }
        }

        return view('user.exam.mcq', compact(
            'pageTitle','admissions','departments','groups','subjects','topics',
            'paperFinals','modelTests','selectedAdmission','selectedDepartment',
            'selectedGroup','selectedSubject','selectedTopic','selectedPaperFinal',
            'selectedModelTest','mcqs','examStart','studyMode'
        ));
    }

    // public function create(Request $request)
    // {
    //     $pageTitle = "MCQ Exam";
    //     $submitted = false;
    //     $correct = 0;
    //     $wrong = 0;
    //     $score = 0;
    //     $total = 0;

    //     $admissions = Admission::where('status', 1)->orderBy('id', 'asc')->get();

    //     $selectedAdmission = $request->query('admission');
    //     $selectedDepartment = $request->query('department');
    //     $selectedSubject = $request->query('subject');
    //     $selectedTopic = $request->query('topic');
    //     $examStart = $request->query('exam');
    //     $studyMode = $request->query('study'); 

    //     $departments = collect();
    //     $subjects = collect();
    //     $topics = collect();
    //     $mcqs = collect();

    //     if ($selectedAdmission) {
    //         $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->orderBy('id', 'asc')->get();
    //         if ($departments->isEmpty() && !$selectedDepartment) {
    //             return redirect()->route('user.mcq.exam')->with('error', 'No departments available for this admission.');
    //         }
    //     }

    //     if ($selectedDepartment) {
    //         $subjects = Subject::where('department_id', $selectedDepartment)->where('status',1)->orderBy('id', 'asc')->get();
    //         if ($subjects->isEmpty() && !$selectedSubject) {
    //             return redirect()->route('user.mcq.exam')->with('error', 'No subjects available for this department.');
    //         }
    //     }

    //     if ($selectedSubject) {
    //         $topics = Topic::where('subject_id', $selectedSubject)->where('status',1)->orderBy('id', 'asc')->get();
    //         if ($topics->isEmpty() && !$selectedTopic) {
    //             return redirect()->route('user.mcq.exam')->with('error', 'No topics available for this subject.');
    //         }
    //     }

    //     // study start
    //     if ($selectedTopic && $studyMode) {
    //         $mcqs = Mcq::with('answers')
    //                     ->where('topic_id', $selectedTopic)
    //                     ->get();

    //         if ($mcqs->isEmpty()) {
    //             return redirect()->route('user.mcq.exam', [
    //                 'admission' => $selectedAdmission,
    //                 'department' => $selectedDepartment,
    //                 'subject' => $selectedSubject,
    //                 'topic' => $selectedTopic,
    //             ])->with('error', 'This topic has no questions.');
    //         }
    //     }

    //     // exam start
    //     if ($selectedTopic && $examStart) {

    //         $selectedTopicData = Topic::find($selectedTopic);

    //         if (!$selectedTopicData) {
    //             return redirect()->back()->with('error', 'টপিক পাওয়া যায়নি।');
    //         }

    //         $examFee = $selectedTopicData->fee;

    //         // Approved balance
    //         $user = Auth::user();
    //         $userBalance = $user->main_wallet;

    //         // balance check
    //         if ($userBalance < $examFee) {
    //             return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
    //         }
    //         $mcqs = Mcq::with('answers')
    //                     ->where('topic_id', $selectedTopic)
    //                     ->get();

    //         if ($mcqs->isEmpty()) {
    //             return redirect()->route('user.mcq.exam', [
    //                 'admission' => $selectedAdmission,
    //                 'department' => $selectedDepartment,
    //                 'subject' => $selectedSubject,
    //                 'topic' => $selectedTopic,
    //             ])->with('error', 'This topic has no questions.');
    //         }
    //     }

    //     return view('user.exam.mcq', compact(
    //         'pageTitle','admissions','departments','subjects','topics',
    //         'selectedAdmission','selectedDepartment','selectedSubject','selectedTopic','mcqs','examStart','studyMode'
    //     ));

    // }


    public function submit(Request $request)
    {
        // dd($request->all());
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
        $timeTaken = $request->time_taken;
        

        $alreadyExists = ExamResult::where('user_id', Auth::id())
            ->where('admission_id', $request->admission)
            ->where('department_id', $request->department)
            ->where('subject_id', $request->subject)
            ->where('topic_id', $request->topic)
            ->first();

        // ✅ Only insert if not already exists
        if (!$alreadyExists) {
            $examResult = ExamResult::create([
                'user_id'       => Auth::id(),
                'admission_id'  => $request->admission,
                'department_id' => $request->department,
                'subject_id'    => $request->subject,
                'topic_id'      => $request->topic,
                'total'         => $total,
                'correct'       => $correct,
                'wrong'         => $wrong,
                'score'         => $score,
                'time_taken'    => $timeTaken,
                'given_answers' => $answers,
            ]);
        } else {
            $examResult = $alreadyExists;
        }

        $examResult = ExamResult::with(['user','admission','department','subject','topic'])->find($examResult->id);

        $givenAnswers = $examResult->given_answers;

        return view('user.exam.result', [
            'pageTitle' => "Exam Result",
            'examResult' => $examResult,
            'total' => $examResult->total,
            'correct' => $examResult->correct,
            'wrong' => $examResult->wrong,
            'score' => $examResult->score,
            'timeTaken' => $examResult->time_taken,
            'mcqs' => Mcq::with('answers')->whereIn('id', array_keys($givenAnswers ?? []))->get(),
            'answers' => $givenAnswers,
        ]);
    }

    public function examView($id)
    {
        $pageTitle = "Exam Result";

        $examResult = ExamResult::with(['user','admission','department','subject','topic'])->findOrFail($id);

        $givenAnswers = $examResult->given_answers;

        // Fetch MCQs with answers
        $mcqs = Mcq::with('answers')->whereIn('id', array_keys($givenAnswers ?? []))->get();

        return view('user.exam.view', compact('pageTitle','examResult','givenAnswers','mcqs'));
    }

    public function reportList()
    {
        $pageTitle = "Exam Reports";

        $examResults = ExamResult::with(['admission','department','subject','topic'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.exam.reports', compact('pageTitle','examResults'));
    }



}
