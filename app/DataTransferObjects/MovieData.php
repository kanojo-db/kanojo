<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App;
use App\Models\Movie;
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
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MovieData extends Data
{
    public function __construct(
        public ?int $id,
        public string|Optional $title,
        public string|Optional $originalTitle,
        /** @var array<string, string>|Optional|Lazy */
        public array|Optional|Lazy $allTitles,
        public string $productCode,

        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public Carbon|Optional|Lazy $releaseDate,

        public int|Optional|Lazy $duration,
        public StudioData|Optional|Lazy $studio,
        public MovieTypeData $type,

        /** @var DataCollection<array-key, ModelData>|Lazy */
        #[DataCollectionOf(ModelData::class)]
        public DataCollection|Lazy $cast,

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

      public static function fromModel(Movie $movie): self
      {
          $locale = App::getLocale();

          return new self(
              $movie->id,
              (string) $movie->getTranslation('title', $locale, true),
              (string) $movie->getTranslation('title', 'ja-JP', false),
              Lazy::create(fn () => $movie->getTranslations('title')),
              $movie->product_code,
              Lazy::create(fn () => $movie->release_date ?? Optional::create()),
              Lazy::create(fn () => $movie->length ?? Optional::create()),
              Lazy::create(fn () => $movie->studio ?? Optional::create()),
              MovieTypeData::from($movie->type),
              Lazy::create(fn (): DataCollection => ModelData::collection($movie->models)),
              Lazy::create(fn () => $movie->created_at ?? Optional::create()),
              Lazy::create(fn () => $movie->updated_at ?? Optional::create()),
              Lazy::create(fn () => $movie->deleted_at ?? Optional::create()),
          );
      }
}
