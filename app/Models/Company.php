<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email'
    ];

    public function addressable() {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function phones() {
        return $this->morphMany(Phone::class, 'callable');
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'company_employees', 'company_id', 'user_id')
            ->withPivot('role_id');
    }

}
