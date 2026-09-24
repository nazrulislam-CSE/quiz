<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rank_name',
        'rank_deposit',
        'rank_reward',
        'reward_text',
        'status',
    ];

    protected $casts = [
        'rank_deposit' => 'decimal:2',
        'rank_reward' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Rank belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}