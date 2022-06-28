<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' , 'image' , 'price' , 'overview' , 'details' , 'sold' , 'status' 
    ];

    protected $hidden = [
     'created_at' , 'updated_at' 
    ];
 
    public function favourites()
    {
        return $this->hasMany('App\Models\Favourite','product_id');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart','product_id');
    }

}


