<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function scopeDniRuc(Builder $query)
    {
        return $query->whereIn('code', ['1', '6'])->pluck('id')->toArray();
    }
}
