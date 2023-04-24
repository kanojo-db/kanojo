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
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [Keyword::SELF, 'media.kanojodb.com'])
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            // ->addDirective(Directive::SCRIPT, [Keyword::SELF, Keyword::UNSAFE_INLINE, 'js-agent.newrelic.com', '*.nr-data.net'])
            ->addDirective(Directive::STYLE, [Keyword::SELF, Keyword::UNSAFE_INLINE])
            ->addDirective(Directive::FONT, [Keyword::SELF, 'data:'])
            ->addDirective(Directive::CONNECT, 'o4504320317259776.ingest.sentry.io')
            ->addDirective(Directive::REPORT, 'https://o4504320317259776.ingest.sentry.io/api/4504320373620736/security/?sentry_key=5fce5990e0e6417e8855d803341140cd');
    }
}
