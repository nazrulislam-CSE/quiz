<?php

namespace Database\Seeders;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $ranks = [
            [
                'rank_name'    => 'LEARNER',
                'rank_deposit' => 100,
                'rank_reward'  => 0,
                'reward_text'  => null,
                'status'       => 0,
            ],
            [
                'rank_name'    => 'DREAMER',
                'rank_deposit' => 50000,
                'rank_reward'  => 5000,
                'reward_text'  => '৳5,000 Cash অথবা Cox’s Bazar Tour',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'EDTECH ENTREPRENEUR',
                'rank_deposit' => 250000,
                'rank_reward'  => 15000,
                'reward_text'  => '৳15,000 Cash অথবা India Tour',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'PLAN MASTER',
                'rank_deposit' => 1000000,
                'rank_reward'  => 50000,
                'reward_text'  => '৳50,000 Cash অথবা Nepal Tour',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'MERIT STAR',
                'rank_deposit' => 3000000,
                'rank_reward'  => 100000,
                'reward_text'  => '৳1,00,000 Cash অথবা Thailand Tour',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'CAMPUS CHAMPION',
                'rank_deposit' => 6000000,
                'rank_reward'  => 200000,
                'reward_text'  => '৳2,00,000 Cash অথবা Motorcycle',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'LEADER',
                'rank_deposit' => 12000000,
                'rank_reward'  => 500000,
                'reward_text'  => '৳5,00,000 Cash অথবা Couple Umrah + Bike',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'EDTECH BRAND BUILDER',
                'rank_deposit' => 24000000,
                'rank_reward'  => 1500000,
                'reward_text'  => '৳15,00,000 Cash অথবা Couple Hajj',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'TOP EDTECH CONSULT',
                'rank_deposit' => 48000000,
                'rank_reward'  => 3500000,
                'reward_text'  => '৳35,00,000 Cash অথবা Private Car | Monthly Salary: ৳40,000',
                'status'       => 0,
            ],
            [
                'rank_name'    => 'NATIONAL RANK ACHIEVER',
                'rank_deposit' => 96000000,
                'rank_reward'  => 10000000,
                'reward_text'  => '৳1,00,00,000 অথবা Luxury Apartment | Company Profit Share: 1%',
                'status'       => 0,
            ],
        ];

        foreach ($ranks as $rank) {
            Rank::updateOrCreate(
                [
                    'user_id'   => $user->id,
                    'rank_name' => $rank['rank_name'],
                ],
                [
                    'rank_deposit' => $rank['rank_deposit'],
                    'rank_reward'  => $rank['rank_reward'],
                    'reward_text'  => $rank['reward_text'],
                    'status'       => $rank['status'],
                ]
            );
        }
    }
}