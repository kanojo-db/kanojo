<?php

declare(strict_types=1);

namespace App\Support;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class Custom extends Basic
{
    public function configure(): void
    {
        parent::configure();

        // Allow inline scripts
        $this->addDirective(Directive::SCRIPT, [Keyword::UNSAFE_INLINE]);
        // Allow all inline styles
        $this->addDirective(Directive::STYLE, [Keyword::UNSAFE_INLINE]);
        // Allow fonts using data: URIs
        $this->addDirective(Directive::FONT, [Keyword::SELF, 'data:']);
        // Allow Sentry
        $this->addDirective(Directive::CONNECT, "'o4504320317259776.ingest.sentry.io'");
    }
}
