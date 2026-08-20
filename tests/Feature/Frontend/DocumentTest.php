<?php

use App\Enums\PublicationStatus;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Support\Facades\Storage;

it('displays public documents correctly on index page', function () {
    $category = DocumentCategory::factory()->create();

    // Published document
    Document::factory()->create([
        'category_id' => $category->id,
        'title' => 'Published Doc',
        'status' => PublicationStatus::Published,
        'published_at' => now()->subDay(),
    ]);

    // Draft document
    Document::factory()->create([
        'category_id' => $category->id,
        'title' => 'Draft Doc',
        'status' => PublicationStatus::Draft,
    ]);

    $this->get(route('documents.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Public/Documents/Index')
            ->has('documents.data', 1)
            ->where('documents.data.0.title', 'Published Doc')
        );
});

it('can download a document and increments download count exactly once per session', function () {
    Storage::fake('public');
    $category = DocumentCategory::factory()->create();

    $document = Document::factory()->create([
        'category_id' => $category->id,
        'title' => 'Important Doc',
        'status' => PublicationStatus::Published,
        'published_at' => now()->subDay(),
        'file_path' => 'docs/test.pdf',
        'file_name' => 'test.pdf',
        'disk' => 'public',
        'download_count' => 0,
    ]);

    Storage::disk('public')->put('docs/test.pdf', 'dummy content');

    // First download
    $this->get(route('documents.download', $document->ulid))
        ->assertDownload('test.pdf');

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'download_count' => 1,
    ]);

    // Second download in same session shouldn't increment
    $this->get(route('documents.download', $document->ulid))
        ->assertDownload('test.pdf');

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'download_count' => 1,
    ]);
});

it('cannot download draft document', function () {
    $category = DocumentCategory::factory()->create();

    $document = Document::factory()->create([
        'category_id' => $category->id,
        'status' => PublicationStatus::Draft,
    ]);

    $this->get(route('documents.download', $document->ulid))
        ->assertNotFound();
});
