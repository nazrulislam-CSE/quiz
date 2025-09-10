<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;
    protected $guarded = [];

    // User who made the original action (buyer)
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // Referrer who received the commission at this generation level
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
