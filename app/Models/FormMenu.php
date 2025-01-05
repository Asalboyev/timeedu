<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // SoftDeletes xususiyatini import qilish


class FormMenu extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['text', 'order', 'init', 'title', 'dinamik_men_id'];

    // Dinamik menyu bilan bog‘lanish
    public function dinamikMen()
    {
        return $this->belongsTo(DinamikMen::class, 'dinamik_men_id');
    }

    // Fayllar bilan bog‘lanish
    public function files()
    {
        return $this->hasMany(File::class, 'form_id');
    }
}
