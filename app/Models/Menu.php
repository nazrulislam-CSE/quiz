<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    use HasFactory;
    
    protected $fillable = ['title','location','content','created_at','updated_at'];

    public $timestamps = false;

    public function get_categories(){
        return $this->hasMany(Category::class, 'menu_id', 'id');
    }

    public function get_pages(){
        return $this->belongsTo(Page::class, 'source_id', 'id');
    }
}