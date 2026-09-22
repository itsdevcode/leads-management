<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    /** @use HasFactory<\Database\Factories\LeadNoteFactory> */
    use HasFactory;

    protected $fillable = ['lead_id', 'user_id', 'content'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
