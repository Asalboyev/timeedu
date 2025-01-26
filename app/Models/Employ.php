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
        'dec',
        'started_work',
        'work_time',
        'leader',
        'professor',
    ];
    protected $casts = [
        'first_name' => 'array',
        'last_name' => 'array',
        'surname' => 'array',
        'address' => 'array',
        'work_time' => 'array',
        'dec' => 'array',

    ];

    public function employMeta()
    {
        return $this->hasOne(EmployMeta::class, 'employ_id');
    }

    protected $appends = [
        'lg_img',
        'md_img',
        'sm_img'
    ];
    public function getLgImgAttribute() {
        return $this->photo ? url('').'/upload/images/'.$this->photo : null;
    }

    public function getMdImgAttribute() {
        return $this->photo ? url('').'/upload/images/600/'.$this->photo : null;
    }

    public function getSmImgAttribute() {
        return $this->photo ? url('').'/upload/images/200/'.$this->photo : null;
    }
    public function educational_programs()
    {
        return $this->belongsToMany(EducationalProgram::class, 'educational_program_employ', 'educational_program_id', 'employ_id');
    }


}
