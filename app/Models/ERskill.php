<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ERskill extends Model
{
    use HasFactory;
    protected $fillable = ['name','active','parent_id'];

    protected $casts = ['name'=>'array'];

    public function entranceRequirements()
    {
        return $this->belongsToMany(EntranceRequirement::class, 'entrance_requirement_e_rskill', 'entrance_requirement_id', 'skill_id');
    }
    public function children()
    {
        return $this->hasMany(EducationFaq::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(EducationFaq::class, 'parent_id');
    }
    public function faq()
    {
        return $this->hasOne(EducationFaq::class, 'skill_id');
    }

}
