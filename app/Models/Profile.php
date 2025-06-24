<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'profileable_id',
        'profileable_type',
        'document_type_id',
        'document_number',
        'full_name',
        'email',
        'description',
        'adicional_data',
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
