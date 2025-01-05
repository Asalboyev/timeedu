<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DinamikMenu extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['menu_id', 'title', 'text'];
    protected $casts = [
        'title' => 'array',
        'text' => 'array',

    ];

    // Menu bilan bog‘lanish
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Form bilan bog‘lanish
    public function forms()
    {
        return $this->hasMany(Form::class, 'dinamik_men_id');
    }
}
