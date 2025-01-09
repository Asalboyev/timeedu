<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class FormMenu extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['text', 'order', 'position', 'title', 'dinamik_menu_id','photo'];
    protected $casts = [
        'title' => 'array',
        'text' => 'array',

    ];


    // Dinamik menyu bilan bog‘lanish
    public function dinamikMen()
    {
        return $this->belongsTo(DinamikMen::class, 'dinamik_menu_id');
    }

    // Fayllar bilan bog‘lanish
    public function files()
    {
        return $this->hasMany(FileMenu::class, 'form_id');
    }
    public function postsmenuCategories()
    {
        return $this->belongsToMany(PostsCategory::class, 'form_menu_category', 'form_menu_id', 'category_id');
    }



}
