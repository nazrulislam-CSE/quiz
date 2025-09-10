<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function division(){
        return $this->belongsTo(Division::class);
    }


    // Transactions where this user received money (commissions, etc.)
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // Transactions where this user triggered the transaction (e.g., a purchase or referral)
    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_id');
    }

    // Generations where this user was referred by someone else (they’re the source of the referral chain)
    public function generationsFrom(): HasMany
    {
        return $this->hasMany(Generation::class, 'from_user_id');
    }

    // Generations where this user received the commission
    public function generationsTo(): HasMany
    {
        return $this->hasMany(Generation::class, 'to_user_id');
    }

    // Referrer (who referred this user)
    public function refer()
    {
        return $this->belongsTo(User::class, 'refer_by');
    }

    // Users that this user has referred
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'refer_by');
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class, 'user_id');
    }



}
