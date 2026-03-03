<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['title', 'slug', 'version_id'];

    // All versions of this document
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)
                    ->orderBy('version_number', 'desc'); 
    }

    // Latest version of this document
    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)
                    ->latestOfMany('version_number'); 
    }

    // published version
    public function publishedVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'version_id');
    }

    // uploader relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
