<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employ extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'surname',
        'email',
        'address',
        'status',
        'birthday',
        'gender',
        'special',
        'photo',
        'phone',
        'started_work',
    ];
    protected $casts = [
        'first_name' => 'array',
        'last_name' => 'array',
        'surname' => 'array',
        'photo' => 'array',
        'started_work' => 'array',
//        'special' => 'array',

    ];

    public function employMeta()
    {
        return $this->hasOne(EmployMeta::class, 'employ_id');
    }
}
