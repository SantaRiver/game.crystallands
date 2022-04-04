<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'schema',
        'template_id',
        'description',
        'image',
        'image_preview',
    ];
}
