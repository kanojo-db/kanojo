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
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\PaginatedDataCollection;
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

        /** @var PaginatedDataCollection<array-key, MovieData>|Lazy */
        #[DataCollectionOf(MovieData::class)]
        public PaginatedDataCollection|Lazy $movies,

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
        /** @var string */
        $locale = App::getLocale();

        /** @var string */
        $localizedName = $studio->getTranslation('name', $locale, true);
        /** @var string */
        $japaneseName = $studio->getTranslation('name', 'ja-JP', false);

        return new self(
            $studio->id,
            $localizedName,
            $japaneseName,
            Lazy::create(fn () => $studio->getTranslations('title')),
            Lazy::create(fn () => $studio->founded_date ?? Optional::create()),
            Lazy::create(fn (): PaginatedDataCollection => ModelData::collection($studio->movies()->paginate())),
            Lazy::create(fn () => $studio->created_at ?? Optional::create()),
            Lazy::create(fn () => $studio->updated_at ?? Optional::create()),
            Lazy::create(fn () => $studio->deleted_at ?? Optional::create()),
        );
    }
}
