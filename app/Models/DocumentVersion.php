<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    // Belongs to a document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

}
