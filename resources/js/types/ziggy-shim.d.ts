import {
    Config,
    RouteParam,
    RouteParamsWithQueryOverload,
    Router,
} from 'ziggy-js';

type Routes = {
    [key: string]: { uri: string; methods: string[] };
    'debugbar.openhandler': {
        uri: '_debugbar/open';
        methods: ['GET', 'HEAD'];
    };
    'debugbar.clockwork': {
        uri: '_debugbar/clockwork/{id}';
        methods: ['GET', 'HEAD'];
    };
    'debugbar.assets.css': {
        uri: '_debugbar/assets/stylesheets';
        methods: ['GET', 'HEAD'];
    };
    'debugbar.assets.js': {
        uri: '_debugbar/assets/javascript';
        methods: ['GET', 'HEAD'];
    };
    'debugbar.cache.delete': {
        uri: '_debugbar/cache/{key}/{tags?}';
        methods: ['DELETE'];
    };
    scribe: {
        uri: 'docs';
        methods: ['GET', 'HEAD'];
    };
    'scribe.postman': {
        uri: 'docs.postman';
        methods: ['GET', 'HEAD'];
    };
    'scribe.openapi': {
        uri: 'docs.openapi';
        methods: ['GET', 'HEAD'];
    };
    'dusk.login': {
        uri: '_dusk/login/{userId}/{guard?}';
        methods: ['GET', 'HEAD'];
    };
    'dusk.logout': {
        uri: '_dusk/logout/{guard?}';
        methods: ['GET', 'HEAD'];
    };
    'dusk.user': {
        uri: '_dusk/user/{guard?}';
        methods: ['GET', 'HEAD'];
    };
    login: {
        uri: 'login';
        methods: ['GET', 'HEAD'];
    };
    logout: {
        uri: 'logout';
        methods: ['POST'];
    };
    'password.request': {
        uri: 'forgot-password';
        methods: ['GET', 'HEAD'];
    };
    'password.reset': {
        uri: 'reset-password/{token}';
        methods: ['GET', 'HEAD'];
    };
    'password.email': {
        uri: 'forgot-password';
        methods: ['POST'];
    };
    'password.update': {
        uri: 'reset-password';
        methods: ['POST'];
    };
    register: {
        uri: 'register';
        methods: ['GET', 'HEAD'];
    };
    'user-profile-information.update': {
        uri: 'user/profile-information';
        methods: ['PUT'];
    };
    'user-password.update': {
        uri: 'user/password';
        methods: ['PUT'];
    };
    'password.confirmation': {
        uri: 'user/confirmed-password-status';
        methods: ['GET', 'HEAD'];
    };
    'password.confirm': {
        uri: 'user/confirm-password';
        methods: ['POST'];
    };
    'horizon.stats.index': {
        uri: 'horizon/api/stats';
        methods: ['GET', 'HEAD'];
    };
    'horizon.workload.index': {
        uri: 'horizon/api/workload';
        methods: ['GET', 'HEAD'];
    };
    'horizon.masters.index': {
        uri: 'horizon/api/masters';
        methods: ['GET', 'HEAD'];
    };
    'horizon.monitoring.index': {
        uri: 'horizon/api/monitoring';
        methods: ['GET', 'HEAD'];
    };
    'horizon.monitoring.store': {
        uri: 'horizon/api/monitoring';
        methods: ['POST'];
    };
    'horizon.monitoring-tag.paginate': {
        uri: 'horizon/api/monitoring/{tag}';
        methods: ['GET', 'HEAD'];
    };
    'horizon.monitoring-tag.destroy': {
        uri: 'horizon/api/monitoring/{tag}';
        methods: ['DELETE'];
    };
    'horizon.jobs-metrics.index': {
        uri: 'horizon/api/metrics/jobs';
        methods: ['GET', 'HEAD'];
    };
    'horizon.jobs-metrics.show': {
        uri: 'horizon/api/metrics/jobs/{id}';
        methods: ['GET', 'HEAD'];
    };
    'horizon.queues-metrics.index': {
        uri: 'horizon/api/metrics/queues';
        methods: ['GET', 'HEAD'];
    };
    'horizon.queues-metrics.show': {
        uri: 'horizon/api/metrics/queues/{id}';
        methods: ['GET', 'HEAD'];
    };
    'horizon.jobs-batches.index': {
        uri: 'horizon/api/batches';
        methods: ['GET', 'HEAD'];
    };
    'horizon.jobs-batches.show': {
        uri: 'horizon/api/batches/{id}';
        methods: ['GET', 'HEAD'];
    };
    'horizon.jobs-batches.retry': {
        uri: 'horizon/api/batches/retry/{id}';
        methods: ['POST'];
    };
    'horizon.pending-jobs.index': {
        uri: 'horizon/api/jobs/pending';
        methods: ['GET', 'HEAD'];
    };
    'horizon.completed-jobs.index': {
        uri: 'horizon/api/jobs/completed';
        methods: ['GET', 'HEAD'];
    };
    'horizon.silenced-jobs.index': {
        uri: 'horizon/api/jobs/silenced';
        methods: ['GET', 'HEAD'];
    };
    'horizon.failed-jobs.index': {
        uri: 'horizon/api/jobs/failed';
        methods: ['GET', 'HEAD'];
    };
    'horizon.failed-jobs.show': {
        uri: 'horizon/api/jobs/failed/{id}';
        methods: ['GET', 'HEAD'];
    };
    'horizon.retry-jobs.show': {
        uri: 'horizon/api/jobs/retry/{id}';
        methods: ['POST'];
    };
    'horizon.jobs.show': {
        uri: 'horizon/api/jobs/{id}';
        methods: ['GET', 'HEAD'];
    };
    'horizon.index': {
        uri: 'horizon/{view?}';
        methods: ['GET', 'HEAD'];
    };
    'sanctum.csrf-cookie': {
        uri: 'sanctum/csrf-cookie';
        methods: ['GET', 'HEAD'];
    };
    'ignition.healthCheck': {
        uri: '_ignition/health-check';
        methods: ['GET', 'HEAD'];
    };
    'ignition.executeSolution': {
        uri: '_ignition/execute-solution';
        methods: ['POST'];
    };
    'ignition.updateConfig': {
        uri: '_ignition/update-config';
        methods: ['POST'];
    };
    welcome: {
        uri: '/';
        methods: ['GET', 'HEAD'];
    };
    'user.locale.store': {
        uri: 'user/locale';
        methods: ['POST'];
    };
    'settings.account': {
        uri: 'settings/account';
        methods: ['GET', 'HEAD'];
    };
    'settings.account.update': {
        uri: 'settings/account';
        methods: ['POST'];
    };
    'settings.sessions': {
        uri: 'settings/sessions';
        methods: ['GET', 'HEAD'];
    };
    'settings.tokens': {
        uri: 'settings/tokens';
        methods: ['GET', 'HEAD'];
    };
    'settings.tokens.create': {
        uri: 'settings/tokens';
        methods: ['POST'];
    };
    'settings.tokens.destroy': {
        uri: 'settings/tokens/{token}';
        methods: ['DELETE'];
    };
    search: {
        uri: 'search';
        methods: ['GET', 'HEAD'];
    };
    'about.index': {
        uri: 'about';
        methods: ['GET', 'HEAD'];
    };
    'movies.index': {
        uri: 'movies';
        methods: ['GET', 'HEAD'];
    };
    'movies.create': {
        uri: 'movies/create';
        methods: ['GET', 'HEAD'];
    };
    'movies.store': {
        uri: 'movies';
        methods: ['POST'];
    };
    'movies.show': {
        uri: 'movies/{movie}';
        methods: ['GET', 'HEAD'];
    };
    'movies.edit': {
        uri: 'movies/{movie}/edit';
        methods: ['GET', 'HEAD'];
    };
    'movies.update': {
        uri: 'movies/{movie}';
        methods: ['PUT', 'PATCH'];
    };
    'movies.destroy': {
        uri: 'movies/{movie}';
        methods: ['DELETE'];
    };
    'movies.media.index': {
        uri: 'movies/{movie}/media';
        methods: ['GET', 'HEAD'];
    };
    'movies.media.store': {
        uri: 'movies/{movie}/media';
        methods: ['POST'];
    };
    'media.destroy': {
        uri: 'media/{medium}';
        methods: ['DELETE'];
    };
    'movies.history.index': {
        uri: 'movies/{movie}/history';
        methods: ['GET', 'HEAD'];
    };
    'movies.like.store': {
        uri: 'movies/{movie}/like';
        methods: ['POST'];
    };
    'movies.dislike.store': {
        uri: 'movies/{movie}/dislike';
        methods: ['POST'];
    };
    'movies.favorite.store': {
        uri: 'movies/{movie}/favorite';
        methods: ['POST'];
    };
    'favorite.destroy': {
        uri: 'favorite/{favorite}';
        methods: ['DELETE'];
    };
    'movies.wishlist.store': {
        uri: 'movies/{movie}/wishlist';
        methods: ['POST'];
    };
    'wishlist.destroy': {
        uri: 'wishlist/{wishlist}';
        methods: ['DELETE'];
    };
    'movies.collection.store': {
        uri: 'movies/{movie}/collection';
        methods: ['POST'];
    };
    'collection.destroy': {
        uri: 'collection/{collection}';
        methods: ['DELETE'];
    };
    'movies.reports.index': {
        uri: 'movies/{movie}/reports';
        methods: ['GET', 'HEAD'];
    };
    'movies.reports.create': {
        uri: 'movies/{movie}/reports/create';
        methods: ['GET', 'HEAD'];
    };
    'movies.reports.store': {
        uri: 'movies/{movie}/reports';
        methods: ['POST'];
    };
    'reports.show': {
        uri: 'reports/{report}';
        methods: ['GET', 'HEAD'];
    };
    'reports.edit': {
        uri: 'reports/{report}/edit';
        methods: ['GET', 'HEAD'];
    };
    'reports.update': {
        uri: 'reports/{report}';
        methods: ['PUT', 'PATCH'];
    };
    'reports.destroy': {
        uri: 'reports/{report}';
        methods: ['DELETE'];
    };
    'models.index': {
        uri: 'models';
        methods: ['GET', 'HEAD'];
    };
    'models.create': {
        uri: 'models/create';
        methods: ['GET', 'HEAD'];
    };
    'models.store': {
        uri: 'models';
        methods: ['POST'];
    };
    'models.show': {
        uri: 'models/{model}';
        methods: ['GET', 'HEAD'];
    };
    'models.edit': {
        uri: 'models/{model}/edit';
        methods: ['GET', 'HEAD'];
    };
    'models.update': {
        uri: 'models/{model}';
        methods: ['PUT', 'PATCH'];
    };
    'models.destroy': {
        uri: 'models/{model}';
        methods: ['DELETE'];
    };
    'models.media.index': {
        uri: 'models/{model}/media';
        methods: ['GET', 'HEAD'];
    };
    'models.media.store': {
        uri: 'models/{model}/media';
        methods: ['POST'];
    };
    'models.history.index': {
        uri: 'models/{model}/history';
        methods: ['GET', 'HEAD'];
    };
    'studios.index': {
        uri: 'studios';
        methods: ['GET', 'HEAD'];
    };
    'studios.create': {
        uri: 'studios/create';
        methods: ['GET', 'HEAD'];
    };
    'studios.store': {
        uri: 'studios';
        methods: ['POST'];
    };
    'studios.show': {
        uri: 'studios/{studio}';
        methods: ['GET', 'HEAD'];
    };
    'studios.edit': {
        uri: 'studios/{studio}/edit';
        methods: ['GET', 'HEAD'];
    };
    'studios.update': {
        uri: 'studios/{studio}';
        methods: ['PUT', 'PATCH'];
    };
    'studios.destroy': {
        uri: 'studios/{studio}';
        methods: ['DELETE'];
    };
    'profile.show': {
        uri: 'user/{user}';
        methods: ['GET', 'HEAD'];
    };
    'bible.general': {
        uri: 'bible/general';
        methods: ['GET', 'HEAD'];
    };
};

declare global {
    const ziggy: Routes;
    function route(): Router;
    function route(
        name: keyof Routes,
        params?: RouteParamsWithQueryOverload | RouteParam,
        absolute?: boolean,
        config?: Config,
    ): string;
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route(
            name: keyof Routes,
            params?: RouteParamsWithQueryOverload | RouteParam,
            absolute?: boolean,
            config?: Config,
        ): string;
    }
}

export { Routes };
