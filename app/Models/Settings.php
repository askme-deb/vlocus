<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Settings extends Model implements HasMedia
{
    //
     use InteractsWithMedia ;

    protected $fillable = [
        
        'site_name',
        'description',
        'contact_email',
        'contact_phone',
        'logo',
        'favicon',
        'address',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'meta_title',
        'meta_description',
        'cab_search_radius',
        'nearby_search_radius',
    ];
}
