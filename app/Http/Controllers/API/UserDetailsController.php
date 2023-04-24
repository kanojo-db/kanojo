<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class UserDetailsController extends Controller
{
    /**
     * Details
     *
     * Get a user's details.
     *
     * @group Account
     *
     * @urlParam account_id required The ID of the account.
     */
    public function show(Request $request, string $account_id): JsonResponse
    {
        $user = User::where('id', $account_id)->firstOrFail();

        return response()->json(
            fractal()
                ->item($user)
                ->transformWith(new UserTransformer())
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
