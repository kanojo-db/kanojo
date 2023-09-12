<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'scribe.index')->name('scribe');

Route::get('/postman', function () {
    return new JsonResponse(Storage::disk('local')->get('scribe/collection.json'), json: true);
})->name('scribe.postman');

Route::get('/openapi', function () {
    return response()->file(Storage::disk('local')->path('scribe/openapi.yaml'));
})->name('scribe.openapi');
