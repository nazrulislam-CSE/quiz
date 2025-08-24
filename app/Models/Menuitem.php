<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menuitem extends Model
{
    use HasFactory;

    public function get_menu(){
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
    public function subMenus()
    {
        return $this->hasMany(Menuitem::class, 'parent_id', 'id')
            ->orderBy('position', 'asc');
    }
    public function childMenus()
    {
        return $this->hasMany(Menuitem::class, 'parent_id', 'id')
            ->orderBy('position', 'asc');
    }
}