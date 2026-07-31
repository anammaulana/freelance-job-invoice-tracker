<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['original_filename', 'stored_path', 'disk', 'mime_type', 'size', 'uploaded_by_user_id'])]
class Document extends Model
{
    public const DISK = 'local';

    public const DIRECTORY = 'documents';

    public const MAX_UPLOAD_KILOBYTES = 5120;

    public const ALLOWED_MIMES = [
        'pdf',
        'jpg',
        'jpeg',
        'png',
        'webp',
        'txt',
        'csv',
        'doc',
        'docx',
        'xls',
        'xlsx',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
