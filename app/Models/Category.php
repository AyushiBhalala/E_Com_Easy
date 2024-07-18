<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'slug', 'status', 'showHome', 'image', 'action'
        ];
    public function sub_category(){
        return $this->hasMany(SubCategory::class);
    }
}
