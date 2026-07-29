<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Document;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;

final class DocumentService
{
    public function __construct(
        private readonly DatabaseManager $db
    ) {}

    /**
     * Upload a new document.
     *
     * @param  array<string, mixed>  $data
     */
    public function upload(array $data, UploadedFile $file): Document
    {
        return $this->db->transaction(function () use ($data, $file) {
            $document = clone new Document;

            $path = $file->store('documents', 'public');
            if (is_string($path)) {
                $data['file_path'] = $path;
                $data['file_extension'] = $file->getClientOriginalExtension();
                $data['mime_type'] = $file->getMimeType();
                $data['file_size'] = $file->getSize();
            }

            $document->fill($data);
            $document->save();

            return $document;
        });
    }

    /**
     * Update an existing document metadata or replace file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Document $document, array $data, ?UploadedFile $file = null): Document
    {
        return $this->db->transaction(function () use ($document, $data, $file) {
            if ($file) {
                $path = $file->store('documents', 'public');
                if (is_string($path)) {
                    $data['file_path'] = $path;
                    $data['file_extension'] = $file->getClientOriginalExtension();
                    $data['mime_type'] = $file->getMimeType();
                    $data['file_size'] = $file->getSize();
                }
            }

            $document->fill($data);
            $document->save();

            return $document;
        });
    }

    /**
     * Toggle public visibility of a document.
     */
    public function toggleVisibility(Document $document): Document
    {
        $document->update(['is_public' => ! $document->is_public]);

        return $document;
    }

    /**
     * Increment download count for a document.
     */
    public function incrementDownload(Document $document): void
    {
        // We use raw increment to avoid firing unnecessary events
        $document->increment('download_count');
    }

    /**
     * Remove a document.
     */
    public function remove(Document $document): void
    {
        $document->delete();
    }
}
