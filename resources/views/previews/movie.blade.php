<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $movie->title }}</title>
    <meta name="robots" content="noindex">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="overflow-hidden" style="width: 1200px; height: 630px;">
        <div class="bg-slate-200 px-6 py-4">
            <img src="https://kanojodb.com/images/logo-light.svg" class="object-contain h-16">
        </div>
        <div class="flex flex-row justify-center gap-8 p-6">
            <!-- If there is a front cover, show it. Otherwise, show a placeholder -->
            @if ($frontCover)
                <img src="{{ $frontCover }}" alt="{{ $movie->title }}" class="rounded shadow-md"
                    style="width: 320px; height: 480px;" />
            @else
                <div class="flex bg-slate-200 text-slate-300 rounded shadow-md"
                    style="min-width: 320px; height: 480px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current">
                        <title>help</title>
                        <path
                            d="M10,19H13V22H10V19M12,2C17.35,2.22 19.68,7.62 16.5,11.67C15.67,12.67 14.33,13.33 13.67,14.17C13,15 13,16 13,17H10C10,15.33 10,13.92 10.67,12.92C11.33,11.92 12.67,11.33 13.5,10.67C15.92,8.43 15.32,5.26 12,5A3,3 0 0,0 9,8H6A6,6 0 0,1 12,2Z" />
                    </svg>
                </div>
            @endif
            <div class="flex flex-col gap-2 justify-center">
                <!-- Show a pill with the movie type -->
                <span class="text-3xl font-bold bg-slate-800 text-white rounded-full px-4 py-2 place-self-start">
                    {{ $movie->type->name }}
                </span>
                <h1 class="text-6xl leading-normal font-bold line-clamp-2 w-fit">
                    {{ $movie->getTranslation('title', 'ja-JP') }}
                </h1>
                <!-- If it had a release date, show it -->
                @if ($movie->release_date)
                    <span class="text-4xl font-bold">
                        Released on {{ $movie->release_date }}
                        @if ($movie->studio)
                            by {{ $movie->studio->getTranslation('name', 'ja-JP') }}
                        @endif
                    </span>
                @endif
                <!-- If it has a studio but no release date, show the studio -->
                @if (!$movie->release_date && $movie->studio)
                    <span class="text-4xl font-bold">
                        Released by {{ $movie->studio->getTranslation('name', 'ja-JP') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
