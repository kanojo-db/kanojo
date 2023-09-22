import type {
    Config,
    RouteParams,
    RouteParamsWithQueryOverload,
    Router,
} from 'ziggy-js';

import { config } from '@vue/test-utils';
import route from 'ziggy-js';

import { Ziggy } from '@/test/ziggy.generated';

config.global.mocks.route = (
    name: string,
    params: RouteParamsWithQueryOverload | RouteParams,
    absolute: boolean,
    config: Config = Ziggy as Config,
): Router | string => {
    return route(name, params, absolute, config);
};

global.ResizeObserver = require('resize-observer-polyfill');
