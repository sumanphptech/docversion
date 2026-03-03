<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    protected $fillable = ['document_id', 'version_number', 'content'];

    // Belongs to a document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

}
