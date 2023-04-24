import type { User } from '@/types/models';
import type {
    ErrorBag,
    Errors,
    PageProps as InertiaPageProps,
    Page,
} from '@inertiajs/core';
import type { Config } from 'ziggy-js';

export interface PageProps extends Page<InertiaPageProps> {
    errors: Errors & ErrorBag;
    locale: string;
    user?: User;
    [key: string]: unknown;
    ziggy: Config;
}
