<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(User $user): array
    {
        return [
            'avatar' => $user->has_gravatar ? $user->gravatar_url : null,
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
}
