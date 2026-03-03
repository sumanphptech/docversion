<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Document;


class DocumentApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_document_with_version_1()
    {
        $response = $this->postJson('/api/documents', [
            'title' => 'First Document',
            'slug' => 'first-doc',
            'content' => 'Initial content',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'document' => [
                'title' => 'First Document',
                'slug' => 'first-doc',
            ],
            'latest_version' => [
                'version_number' => 1,
                'content' => 'Initial content',
            ]
        ]);

        $this->assertDatabaseHas('documents', [
            'title' => 'First Document',
            'slug' => 'first-doc',
        ]);

        $this->assertDatabaseHas('document_versions', [
            'version_number' => 1,
            'content' => 'Initial content',
        ]);
    }

    /** @test */
    public function it_updates_document_and_creates_new_version()
    {
        // First, create the document
        $document = Document::create([
            'title' => 'First Document',
            'slug' => 'first-doc',
            'user_id' => 1,
        ]);

        // Create the first version
        $document->versions()->create([
            'version_number' => 1,
            'content' => 'Initial content',
        ]);

        // Call the same API to update content
        $response = $this->postJson('/api/documents', [
            'title' => 'First Document',
            'slug' => 'first-doc',
            'content' => 'Updated content',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'latest_version' => [
                'version_number' => 2,
                'content' => 'Updated content',
            ]
        ]);

        $this->assertDatabaseHas('document_versions', [
            'version_number' => 2,
            'content' => 'Updated content',
        ]);
    }
}
