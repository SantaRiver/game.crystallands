<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $name
 * @property string $template_id
 * @property string $description
 * @property string $image
 * @property string $image_preview
 * @mixin Builder
 */
class Cards extends Model
{
    protected $fillable = [
        'name',
        'schema',
        'template_id',
        'description',
        'is_transferable',
        'is_burnable',
        'issued_supply',
        'max_supply',
        'immutable_data',
        'created_at_time',
        'image',
    ];

    protected $casts = [
        'name' => 'string',
        'schema' => 'string',
        'template_id' => 'string',
        'description' => 'string',
        'is_transferable' => 'boolean',
        'is_burnable' => 'boolean',
        'issued_supply' => 'integer',
        'max_supply' => 'integer',
        'immutable_data' => 'array',
        'created_at_time' => 'date',
        'image' => 'string',
    ];
}
