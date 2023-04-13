<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App;
use App\Models\Studio;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class StudioData extends Data
{
    public function __construct(
        public ?int $id,
        public string|Optional $name,
        public string|Optional $originalName,
        /** @var array<string, string>|Optional|Lazy */
        public array|Optional|Lazy $allNames,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|Optional|Lazy $founded,

        /** @var DataCollection<array-key, MovieData>|Lazy */
        #[DataCollectionOf(MovieData::class)]
        public DataCollection|Lazy $movies,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public CarbonImmutable|Optional|Lazy $createdAt,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public CarbonImmutable|Optional|Lazy $updatedAt,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public CarbonImmutable|Optional|Lazy $deletedAt,

    ) {
    }

    public static function fromModel(Studio $studio): self
    {
        $locale = App::getLocale();

        return new self(
            $studio->id,
            (string) $studio->getTranslation('name', $locale, true),
            (string) $studio->getTranslation('name', 'ja-JP', false),
            Lazy::create(fn () => $studio->getTranslations('title')),
            Lazy::create(fn () => $studio->founded_date ?? Optional::create()),
            Lazy::create(fn (): DataCollection => ModelData::collection($studio->movies)),
            Lazy::create(fn () => $studio->created_at ?? Optional::create()),
            Lazy::create(fn () => $studio->updated_at ?? Optional::create()),
            Lazy::create(fn () => $studio->deleted_at ?? Optional::create()),
        );
    }
}
