<?php

namespace App\Http\Controllers\Api\V1\User\Rank;

use App\Http\Controllers\Controller;
use App\Models\BalanceRequest;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RankController extends Controller
{
    public function rank(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // =========================
        // CURRENT RANK
        // =========================
        $currentRank = Rank::where('user_id', $user->id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->first();

        // =========================
        // OWN DEPOSIT
        // =========================
        $ownDeposit = BalanceRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('payment_status', 'paid')
            ->sum('amount');

        // =========================
        // DIRECT MEMBERS
        // =========================
        $directMembers = User::where('refer_by', $user->id)->get();

        $directMemberCount = $directMembers->count();

        // =========================
        // TEAM MEMBERS
        // =========================
        $teamUserIds = $this->getTeamUserIds($user->id);

        $teamMemberCount = count($teamUserIds);

        // =========================
        // TEAM DEPOSIT
        // =========================
        $teamDeposit = 0;

        if (!empty($teamUserIds)) {
            $teamDeposit = BalanceRequest::whereIn('user_id', $teamUserIds)
                ->where('status', 'approved')
                ->where('payment_status', 'paid')
                ->sum('amount');
        }

        // =========================
        // TOTAL NETWORK DEPOSIT
        // =========================
        $totalNetworkDeposit = $ownDeposit + $teamDeposit;

        // =========================
        // DIRECT PAID CUSTOMER
        // =========================
        $directPaidCustomer = 0;

        foreach ($directMembers as $directUser) {

            $deposit = BalanceRequest::where('user_id', $directUser->id)
                ->where('status', 'approved')
                ->where('payment_status', 'paid')
                ->sum('amount');

            if ($deposit >= 100) {
                $directPaidCustomer++;
            }
        }

        // =========================
        // TEAM RANK COUNTS
        // =========================
        $teamRanks = collect();

        if (!empty($teamUserIds)) {
            $teamRanks = Rank::whereIn('user_id', $teamUserIds)
                ->where('status', 1)
                ->get()
                ->groupBy('rank_name');
        }

        $rankNames = [
            'DREAMER',
            'EDTECH ENTREPRENEUR',
            'PLAN MASTER',
            'MERIT STAR',
            'CAMPUS CHAMPION',
            'LEADER',
            'EDTECH BRAND BUILDER',
            'TOP EDTECH CONSULT',
        ];

        $rankCounts = [];

        foreach ($rankNames as $rankName) {
            $rankCounts[$rankName] = $teamRanks
                ->get($rankName, collect())
                ->count();
        }

        // =========================
        // NEXT RANK
        // =========================
        $nextRank = $this->getNextRank(
            $currentRank,
            $ownDeposit,
            $teamDeposit,
            $directPaidCustomer,
            $rankCounts
        );

        // =========================
        // API RESPONSE
        // =========================
        return response()->json([
            'status' => true,
            'message' => 'Rank information retrieved successfully.',

            'data' => [
                'current_rank' => $currentRank ? [
                    'id' => $currentRank->id,
                    'rank_name' => $currentRank->rank_name,
                    'status' => $currentRank->status,
                ] : null,

                'statistics' => [
                    'own_deposit' => (float) $ownDeposit,
                    'direct_member_count' => $directMemberCount,
                    'team_member_count' => $teamMemberCount,
                    'team_deposit' => (float) $teamDeposit,
                    'total_network_deposit' => (float) $totalNetworkDeposit,
                    'direct_paid_customer' => $directPaidCustomer,
                ],

                'rank_counts' => $rankCounts,

                'next_rank' => $nextRank,
            ],
        ]);
    }

    // =====================================================
    // NEXT RANK CALCULATION
    // =====================================================
    private function getNextRank(
        $currentRank,
        $ownDeposit,
        $teamDeposit,
        $directPaidCustomer,
        $rankCounts
    ): ?array {

        $currentRankName = $currentRank?->rank_name;

        // =========================
        // RANK ORDER
        // =========================
        $rankNames = [
            'FREE MEMBER',
            'LEARNER',
            'DREAMER',
            'EDTECH ENTREPRENEUR',
            'PLAN MASTER',
            'MERIT STAR',
            'CAMPUS CHAMPION',
            'LEADER',
            'EDTECH BRAND BUILDER',
            'TOP EDTECH CONSULT',
            'NATIONAL RANK ACHIEVER',
        ];

        // =========================
        // CURRENT RANK INDEX
        // =========================
        $currentIndex = $currentRankName
            ? array_search($currentRankName, $rankNames)
            : -1;

        if ($currentIndex === false) {
            $currentIndex = -1;
        }

        // =========================
        // NEXT RANK
        // =========================
        $nextRankName = $rankNames[$currentIndex + 1] ?? null;

        if (!$nextRankName) {
            return null;
        }

        // =========================
        // REQUIREMENTS
        // =========================
        $ranks = [

            'FREE MEMBER' => [
                'own_deposit' => 0,
                'description' => 'Referral এর মাধ্যমে FREE MEMBER হওয়া যায়।',
            ],

            'LEARNER' => [
                'own_deposit' => 100,
                'description' => 'Minimum ৳100 নিজের Deposit সম্পন্ন করতে হবে।',
            ],

            'DREAMER' => [
                'direct_paid' => 50,
                'team_deposit' => 50000,
                'description' => '50 জন Direct Paid Customer এবং Team Deposit ৳50,000 করতে হবে।',
            ],

            'EDTECH ENTREPRENEUR' => [
                'rank_name' => 'DREAMER',
                'rank_count' => 5,
                'team_deposit' => 250000,
                'description' => 'Team থেকে 5 জন DREAMER এবং Team Deposit ৳2,50,000 করতে হবে।',
            ],

            'PLAN MASTER' => [
                'rank_name' => 'EDTECH ENTREPRENEUR',
                'rank_count' => 4,
                'team_deposit' => 1000000,
                'description' => 'Team থেকে 4 জন EDTECH ENTREPRENEUR এবং Team Deposit ৳10,00,000 করতে হবে।',
            ],

            'MERIT STAR' => [
                'rank_name' => 'PLAN MASTER',
                'rank_count' => 3,
                'team_deposit' => 3000000,
                'description' => 'Team থেকে 3 জন PLAN MASTER এবং Team Deposit ৳30,00,000 করতে হবে।',
            ],

            'CAMPUS CHAMPION' => [
                'rank_name' => 'MERIT STAR',
                'rank_count' => 2,
                'team_deposit' => 6000000,
                'description' => 'Team থেকে 2 জন MERIT STAR এবং Team Deposit ৳60,00,000 করতে হবে।',
            ],

            'LEADER' => [
                'rank_name' => 'CAMPUS CHAMPION',
                'rank_count' => 2,
                'team_deposit' => 12000000,
                'description' => 'Team থেকে 2 জন CAMPUS CHAMPION এবং Team Deposit ৳1,20,00,000 করতে হবে।',
            ],

            'EDTECH BRAND BUILDER' => [
                'rank_name' => 'LEADER',
                'rank_count' => 2,
                'team_deposit' => 24000000,
                'description' => 'Team থেকে 2 জন LEADER এবং Team Deposit ৳2,40,00,000 করতে হবে।',
            ],

            'TOP EDTECH CONSULT' => [
                'rank_name' => 'EDTECH BRAND BUILDER',
                'rank_count' => 2,
                'team_deposit' => 48000000,
                'description' => 'Team থেকে 2 জন EDTECH BRAND BUILDER এবং Team Deposit ৳4,80,00,000 করতে হবে।',
            ],

            'NATIONAL RANK ACHIEVER' => [
                'rank_name' => 'TOP EDTECH CONSULT',
                'rank_count' => 2,
                'team_deposit' => 96000000,
                'description' => 'Team থেকে 2 জন TOP EDTECH CONSULT এবং Team Deposit ৳9,60,00,000 করতে হবে।',
            ],
        ];

        $requirement = $ranks[$nextRankName] ?? [];

        // =========================
        // REMAINING DEPOSIT
        // =========================
        $remainingDeposit = 0;

        if (isset($requirement['own_deposit'])) {

            $remainingDeposit = max(
                0,
                $requirement['own_deposit'] - $ownDeposit
            );

        } elseif (isset($requirement['team_deposit'])) {

            $remainingDeposit = max(
                0,
                $requirement['team_deposit'] - $teamDeposit
            );
        }

        // =========================
        // REMAINING PEOPLE
        // =========================
        $remainingPeople = 0;

        if ($nextRankName === 'DREAMER') {

            $remainingPeople = max(
                0,
                50 - $directPaidCustomer
            );

        } elseif (isset($requirement['rank_name'])) {

            $requiredRank = $requirement['rank_name'];

            $currentCount = $rankCounts[$requiredRank] ?? 0;

            $remainingPeople = max(
                0,
                $requirement['rank_count'] - $currentCount
            );
        }

        // =========================
        // PROGRESS
        // =========================
        $depositRequirement = $requirement['team_deposit']
            ?? $requirement['own_deposit']
            ?? 0;

        $depositCurrent = isset($requirement['own_deposit'])
            ? $ownDeposit
            : $teamDeposit;

        $depositProgress = $depositRequirement > 0
            ? min(100, round(($depositCurrent / $depositRequirement) * 100, 2))
            : 100;

        $peopleRequirement = 0;

        if ($nextRankName === 'DREAMER') {

            $peopleRequirement = 50;

        } elseif (isset($requirement['rank_count'])) {

            $peopleRequirement = $requirement['rank_count'];
        }

        $peopleCurrent = 0;

        if ($nextRankName === 'DREAMER') {

            $peopleCurrent = $directPaidCustomer;

        } elseif (isset($requirement['rank_name'])) {

            $peopleCurrent = $rankCounts[$requirement['rank_name']] ?? 0;
        }

        $peopleProgress = $peopleRequirement > 0
            ? min(100, round(($peopleCurrent / $peopleRequirement) * 100, 2))
            : 100;

        // =========================
        // RETURN
        // =========================
        return [
            'name' => $nextRankName,

            'requirement' => $requirement,

            'remaining_deposit' => (float) $remainingDeposit,

            'remaining_people' => $remainingPeople,

            'deposit_progress' => $depositProgress,

            'people_progress' => $peopleProgress,

            'description' => $requirement['description'] ?? null,
        ];
    }

    // =====================================================
    // GET ALL TEAM USER IDS
    // =====================================================
    private function getTeamUserIds($userId): array
    {
        $teamIds = [];

        $children = User::where('refer_by', $userId)
            ->pluck('id');

        foreach ($children as $childId) {

            $teamIds[] = $childId;

            $teamIds = array_merge(
                $teamIds,
                $this->getTeamUserIds($childId)
            );
        }

        return array_values(array_unique($teamIds));
    }
}
