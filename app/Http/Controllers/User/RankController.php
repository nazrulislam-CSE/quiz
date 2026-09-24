<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BalanceRequest;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RankController extends Controller
{
    public function rank()
    {
        $pageTitle = 'My Rank';

        $user = Auth::user();

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

        if (! empty($teamUserIds)) {
            $teamDeposit = BalanceRequest::whereIn('user_id', $teamUserIds)
                ->where('status', 'approved')
                ->where('payment_status', 'paid')
                ->sum('amount');
        }

        // =========================
        // TOTAL TEAM DEPOSIT
        // Own + Team
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
        $teamRanks = Rank::whereIn('user_id', $teamUserIds)
            ->where('status', 1)
            ->get()
            ->groupBy('rank_name');

        $rankCounts = [
            'DREAMER' => $teamRanks->get('DREAMER', collect())->count(),

            'EDTECH ENTREPRENEUR' => $teamRanks->get('EDTECH ENTREPRENEUR', collect())->count(),

            'PLAN MASTER' => $teamRanks->get('PLAN MASTER', collect())->count(),

            'MERIT STAR' => $teamRanks->get('MERIT STAR', collect())->count(),

            'CAMPUS CHAMPION' => $teamRanks->get('CAMPUS CHAMPION', collect())->count(),

            'LEADER' => $teamRanks->get('LEADER', collect())->count(),

            'EDTECH BRAND BUILDER' => $teamRanks->get('EDTECH BRAND BUILDER', collect())->count(),

            'TOP EDTECH CONSULT' => $teamRanks->get('TOP EDTECH CONSULT', collect())->count(),
        ];

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

        return view('user.rank.index', compact(
            'pageTitle',
            'currentRank',
            'ownDeposit',
            'directMemberCount',
            'teamMemberCount',
            'teamDeposit',
            'totalNetworkDeposit',
            'directPaidCustomer',
            'rankCounts',
            'nextRank'
        ));
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
    ) {

        $currentRankName = $currentRank?->rank_name;

        // =========================================
        // RANK ORDER
        // =========================================
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

        // =========================================
        // CURRENT RANK INDEX
        // =========================================
        $currentIndex = $currentRankName
            ? array_search($currentRankName, $rankNames)
            : -1;

        if ($currentIndex === false) {
            $currentIndex = -1;
        }

        // =========================================
        // NEXT RANK
        // =========================================
        $nextRankName = $rankNames[$currentIndex + 1] ?? null;

        if (! $nextRankName) {
            return null;
        }

        // =========================================
        // ALL RANK REQUIREMENTS
        // =========================================
        $ranks = [

            // =====================================
            // 1. FREE MEMBER
            // =====================================
            'FREE MEMBER' => [
                'own_deposit' => 0,
                'description' => 'Referral এর মাধ্যমে FREE MEMBER হওয়া যায়।',
            ],

            // =====================================
            // 2. LEARNER
            // =====================================
            'LEARNER' => [
                'own_deposit' => 100,
                'description' => 'Minimum ৳100 নিজের Deposit সম্পন্ন করতে হবে।',
            ],

            // =====================================
            // 3. DREAMER
            // =====================================
            'DREAMER' => [
                'direct_paid' => 50,
                'team_deposit' => 50000,
                'description' => '50 জন Direct Paid Customer এবং Team Deposit ৳50,000 করতে হবে।',
            ],

            // =====================================
            // 4. EDTECH ENTREPRENEUR
            // =====================================
            'EDTECH ENTREPRENEUR' => [
                'rank_name' => 'DREAMER',
                'rank_count' => 5,
                'team_deposit' => 250000,
                'description' => 'Team থেকে 5 জন DREAMER এবং Team Deposit ৳2,50,000 করতে হবে।',
            ],

            // =====================================
            // 5. PLAN MASTER
            // =====================================
            'PLAN MASTER' => [
                'rank_name' => 'EDTECH ENTREPRENEUR',
                'rank_count' => 4,
                'team_deposit' => 1000000,
                'description' => 'Team থেকে 4 জন EDTECH ENTREPRENEUR এবং Team Deposit ৳10,00,000 করতে হবে।',
            ],

            // =====================================
            // 6. MERIT STAR
            // =====================================
            'MERIT STAR' => [
                'rank_name' => 'PLAN MASTER',
                'rank_count' => 3,
                'team_deposit' => 3000000,
                'description' => 'Team থেকে 3 জন PLAN MASTER এবং Team Deposit ৳30,00,000 করতে হবে।',
            ],

            // =====================================
            // 7. CAMPUS CHAMPION
            // =====================================
            'CAMPUS CHAMPION' => [
                'rank_name' => 'MERIT STAR',
                'rank_count' => 2,
                'team_deposit' => 6000000,
                'description' => 'Team থেকে 2 জন MERIT STAR এবং Team Deposit ৳60,00,000 করতে হবে।',
            ],

            // =====================================
            // 8. LEADER
            // =====================================
            'LEADER' => [
                'rank_name' => 'CAMPUS CHAMPION',
                'rank_count' => 2,
                'team_deposit' => 12000000,
                'description' => 'Team থেকে 2 জন CAMPUS CHAMPION এবং Team Deposit ৳1,20,00,000 করতে হবে।',
            ],

            // =====================================
            // 9. EDTECH BRAND BUILDER
            // =====================================
            'EDTECH BRAND BUILDER' => [
                'rank_name' => 'LEADER',
                'rank_count' => 2,
                'team_deposit' => 24000000,
                'description' => 'Team থেকে 2 জন LEADER এবং Team Deposit ৳2,40,00,000 করতে হবে।',
            ],

            // =====================================
            // 10. TOP EDTECH CONSULT
            // =====================================
            'TOP EDTECH CONSULT' => [
                'rank_name' => 'EDTECH BRAND BUILDER',
                'rank_count' => 2,
                'team_deposit' => 48000000,
                'description' => 'Team থেকে 2 জন EDTECH BRAND BUILDER এবং Team Deposit ৳4,80,00,000 করতে হবে।',
            ],

            // =====================================
            // 11. NATIONAL RANK ACHIEVER
            // =====================================
            'NATIONAL RANK ACHIEVER' => [
                'rank_name' => 'TOP EDTECH CONSULT',
                'rank_count' => 2,
                'team_deposit' => 96000000,
                'description' => 'Team থেকে 2 জন TOP EDTECH CONSULT এবং Team Deposit ৳9,60,00,000 করতে হবে।',
            ],
        ];

        $requirement = $ranks[$nextRankName] ?? [];

        // =========================================
        // REMAINING DEPOSIT
        // =========================================
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

        // =========================================
        // REMAINING PEOPLE
        // =========================================
        $remainingPeople = 0;

        // DREAMER
        if ($nextRankName === 'DREAMER') {

            $remainingPeople = max(
                0,
                50 - $directPaidCustomer
            );
        }

        // Higher ranks
        elseif (isset($requirement['rank_name'])) {

            $requiredRank = $requirement['rank_name'];

            $currentCount = $rankCounts[$requiredRank] ?? 0;

            $remainingPeople = max(
                0,
                $requirement['rank_count'] - $currentCount
            );
        }

        // =========================================
        // RETURN DATA
        // =========================================
        return [
            'name' => $nextRankName,

            'requirement' => $requirement,

            'remaining_deposit' => $remainingDeposit,

            'remaining_people' => $remainingPeople,

            'description' => $requirement['description'] ?? null,
        ];
    }

    // =====================================================
    // TEAM IDS
    // =====================================================
    private function getTeamUserIds($userId)
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

        return array_unique($teamIds);
    }
}
