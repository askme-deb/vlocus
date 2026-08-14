<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    
    protected $fillable = [
        'name',
        'brand_id',
        'description',
        'is_visible',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
}
