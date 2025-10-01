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
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class ExamController extends Controller
{
    public function create(Request $request)
    {
        // dd($request->all());
        $pageTitle = "MCQ Exam";
        $submitted = false;
        $correct = 0;
        $wrong = 0;
        $score = 0;
        $total = 0;

        $admissions = Admission::where('status', 1)->orderBy('id', 'asc')->get();

        $selectedAdmission = $request->query('admission');
        $selectedDepartment = $request->query('department');
        $selectedSubject = $request->query('subject');
        $selectedTopic = $request->query('topic');
        $selectedGroup = $request->query('group');
        $selectedPaperFinal = $request->query('paper_final');
        $selectedModelTest = $request->query('model_test');
        $examStart = $request->query('exam');
        $studyMode = $request->query('study');
         

        $departments = collect();
        $subjects = collect();
        $topics = collect();
        $groups = collect();
        $paperFinals = collect();
        $modelTests = collect();
        $mcqs = collect();

        // Get selected admission data
        $selectedAdmissionData = null;
        if ($selectedAdmission) {
            $selectedAdmissionData = Admission::find($selectedAdmission);
        }

        $user = Auth::user();
        $userHasAttempted = false;

        // ভার্সিটি এডমিশন workflow
        if ($selectedAdmissionData && $selectedAdmissionData->name == 'ভার্সিটি এডমিশন') {
            if ($selectedAdmission) {
                $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->orderBy('id', 'asc')->get();
                if ($departments->isEmpty() && !$selectedDepartment) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No departments available for this admission.');
                }
            }

            if ($selectedDepartment) {
                $subjects = Subject::where('department_id', $selectedDepartment)->where('status',1)->orderBy('id', 'asc')->get();
                if ($subjects->isEmpty() && !$selectedSubject) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No subjects available for this department.');
                }
            }

            if ($selectedSubject) {
                $topics = Topic::where('subject_id', $selectedSubject)->where('status',1)->orderBy('id', 'asc')->get();
                if ($topics->isEmpty() && !$selectedTopic) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No topics available for this subject.');
                }
            }

            // Check if user has already attempted this topic
            if ($selectedTopic) {
                $userHasAttempted = ExamResult::where('user_id', $user->id)
                    ->where('topic_id', $selectedTopic)
                    ->exists();
            }

            // study start for ভার্সিটি এডমিশন
            if ($selectedTopic && $studyMode) {
                $mcqs = Mcq::with('answers')
                            ->where('topic_id', $selectedTopic)
                            ->where('mcq_type', 2)
                            ->get();

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'subject' => $selectedSubject,
                        'topic' => $selectedTopic,
                    ])->with('error', 'This topic has no questions.');
                }
            }

            // exam start for ভার্সিটি এডমিশন
            if ($selectedTopic && $examStart) {
                $selectedTopicData = Topic::find($selectedTopic);

                if (!$selectedTopicData) {
                    return redirect()->back()->with('error', 'টপিক পাওয়া যায়নি।');
                }

                $examFee = $selectedTopicData->fee;
                $user = Auth::user();
                $userBalance = $user->main_wallet;

                if ($userBalance < $examFee) {
                    return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
                }

                $user->main_wallet -= $examFee;
                $user->save(); 

                // Transaction log 
                Transaction::create([
                    'from_id'   => $user->id,            
                    'user_id'   => null,                
                    'from_user' => $user->id,            
                    'out'       => 'exam_fee',      
                    'status'    => 'success',
                    'purpose'   => 'Exam Fee Deducted for Topic: ' . $selectedTopicData->name,
                    'amount'    => $examFee,
                ]);

                $mcqs = Mcq::with('answers')
                            ->where('topic_id', $selectedTopic)
                            ->where('mcq_type', 1)
                            ->get();

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'subject' => $selectedSubject,
                        'topic' => $selectedTopic,
                    ])->with('error', 'This topic has no questions.');
                }
            }
        }
        // পেপার ফাইনাল এক্সাম workflow - UPDATED
        elseif ($selectedAdmissionData && $selectedAdmissionData->name == 'পেপার ফাইনাল এক্সাম') {
            if ($selectedAdmission) {
                $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->orderBy('id', 'asc')->get();
                if ($departments->isEmpty() && !$selectedDepartment) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No departments available for this admission.');
                }
            }

            if ($selectedDepartment) {
                $groups = Group::where('department_id', $selectedDepartment)->where('status',1)->orderBy('id', 'asc')->get();
                if ($groups->isEmpty() && !$selectedGroup) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No groups available for this department.');
                }
            }

            if ($selectedGroup) {
                $subjects = Subject::where('group_id', $selectedGroup)->where('status',1)->orderBy('id', 'asc')->get();
                if ($subjects->isEmpty() && !$selectedSubject) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No subjects available for this group.');
                }
            }

            if ($selectedSubject) {
                $paperFinals = PaperFinal::where('subject_id', $selectedSubject)->where('status',1)->orderBy('id', 'asc')->get();
                if ($paperFinals->isEmpty() && !$selectedPaperFinal) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No paper finals available for this subject.');
                }
            }

            // Check if user has already attempted this paper final
            if ($selectedPaperFinal) {
                $userHasAttempted = ExamResult::where('user_id', $user->id)
                    ->where('paper_final_id', $selectedPaperFinal)
                    ->exists();
            }

            // study start for পেপার ফাইনাল এক্সাম
            if ($selectedPaperFinal && $studyMode) {
                $mcqs = Mcq::with('answers')
                            ->where('paper_final_id', $selectedPaperFinal)
                            ->get();

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'group' => $selectedGroup,
                        'subject' => $selectedSubject,
                        'paper_final' => $selectedPaperFinal,
                    ])->with('error', 'This paper final has no questions.');
                }
            }

            // exam start for পেপার ফাইনাল এক্সাম
            if ($selectedPaperFinal && $examStart) {
                $selectedPaperFinalData = PaperFinal::find($selectedPaperFinal);

                if (!$selectedPaperFinalData) {
                    return redirect()->back()->with('error', 'পেপার ফাইনাল পাওয়া যায়নি।');
                }

                $examFee = $selectedPaperFinalData->fee;
                $user = Auth::user();
                $userBalance = $user->main_wallet;

                if ($userBalance < $examFee) {
                    return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
                }

                $user->main_wallet -= $examFee;
                $user->save(); 

                // Transaction log 
                Transaction::create([
                    'from_id'   => $user->id,            
                    'user_id'   => null,                
                    'from_user' => $user->id,            
                    'out'       => 'exam_fee',      
                    'status'    => 'success',
                    'purpose'   => 'Exam Fee Deducted for Paper Final: ' . $selectedPaperFinalData->name,
                    'amount'    => $examFee,
                ]);

                $mcqs = Mcq::with('answers')
                            ->where('paper_final_id', $selectedPaperFinal)
                            ->where('mcq_type', 3)
                            ->get();

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'group' => $selectedGroup,
                        'subject' => $selectedSubject,
                        'paper_final' => $selectedPaperFinal,
                    ])->with('error', 'This paper final has no questions.');
                }
            }
        }
        // ফাইনাল মডেল টেস্ট এক্সাম workflow
        elseif ($selectedAdmissionData && $selectedAdmissionData->name == 'ফাইনাল মডেল টেস্ট এক্সাম') {
            if ($selectedAdmission) {
                $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->orderBy('id', 'asc')->get();
                if ($departments->isEmpty() && !$selectedDepartment) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No departments available for this admission.');
                }
            }

            if ($selectedDepartment) {
                $groups = Group::where('department_id', $selectedDepartment)->where('status',1)->orderBy('id', 'asc')->get();
                if ($groups->isEmpty() && !$selectedGroup) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No groups available for this department.');
                }
            }

            if ($selectedGroup) {
                $modelTests = ModelTest::where('group_id', $selectedGroup)->where('status',1)->orderBy('id', 'asc')->get();
                if ($modelTests->isEmpty() && !$selectedModelTest) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No model tests available for this group.');
                }
            }

            // Check if user has already attempted this model test
            if ($selectedModelTest) {
                $userHasAttempted = ExamResult::where('user_id', $user->id)
                    ->where('model_test_id', $selectedModelTest)
                    ->exists();
            }
            

            // study start for ফাইনাল মডেল টেস্ট এক্সাম
            if ($selectedModelTest && $studyMode) {
                $mcqs = Mcq::with('answers')
                            ->where('model_test_id', $selectedModelTest)
                            ->get();

                

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'group' => $selectedGroup,
                        'model_test' => $selectedModelTest,
                    ])->with('error', 'This model test has no questions.');
                }
            }

            // exam start for ফাইনাল মডেল টেস্ট এক্সাম
            if ($selectedModelTest && $examStart) {
                $selectedModelTestData = ModelTest::find($selectedModelTest);

                if (!$selectedModelTestData) {
                    return redirect()->back()->with('error', 'মডেল টেস্ট পাওয়া যায়নি।');
                }

                $examFee = $selectedModelTestData->fee;
                $user = Auth::user();
                $userBalance = $user->main_wallet;

                if ($userBalance < $examFee) {
                    return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
                }

                $user->main_wallet -= $examFee;
                $user->save(); 

                // Transaction log 
                Transaction::create([
                    'from_id'   => $user->id,            
                    'user_id'   => null,                
                    'from_user' => $user->id,            
                    'out'       => 'exam_fee',      
                    'status'    => 'success',
                    'purpose'   => 'Exam Fee Deducted for Model Test: ' . $selectedModelTestData->name,
                    'amount'    => $examFee,
                ]);

                $mcqs = Mcq::with('answers')
                            ->where('model_test_id', $selectedModelTest)
                            ->where('mcq_type', 4)
                            ->get();

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'group' => $selectedGroup,
                        'model_test' => $selectedModelTest,
                    ])->with('error', 'This model test has no questions.');
                }
            }
        }else {
            if ($selectedAdmission) {
                $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->orderBy('id', 'asc')->get();
                if ($departments->isEmpty() && !$selectedDepartment) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No departments available for this admission.');
                }
            }

            if ($selectedDepartment) {
                $subjects = Subject::where('department_id', $selectedDepartment)->where('status',1)->orderBy('id', 'asc')->get();
                if ($subjects->isEmpty() && !$selectedSubject) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No subjects available for this department.');
                }
            }

            if ($selectedSubject) {
                $topics = Topic::where('subject_id', $selectedSubject)->where('status',1)->orderBy('id', 'asc')->get();
                if ($topics->isEmpty() && !$selectedTopic) {
                    return redirect()->route('user.mcq.exam')->with('error', 'No topics available for this subject.');
                }
            }

            // Check if user has already attempted this topic for default case
            if ($selectedTopic) {
                $userHasAttempted = ExamResult::where('user_id', $user->id)
                    ->where('topic_id', $selectedTopic)
                    ->exists();
            }

            // study start for ভার্সিটি এডমিশন
            if ($selectedTopic && $studyMode) {
                $mcqs = Mcq::with('answers')
                            ->where('topic_id', $selectedTopic)
                            ->where('mcq_type', 2)
                            ->get();

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'subject' => $selectedSubject,
                        'topic' => $selectedTopic,
                    ])->with('error', 'This topic has no questions.');
                }
            }

            // exam start for ভার্সিটি এডমিশন
            if ($selectedTopic && $examStart) {
                $selectedTopicData = Topic::find($selectedTopic);

                if (!$selectedTopicData) {
                    return redirect()->back()->with('error', 'টপিক পাওয়া যায়নি।');
                }

                $examFee = $selectedTopicData->fee;
                $user = Auth::user();
                $userBalance = $user->main_wallet;

                if ($userBalance < $examFee) {
                    return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই। অনুগ্রহ করে রিচার্জ করুন।');
                }

                $user->main_wallet -= $examFee;
                $user->save(); 

                // Transaction log 
                Transaction::create([
                    'from_id'   => $user->id,            
                    'user_id'   => null,                
                    'from_user' => $user->id,            
                    'out'       => 'exam_fee',      
                    'status'    => 'success',
                    'purpose'   => 'Exam Fee Deducted for Topic: ' . $selectedTopicData->name,
                    'amount'    => $examFee,
                ]);

                $mcqs = Mcq::with('answers')
                            ->where('topic_id', $selectedTopic)
                            ->get();

                if ($mcqs->isEmpty()) {
                    return redirect()->route('user.mcq.exam', [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'subject' => $selectedSubject,
                        'topic' => $selectedTopic,
                    ])->with('error', 'This topic has no questions.');
                }
            }
        }
        
        return view('user.exam.mcq', compact(
            'pageTitle','admissions','departments','subjects','topics','groups','paperFinals','modelTests',
            'selectedAdmission','selectedDepartment','selectedSubject','selectedTopic','selectedGroup',
            'selectedPaperFinal','selectedModelTest','mcqs','examStart','studyMode','selectedAdmissionData','userHasAttempted'
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
        $timeTaken = $request->time_taken;
        $userId = Auth::id();

        $examType = $request->admission_data; // এখানে exam type পাঠানো হয়েছে

        // Conditional alreadyExists check
        if($examType == 'ভার্সিটি এডমিশন') {
            $alreadyExists = ExamResult::where('user_id', $userId)
                ->where('admission_id', $request->admission)
                ->where('department_id', $request->department)
                ->where('subject_id', $request->subject)
                ->where('topic_id', $request->topic)
                ->first();
        } elseif($examType == 'পেপার ফাইনাল এক্সাম') {
            $alreadyExists = ExamResult::where('user_id', $userId)
                ->where('admission_id', $request->admission)
                ->where('department_id', $request->department)
                ->where('group_id', $request->group)
                ->where('subject_id', $request->subject)
                ->where('paper_final_id', $request->paper_final)
                ->first();
        } elseif($examType == 'ফাইনাল মডেল টেস্ট এক্সাম') {
            $alreadyExists = ExamResult::where('user_id', $userId)
                ->where('admission_id', $request->admission)
                ->where('department_id', $request->department)
                ->where('group_id', $request->group)
                ->where('model_test_id', $request->model_test)
                ->first();
        } else {
            $alreadyExists = ExamResult::where('user_id', $userId)
                ->where('admission_id', $request->admission)
                ->where('department_id', $request->department)
                ->where('subject_id', $request->subject)
                ->where('topic_id', $request->topic)
                ->first();
        }

        // Only insert if not exists
        if (!$alreadyExists) {
            $examResult = ExamResult::create([
                'user_id'       => $userId,
                'admission_id'  => $request->admission,
                'department_id' => $request->department,
                'group_id'      => $request->group,
                'subject_id'    => $request->subject,
                'topic_id'      => $request->topic,
                'model_test_id' => $request->model_test,
                'paper_final_id'=> $request->paper_final,
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

        $examResult = ExamResult::with(['user','admission','department','subject','topic','group','modelTest','paperFinal'])
            ->find($examResult->id);

        return view('user.exam.result', [
            'pageTitle' => $pageTitle,
            'examResult' => $examResult,
            'total' => $examResult->total,
            'correct' => $examResult->correct,
            'wrong' => $examResult->wrong,
            'score' => $examResult->score,
            'timeTaken' => $examResult->time_taken,
            'mcqs' => Mcq::with('answers')->whereIn('id', array_keys($answers ?? []))->get(),
            'answers' => $answers,
        ]);
    }


    public function examView($id)
    {
        $pageTitle = "Exam Result";

        $examResult = ExamResult::with(['user','admission','department','subject','topic','group','modelTest','paperFinal'])->findOrFail($id);

        $givenAnswers = $examResult->given_answers;

        // Fetch MCQs with answers
        $mcqs = Mcq::with('answers')->whereIn('id', array_keys($givenAnswers ?? []))->get();

        return view('user.exam.view', compact('pageTitle','examResult','givenAnswers','mcqs'));
    }

    public function reportList()
    {
        $pageTitle = "Exam Reports";

        $examResults = ExamResult::with(['admission','department','subject','topic','group','modelTest','paperFinal'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.exam.reports', compact('pageTitle','examResults'));
    }



}
