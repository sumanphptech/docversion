<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    // All versions of this document
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)
                    ->orderBy('version_number', 'asc'); // oldest first
    }

    // Latest version of this document
    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)
                    ->latestOfMany('version_number'); 
    }

    // uploader relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
