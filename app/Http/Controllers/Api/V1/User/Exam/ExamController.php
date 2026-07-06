<?php

namespace App\Http\Controllers\Api\V1\User\Exam;

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
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /**
     * Get exam data with filters
     */
    public function getExamData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admission' => 'nullable|exists:admissions,id',
                'department' => 'nullable|exists:departments,id',
                'subject' => 'nullable|exists:subjects,id',
                'topic' => 'nullable|exists:topics,id',
                'group' => 'nullable|exists:groups,id',
                'paper_final' => 'nullable|exists:paper_finals,id',
                'model_test' => 'nullable|exists:model_tests,id',
                'exam' => 'nullable|boolean',
                'study' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

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
            $selectedAdmissionData = null;
            $userHasAttempted = false;
            $isExamStarted = false;
            $isStudyMode = false;

            // Get selected admission data
            if ($selectedAdmission) {
                $selectedAdmissionData = Admission::find($selectedAdmission);
            }

            // Step 1: Load departments for any admission
            if ($selectedAdmission) {
                $departments = Department::where('admission_id', $selectedAdmission)
                    ->where('status', 1)
                    ->orderBy('id', 'asc')
                    ->get();
                
                if ($departments->isEmpty() && !$selectedDepartment) {
                    return response()->json([
                        'success' => false,
                        'message' => 'এই Admission এ কোনো Department নেই।'
                    ], 404);
                }
            }

            // Step 2: Load subjects for any department
            if ($selectedDepartment) {
                $subjects = Subject::where('department_id', $selectedDepartment)
                    ->where('status', 1)
                    ->orderBy('id', 'asc')
                    ->get();
                
                if ($subjects->isEmpty() && !$selectedSubject) {
                    return response()->json([
                        'success' => false,
                        'message' => 'এই Department এ কোনো Subject নেই।'
                    ], 404);
                }
            }

            // Step 3: Load topics for any subject
            if ($selectedSubject) {
                $topics = Topic::where('subject_id', $selectedSubject)
                    ->where('status', 1)
                    ->orderBy('id', 'asc')
                    ->get();
                
                if ($topics->isEmpty() && !$selectedTopic) {
                    return response()->json([
                        'success' => false,
                        'message' => 'এই Subject এ কোনো Topic নেই।'
                    ], 404);
                }
            }

            // Step 4: Admission-specific workflows
            if ($selectedAdmissionData) {
                $admissionName = $selectedAdmissionData->name;
                
                // University Admission workflow
                if ($admissionName == 'ভার্সিটি এডমিশন') {
                    // Check if user has already attempted this topic
                    if ($selectedTopic) {
                        $userHasAttempted = ExamResult::where('user_id', $user->id)
                            ->where('topic_id', $selectedTopic)
                            ->exists();
                    }

                    // study start for University Admission
                    if ($selectedTopic && $studyMode) {
                        $isStudyMode = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('topic_id', $selectedTopic)
                                    ->where('mcq_type', 2)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Topic এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }

                    // exam start for University Admission
                    if ($selectedTopic && $examStart) {
                        $selectedTopicData = Topic::find($selectedTopic);

                        if (!$selectedTopicData) {
                            return response()->json([
                                'success' => false,
                                'message' => 'টপিক পাওয়া যায়নি।'
                            ], 404);
                        }

                        $examFee = $selectedTopicData->fee;
                        $userBalance = $user->main_wallet;

                        if ($userBalance < $examFee) {
                            return response()->json([
                                'success' => false,
                                'message' => 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই।'
                            ], 400);
                        }

                        // Deduct fee
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

                        $isExamStarted = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('topic_id', $selectedTopic)
                                    ->where('mcq_type', 1)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Topic এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }
                }
                // Paper Final Exam workflow
                elseif ($admissionName == 'পেপার ফাইনাল এক্সাম') {
                    // Load groups for this admission type
                    if ($selectedDepartment) {
                        $groups = Group::where('department_id', $selectedDepartment)
                            ->where('status', 1)
                            ->orderBy('id', 'asc')
                            ->get();
                        
                        if ($groups->isEmpty() && !$selectedGroup) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Department এ কোনো Group নেই।'
                            ], 404);
                        }
                    }

                    if ($selectedGroup) {
                        // Load subjects for this group
                        $subjects = Subject::where('group_id', $selectedGroup)
                            ->where('status', 1)
                            ->orderBy('id', 'asc')
                            ->get();
                        
                        if ($subjects->isEmpty() && !$selectedSubject) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Group এ কোনো Subject নেই।'
                            ], 404);
                        }
                    }

                    if ($selectedSubject) {
                        $paperFinals = PaperFinal::where('subject_id', $selectedSubject)
                            ->where('status', 1)
                            ->orderBy('id', 'asc')
                            ->get();
                        
                        if ($paperFinals->isEmpty() && !$selectedPaperFinal) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Subject এ কোনো Paper Final নেই।'
                            ], 404);
                        }
                    }

                    // Check if user has already attempted this paper final
                    if ($selectedPaperFinal) {
                        $userHasAttempted = ExamResult::where('user_id', $user->id)
                            ->where('paper_final_id', $selectedPaperFinal)
                            ->exists();
                    }

                    // study start for Paper Final
                    if ($selectedPaperFinal && $studyMode) {
                        $isStudyMode = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('paper_final_id', $selectedPaperFinal)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Paper Final এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }

                    // exam start for Paper Final
                    if ($selectedPaperFinal && $examStart) {
                        $selectedPaperFinalData = PaperFinal::find($selectedPaperFinal);

                        if (!$selectedPaperFinalData) {
                            return response()->json([
                                'success' => false,
                                'message' => 'পেপার ফাইনাল পাওয়া যায়নি।'
                            ], 404);
                        }

                        $examFee = $selectedPaperFinalData->fee;
                        $userBalance = $user->main_wallet;

                        if ($userBalance < $examFee) {
                            return response()->json([
                                'success' => false,
                                'message' => 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই।'
                            ], 400);
                        }

                        $user->main_wallet -= $examFee;
                        $user->save(); 

                        Transaction::create([
                            'from_id'   => $user->id,            
                            'user_id'   => null,                
                            'from_user' => $user->id,            
                            'out'       => 'exam_fee',      
                            'status'    => 'success',
                            'purpose'   => 'Exam Fee Deducted for Paper Final: ' . $selectedPaperFinalData->name,
                            'amount'    => $examFee,
                        ]);

                        $isExamStarted = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('paper_final_id', $selectedPaperFinal)
                                    ->where('mcq_type', 3)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Paper Final এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }
                }
                // Final Model Test workflow
                elseif ($admissionName == 'ফাইনাল মডেল টেস্ট এক্সাম') {
                    // Load groups for this admission type
                    if ($selectedDepartment) {
                        $groups = Group::where('department_id', $selectedDepartment)
                            ->where('status', 1)
                            ->orderBy('id', 'asc')
                            ->get();
                        
                        if ($groups->isEmpty() && !$selectedGroup) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Department এ কোনো Group নেই।'
                            ], 404);
                        }
                    }

                    if ($selectedGroup) {
                        $modelTests = ModelTest::where('group_id', $selectedGroup)
                            ->where('status', 1)
                            ->orderBy('id', 'asc')
                            ->get();
                        
                        if ($modelTests->isEmpty() && !$selectedModelTest) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Group এ কোনো Model Test নেই।'
                            ], 404);
                        }
                    }

                    // Check if user has already attempted this model test
                    if ($selectedModelTest) {
                        $userHasAttempted = ExamResult::where('user_id', $user->id)
                            ->where('model_test_id', $selectedModelTest)
                            ->exists();
                    }

                    // study start for Model Test
                    if ($selectedModelTest && $studyMode) {
                        $isStudyMode = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('model_test_id', $selectedModelTest)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Model Test এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }

                    // exam start for Model Test
                    if ($selectedModelTest && $examStart) {
                        $selectedModelTestData = ModelTest::find($selectedModelTest);

                        if (!$selectedModelTestData) {
                            return response()->json([
                                'success' => false,
                                'message' => 'মডেল টেস্ট পাওয়া যায়নি।'
                            ], 404);
                        }

                        $examFee = $selectedModelTestData->fee;
                        $userBalance = $user->main_wallet;

                        if ($userBalance < $examFee) {
                            return response()->json([
                                'success' => false,
                                'message' => 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই।'
                            ], 400);
                        }

                        $user->main_wallet -= $examFee;
                        $user->save(); 

                        Transaction::create([
                            'from_id'   => $user->id,            
                            'user_id'   => null,                
                            'from_user' => $user->id,            
                            'out'       => 'exam_fee',      
                            'status'    => 'success',
                            'purpose'   => 'Exam Fee Deducted for Model Test: ' . $selectedModelTestData->name,
                            'amount'    => $examFee,
                        ]);

                        $isExamStarted = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('model_test_id', $selectedModelTest)
                                    ->where('mcq_type', 4)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Model Test এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }
                }
                // DEFAULT WORKFLOW FOR OTHER ADMISSIONS
                else {
                    // Check if user has already attempted this topic
                    if ($selectedTopic) {
                        $userHasAttempted = ExamResult::where('user_id', $user->id)
                            ->where('topic_id', $selectedTopic)
                            ->exists();
                    }

                    // study start for other admissions
                    if ($selectedTopic && $studyMode) {
                        $isStudyMode = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('topic_id', $selectedTopic)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Topic এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }

                    // exam start for other admissions
                    if ($selectedTopic && $examStart) {
                        $selectedTopicData = Topic::find($selectedTopic);

                        if (!$selectedTopicData) {
                            return response()->json([
                                'success' => false,
                                'message' => 'টপিক পাওয়া যায়নি।'
                            ], 404);
                        }

                        $examFee = $selectedTopicData->fee;
                        $userBalance = $user->main_wallet;

                        if ($userBalance < $examFee) {
                            return response()->json([
                                'success' => false,
                                'message' => 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালান্স নেই।'
                            ], 400);
                        }

                        $user->main_wallet -= $examFee;
                        $user->save(); 

                        Transaction::create([
                            'from_id'   => $user->id,            
                            'user_id'   => null,                
                            'from_user' => $user->id,            
                            'out'       => 'exam_fee',      
                            'status'    => 'success',
                            'purpose'   => 'Exam Fee Deducted for Topic: ' . $selectedTopicData->name,
                            'amount'    => $examFee,
                        ]);

                        $isExamStarted = true;
                        $mcqs = Mcq::with('answers')
                                    ->where('topic_id', $selectedTopic)
                                    ->get();

                        if ($mcqs->isEmpty()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'এই Topic এ কোনো প্রশ্ন নেই।'
                            ], 404);
                        }
                    }
                }
            }
            
            // Prepare response data
            $responseData = [
                'success' => true,
                'data' => [
                    'admissions' => $admissions->map(function($admission) {
                        return [
                            'id' => $admission->id,
                            'name' => $admission->name,
                            'status' => $admission->status,
                        ];
                    }),
                    'departments' => $departments->map(function($department) {
                        return [
                            'id' => $department->id,
                            'name' => $department->name,
                            'status' => $department->status,
                        ];
                    }),
                    'subjects' => $subjects->map(function($subject) {
                        return [
                            'id' => $subject->id,
                            'name' => $subject->name,
                            'status' => $subject->status,
                        ];
                    }),
                    'topics' => $topics->map(function($topic) {
                        return [
                            'id' => $topic->id,
                            'name' => $topic->name,
                            'fee' => $topic->fee,
                            'status' => $topic->status,
                        ];
                    }),
                    'groups' => $groups->map(function($group) {
                        return [
                            'id' => $group->id,
                            'name' => $group->name,
                            'status' => $group->status,
                        ];
                    }),
                    'paper_finals' => $paperFinals->map(function($paperFinal) {
                        return [
                            'id' => $paperFinal->id,
                            'name' => $paperFinal->name,
                            'fee' => $paperFinal->fee,
                            'status' => $paperFinal->status,
                        ];
                    }),
                    'model_tests' => $modelTests->map(function($modelTest) {
                        return [
                            'id' => $modelTest->id,
                            'name' => $modelTest->name,
                            'fee' => $modelTest->fee,
                            'status' => $modelTest->status,
                        ];
                    }),
                    'mcqs' => $mcqs->map(function($mcq) {
                        return [
                            'id' => $mcq->id,
                            'question' => $mcq->question,
                            'question_type' => $mcq->question_type,
                            'mcq_type' => $mcq->mcq_type,
                            'answers' => $mcq->answers->map(function($answer) {
                                return [
                                    'id' => $answer->id,
                                    'answer' => $answer->answer,
                                    'is_correct' => $answer->is_correct,
                                ];
                            }),
                        ];
                    }),
                    'selected_filters' => [
                        'admission' => $selectedAdmission,
                        'department' => $selectedDepartment,
                        'subject' => $selectedSubject,
                        'topic' => $selectedTopic,
                        'group' => $selectedGroup,
                        'paper_final' => $selectedPaperFinal,
                        'model_test' => $selectedModelTest,
                    ],
                    'exam_status' => [
                        'is_exam_started' => $isExamStarted,
                        'is_study_mode' => $isStudyMode,
                        'has_attempted' => $userHasAttempted,
                    ],
                    'selected_admission_data' => $selectedAdmissionData ? [
                        'id' => $selectedAdmissionData->id,
                        'name' => $selectedAdmissionData->name,
                    ] : null,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'main_wallet' => $user->main_wallet,
                    ]
                ]
            ];

            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit exam answers
     */
    public function submitExam(Request $request)
    {
        // dd('hi');
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'answers' => 'required|array',
                'answers.*' => 'required|exists:answers,id',
                'time_taken' => 'required|integer',
                'admission' => 'required|exists:admissions,id',
                'department' => 'required|exists:departments,id',
                'subject' => 'nullable|exists:subjects,id',
                'topic' => 'nullable|exists:topics,id',
                'group' => 'nullable|exists:groups,id',
                'paper_final' => 'nullable|exists:paper_finals,id',
                'model_test' => 'nullable|exists:model_tests,id',
                'admission_data' => 'required|string', // Exam type
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            $answers = $request->answers;
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
            $userId = $user->id;

            $examType = $request->admission_data;

            // Check if already exists
            $alreadyExists = null;
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

            // Load relationships
            $examResult->load(['user','admission','department','subject','topic','group','modelTest','paperFinal']);

            // Prepare MCQ data with answers
            $mcqData = Mcq::with('answers')->whereIn('id', array_keys($answers))->get();

            return response()->json([
                'success' => true,
                'message' => 'Exam submitted successfully!',
                'data' => [
                    'exam_result' => [
                        'id' => $examResult->id,
                        'total' => $examResult->total,
                        'correct' => $examResult->correct,
                        'wrong' => $examResult->wrong,
                        'score' => $examResult->score,
                        'time_taken' => $examResult->time_taken,
                        'created_at' => $examResult->created_at,
                        'user' => [
                            'id' => $examResult->user->id,
                            'name' => $examResult->user->name,
                            'email' => $examResult->user->email,
                        ],
                        'admission' => $examResult->admission ? [
                            'id' => $examResult->admission->id,
                            'name' => $examResult->admission->name,
                        ] : null,
                        'department' => $examResult->department ? [
                            'id' => $examResult->department->id,
                            'name' => $examResult->department->name,
                        ] : null,
                        'subject' => $examResult->subject ? [
                            'id' => $examResult->subject->id,
                            'name' => $examResult->subject->name,
                        ] : null,
                        'topic' => $examResult->topic ? [
                            'id' => $examResult->topic->id,
                            'name' => $examResult->topic->name,
                        ] : null,
                        'group' => $examResult->group ? [
                            'id' => $examResult->group->id,
                            'name' => $examResult->group->name,
                        ] : null,
                        'model_test' => $examResult->modelTest ? [
                            'id' => $examResult->modelTest->id,
                            'name' => $examResult->modelTest->name,
                        ] : null,
                        'paper_final' => $examResult->paperFinal ? [
                            'id' => $examResult->paperFinal->id,
                            'name' => $examResult->paperFinal->name,
                        ] : null,
                    ],
                    'questions' => $mcqData->map(function($mcq) use ($answers) {
                        return [
                            'id' => $mcq->id,
                            'question' => $mcq->question,
                            'given_answer_id' => $answers[$mcq->id] ?? null,
                            'answers' => $mcq->answers->map(function($answer) {
                                return [
                                    'id' => $answer->id,
                                    'answer' => $answer->answer,
                                    'is_correct' => $answer->is_correct,
                                ];
                            }),
                        ];
                    }),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * View exam result by ID
     */
    public function viewExamResult($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            $examResult = ExamResult::with(['user','admission','department','subject','topic','group','modelTest','paperFinal'])
                ->findOrFail($id);

            // Check if the result belongs to the authenticated user
            if ($examResult->user_id != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this result.'
                ], 403);
            }

            $givenAnswers = $examResult->given_answers;
            $mcqs = Mcq::with('answers')->whereIn('id', array_keys($givenAnswers ?? []))->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'exam_result' => [
                        'id' => $examResult->id,
                        'total' => $examResult->total,
                        'correct' => $examResult->correct,
                        'wrong' => $examResult->wrong,
                        'score' => $examResult->score,
                        'time_taken' => $examResult->time_taken,
                        'created_at' => $examResult->created_at,
                        'user' => [
                            'id' => $examResult->user->id,
                            'name' => $examResult->user->name,
                            'email' => $examResult->user->email,
                        ],
                        'admission' => $examResult->admission ? [
                            'id' => $examResult->admission->id,
                            'name' => $examResult->admission->name,
                        ] : null,
                        'department' => $examResult->department ? [
                            'id' => $examResult->department->id,
                            'name' => $examResult->department->name,
                        ] : null,
                        'subject' => $examResult->subject ? [
                            'id' => $examResult->subject->id,
                            'name' => $examResult->subject->name,
                        ] : null,
                        'topic' => $examResult->topic ? [
                            'id' => $examResult->topic->id,
                            'name' => $examResult->topic->name,
                        ] : null,
                        'group' => $examResult->group ? [
                            'id' => $examResult->group->id,
                            'name' => $examResult->group->name,
                        ] : null,
                        'model_test' => $examResult->modelTest ? [
                            'id' => $examResult->modelTest->id,
                            'name' => $examResult->modelTest->name,
                        ] : null,
                        'paper_final' => $examResult->paperFinal ? [
                            'id' => $examResult->paperFinal->id,
                            'name' => $examResult->paperFinal->name,
                        ] : null,
                    ],
                    'questions' => $mcqs->map(function($mcq) use ($givenAnswers) {
                        return [
                            'id' => $mcq->id,
                            'question' => $mcq->question,
                            'question_type' => $mcq->question_type,
                            'given_answer_id' => $givenAnswers[$mcq->id] ?? null,
                            'answers' => $mcq->answers->map(function($answer) {
                                return [
                                    'id' => $answer->id,
                                    'answer' => $answer->answer,
                                    'is_correct' => $answer->is_correct,
                                ];
                            }),
                        ];
                    }),
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exam result not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exam reports list
     */
    public function getExamReports()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            $examResults = ExamResult::with(['admission','department','subject','topic','group','modelTest','paperFinal'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_exams' => $examResults->count(),
                    'average_score' => $examResults->avg('score'),
                    'total_correct' => $examResults->sum('correct'),
                    'total_wrong' => $examResults->sum('wrong'),
                    'exam_results' => $examResults->map(function($result) {
                        return [
                            'id' => $result->id,
                            'total' => $result->total,
                            'correct' => $result->correct,
                            'wrong' => $result->wrong,
                            'score' => $result->score,
                            'time_taken' => $result->time_taken,
                            'created_at' => $result->created_at,
                            'admission' => $result->admission ? $result->admission->name : null,
                            'department' => $result->department ? $result->department->name : null,
                            'subject' => $result->subject ? $result->subject->name : null,
                            'topic' => $result->topic ? $result->topic->name : null,
                            'group' => $result->group ? $result->group->name : null,
                            'model_test' => $result->modelTest ? $result->modelTest->name : null,
                            'paper_final' => $result->paperFinal ? $result->paperFinal->name : null,
                        ];
                    }),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}