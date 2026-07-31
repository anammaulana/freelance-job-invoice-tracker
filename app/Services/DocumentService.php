<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function store(Model $attachable, UploadedFile $file, User $user): Document
    {
        $path = $file->store(Document::DIRECTORY, Document::DISK);

        return $attachable->documents()->create([
            'original_filename' => $file->getClientOriginalName(),
            'stored_path' => $path,
            'disk' => Document::DISK,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize() ?: 0,
            'uploaded_by_user_id' => $user->id,
        ]);
    }

    public function delete(Document $document): void
    {
        Storage::disk($document->disk)->delete($document->stored_path);
        $document->delete();
    }
}
