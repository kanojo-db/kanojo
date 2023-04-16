<?php

declare(strict_types=1);

namespace App\Support;

use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Basic;

class Custom extends Basic
{
    public function configure(): void
    {
        parent::configure();

        // Allow Sentry
        $this->addDirective(Directive::SCRIPT, 'sentry.io');
    }
}
