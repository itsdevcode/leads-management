<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'contact_person',
        'email',
        'phone',
        'status',
        'source',
        'priority',
        'follow_up_date',
        'notes_count',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}
