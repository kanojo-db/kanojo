<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Models\MovieType;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class MovieTypeData extends Data
{
    public function __construct(
    public int|Optional $id,
    public string $name,
    #[WithCast(DateTimeInterfaceCast::class)]
    #[WithTransformer(DateTimeInterfaceTransformer::class)]
    public Carbon $createdAt,
    #[WithCast(DateTimeInterfaceCast::class)]
    #[WithTransformer(DateTimeInterfaceTransformer::class)]
    public Carbon $updatedAt,
  ) {
    }

    public static function fromModel(MovieType $type): self
    {
        return new self(
            $type->id,
            $type->name,
            Carbon::parse($type->created_at),
            Carbon::parse($type->updated_at),
        );
    }
}
