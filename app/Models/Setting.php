<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'site_favicon',
        'site_description',
        'footer_description',
        'contact_email',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
    ];
}