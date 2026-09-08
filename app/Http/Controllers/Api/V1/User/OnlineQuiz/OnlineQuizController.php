<?php

namespace App\Http\Controllers\Api\V1\User\OnlineQuiz;

use App\Http\Controllers\Controller;
use App\Models\Mcq;
use App\Models\McqQuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class OnlineQuizController extends Controller
{
    /**
     * Get Online Quiz List
     */
    public function index(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first.',
                ], 401);
            }

            $quizzes = Mcq::whereHas('quizAnswers')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Online quiz list retrieved successfully.',
                'data' => $quizzes,
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve online quiz list.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get Quiz Details for Exam
     */
    public function onlineExam(Request $request, $id)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first.',
                ], 401);
            }

            $quiz = Mcq::with('questions.answers')
                ->find($id);

            if (!$quiz) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quiz not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Quiz details retrieved successfully.',
                'data' => $quiz,
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve quiz details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Submit Online Exam
     */
    public function submitExam(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first.',
                ], 401);
            }

            $request->validate([
                'quiz_id' => 'required|exists:mcqs,id',
                'answers' => 'required|array',
                'time_taken' => 'nullable|integer|min:0',
            ]);

            $userId = $user->id;
            $quizId = $request->quiz_id;
            $answers = $request->answers;
            $timeTaken = $request->time_taken;

            DB::beginTransaction();

            foreach ($answers as $questionId => $answerId) {

                McqQuizAnswer::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'question_id' => $questionId,
                        'mcq_id' => $quizId,
                    ],
                    [
                        'answer_id' => $answerId,
                        'time_taken' => $timeTaken,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Exam submitted successfully.',
                'data' => [
                    'quiz_id' => $quizId,
                    'user_id' => $userId,
                    'total_answered' => count($answers),
                    'time_taken' => $timeTaken,
                ],
            ], 200);

        } catch (Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit exam.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get Exam Result
     */
    public function result(Request $request, $quiz_id)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first.',
                ], 401);
            }

            $quiz = Mcq::with('questions.answers')
                ->find($quiz_id);

            if (!$quiz) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quiz not found.',
                ], 404);
            }

            $userId = $user->id;

            $results = [];

            foreach ($quiz->questions as $question) {

                // Get user's submitted answer
                $userAnswer = McqQuizAnswer::where('user_id', $userId)
                    ->where('mcq_id', $quiz_id)
                    ->where('question_id', $question->id)
                    ->first();

                // Get correct answer
                $correctAnswer = $question->answers
                    ->where('is_correct', 1)
                    ->first();

                $correctAnswerId = $correctAnswer?->id;
                $correctAnswerText = $correctAnswer?->answer;

                $userAnswerId = null;
                $userAnswerText = null;

                if (!$userAnswer) {

                    $status = 'notAnswered';

                } else {

                    $userAnswerId = $userAnswer->answer_id;

                    $selectedAnswer = $question->answers
                        ->where('id', $userAnswerId)
                        ->first();

                    $userAnswerText = $selectedAnswer?->answer;

                    $status = $userAnswerId == $correctAnswerId
                        ? 'correct'
                        : 'wrong';
                }

                // Get question options
                $options = $question->answers
                    ->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'answer' => $answer->answer,
                        ];
                    })
                    ->values()
                    ->toArray();

                $results[] = [
                    'question_id' => $question->id,
                    'question' => $question->question,

                    'status' => $status,

                    'user_answer_id' => $userAnswerId,
                    'user_answer' => $userAnswerText,

                    'correct_answer_id' => $correctAnswerId,
                    'correct_answer' => $correctAnswerText,

                    'options' => $options,
                ];
            }

            // Calculate result summary
            $total = count($results);

            $correct = count(array_filter(
                $results,
                fn($result) => $result['status'] === 'correct'
            ));

            $wrong = count(array_filter(
                $results,
                fn($result) => $result['status'] === 'wrong'
            ));

            $notAnswered = count(array_filter(
                $results,
                fn($result) => $result['status'] === 'notAnswered'
            ));

            $percentage = $total > 0
                ? round(($correct / $total) * 100, 2)
                : 0;

            return response()->json([
                'success' => true,
                'message' => 'Exam result retrieved successfully.',

                'data' => [
                    'quiz_id' => $quiz->id,

                    'summary' => [
                        'total_questions' => $total,
                        'correct' => $correct,
                        'wrong' => $wrong,
                        'not_answered' => $notAnswered,
                        'percentage' => $percentage,
                    ],

                    'results' => $results,
                ],
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve exam result.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}