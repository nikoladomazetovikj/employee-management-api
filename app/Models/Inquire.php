<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'inquire_id',
        'user_id',
        'status_id',
        'type',
        'start',
        'end',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
