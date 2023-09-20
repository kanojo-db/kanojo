<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreSceneRequest;
use App\Http\Requests\UpdateSceneRequest;
use App\Models\Scene;

class SceneController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSceneRequest $request): void
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSceneRequest $request, Scene $scene): void
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scene $scene): void
    {

    }
}
