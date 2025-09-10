<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    // User who initiated the transaction (from_id)
    public function initiator()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    // User who received the transaction (user_id)
    public function recipient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Optionally: from_user — if you distinguish this from from_id
    public function originUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }

}
