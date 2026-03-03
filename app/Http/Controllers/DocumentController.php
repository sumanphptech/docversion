<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;


class DocumentController extends Controller
{

    // public function index()
    // {
    //     $documents = Document::with(['versions' => function($q) {
    //         $q->orderBy('version_number', 'desc');
    //     }])->get();

    //     return view('documents.index', compact('documents'));
    // }

    public function publish(Request $request, $slug)
    {
        // Validate 
        $request->validate([
            'version_id' => 'required|integer'
        ]);

        // Find document by slug
        $document = Document::where('slug', $slug)->firstOrFail();

        $versionId = $request->version_id;

        // Check if the selected version belongs to this document
        $versionExists = $document->versions()
                                ->where('id', $versionId)
                                ->exists();

        if (!$versionExists) {
            return response()->json([
                'message' => 'Invalid version selected'
            ], 400);
        }

        // Update published version
        $document->update([
            'version_id' => $versionId
        ]);

        return response()->json([
            'message' => 'Version published successfully',
            'published_version_id' => $versionId
        ]);
    }
    



    public function versions($slug)
    {
        // Just pass the slug to the Blade; JS will fetch full data via API
        return view('documents.versions', ['slug' => $slug]);
    }

    public function apiVersionsList($slug)
    {
        $document = Document::with('versions')
            ->where('slug', $slug)
            ->firstOrFail();

        // Map to include only latest version
        // $data = $documents->map(function($doc) {
        //     $latest = $doc->versions->first();
        //     return [
        //         'id' => $doc->id,
        //         'title' => $doc->title,
        //         'slug' => $doc->slug,
        //         'latest_version' => $latest ? $latest->version_number : null,
        //         'content' => $latest ? $latest->content : null,
        //     ];
        // });

        return response()->json($document);
    }


    public function edit($slug)
    {
        // Just pass the slug to the Blade; JS will fetch full data via API
        return view('documents.edit', ['slug' => $slug]);
    }

    public function apiShow($slug)
    {
        $document = Document::with('versions')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'id' => $document->id,
            'title' => $document->title,
            'slug' => $document->slug,
            'versions' => $document->versions->map(function($v) {
                return [
                    'version_number' => $v->version_number,
                    'content' => $v->content,
                ];
            }),
        ]);
    }

    public function getDocumentsList()
    {
        $documents = Document::with(['versions' => function($q) {
            $q->orderBy('version_number', 'desc'); // latest version first
        }])->get();

        // Map to include only latest version
        $data = $documents->map(function($doc) {
            $latest = $doc->versions->first();
            return [
                'id' => $doc->id,
                'title' => $doc->title,
                'slug' => $doc->slug,
                'latest_version' => $latest ? $latest->version_number : null,
                'content' => $latest ? $latest->content : null,
            ];
        });

        return response()->json($data);
    }

    public function create()
    {
        return view('documents.create'); 
    }    

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Use a dummy user (you already seeded)
        $userId = 1; // replace with actual user id if needed

        // Find existing document by slug or create a new one
        $document = Document::firstOrCreate(
            ['slug' => $request->slug],
            [
                'title' => $request->title,
                'user_id' => $userId
            ]
        );

        // Get latest version number for this document
        $latestVersion = $document->versions()->orderByDesc('version_number')->first();
        $nextVersionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;

        // Create a new document version
        $version = $document->versions()->create([
            'version_number' => $nextVersionNumber,
            'content' => $request->content,
        ]);

        $document->version_id = $version->id;
        $document->save();

        return response()->json([
            'message' => $latestVersion ? 'Document updated with new version '.$nextVersionNumber : 'Document created with version 1',
            'document' => [
                'id' => $document->id,
                'title' => $document->title,
                'slug' => $document->slug,
            ],
            'latest_version' => [
                'version_number' => $version->version_number,
                'content' => $version->content,
            ]
        ]);
    }

}
