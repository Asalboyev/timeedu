<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployMeta extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = [
        'employ_id',
        'department_id',
        'position_id',
        'employ_staff_id',
        'employ_status_id',
        'employ_form_id',
        'contrakt_date',
        'contrakt_number',
        'employ_type_id',
        'active'
    ];



    public function employ()
    {
        return $this->belongsTo(Employ::class, 'employ_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

}
