<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'lead_text',
        'content',
        'contact_email',
        'contact_address',
    ];
}
