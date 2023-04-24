import type route from 'ziggy-js';

import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';

import { PageProps as AppPageProps } from './';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    const route: route;
    const Ziggy: ZiggyConfig;
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: route;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}
