<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    // Ang mga fields na pwedeng i-save
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    // Relationship: Para makuha natin ang details ng user na nag-trigger nito
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ito ang simpleng function para mag-log ng activity.
     * Halimbawa ng paggamit: ActivityLog::record('Login', 'User logged in successfully');
     */
    public static function record($action, $description = null)
    {
        static::create([
            'user_id' => Auth::id(), // Auto-detect kung may naka-login
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(), // Auto-get ng IP address
        ]);
    }
}