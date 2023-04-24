import type {
    Config,
    RouteParams,
    RouteParamsWithQueryOverload,
    Router,
} from 'ziggy-js';

import { config } from '@vue/test-utils';
import route from 'ziggy-js';

config.global.mocks.route = (
    name: string,
    params: RouteParamsWithQueryOverload | RouteParams,
    absolute: boolean,
    config: Config,
): Router | string => {
    return route(name, params, absolute, config);
};
