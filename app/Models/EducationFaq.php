<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationFaq extends Model
{
    use HasFactory;
    protected $table = 'education_faqs';
    protected $fillable = ['educational_program_id','skill_id','question', 'answer'];

    public function educationalProgram()
    {
        return $this->belongsTo(EducationalProgram::class, 'educational_program_id');
    }
    public function skill()
    {
        return $this->belongsTo(ERskill::class, 'skill_id');
    }


}
