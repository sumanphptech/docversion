<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;


Route::get('/', function() {
    return view('documents.index'); 
})->name('home');

Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');

Route::post('/api/documents', [DocumentController::class, 'store']);


 Route::get('/api/documents', [DocumentController::class, 'getDocumentsList']);

Route::get('/documents/{slug}/edit', [DocumentController::class, 'edit'])->name('documents.edit');

Route::get('/api/documents/{slug}', [DocumentController::class, 'apiShow']);

Route::get('/documents/{slug}/versions', [DocumentController::class, 'versions'])->name('documents.versions');

Route::get('/api/documents/{slug}/versions', [DocumentController::class, 'apiVersionsList']);

Route::post('/api/documents/{slug}/publish', [DocumentController::class, 'publish']);
    


