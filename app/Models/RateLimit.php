<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RateLimit extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'attempts', 'expires_at'];

    /**
     * Check if the rate limit has expired.
     *
     * @return bool
     */
    public function hasExpired()
    {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Reset the attempts and set the expiration time.
     *
     * @param  int  $attempts
     * @param  int  $decayMinutes
     * @return void
     */
    public function resetAttempts($attempts, $decayMinutes)
    {
        $this->attempts = $attempts;
        $this->expires_at = Carbon::now()->addMinutes($decayMinutes);
        $this->save();
    }
}