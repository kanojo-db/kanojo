import type {
    ErrorBag,
    Errors,
    PageProps as InertiaPageProps,
    Page,
} from '@inertiajs/core';

import { User } from './models';

export interface PageProps extends Page<InertiaPageProps> {
    errors: Errors & ErrorBag;
    locale: string;
    user?: User;
    [key: string]: unknown;
}
