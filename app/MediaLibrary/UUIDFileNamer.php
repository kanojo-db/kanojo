<?php

declare(strict_types=1);

namespace App\MediaLibrary;

use Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer;
use Ramsey\Uuid\Uuid;

class UUIDFileNamer extends DefaultFileNamer {
    public function originalFileName(string $fileName): string
    {
        return Uuid::uuid4()->toString();
    }
};
