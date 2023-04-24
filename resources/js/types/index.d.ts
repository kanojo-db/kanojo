import { User } from './models';

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    user: User | null;
    locale: string;
    ziggy: {
        url: string;
        port: number | null;
        defaults: Record<string, unknown>;
        routes: Record<string, unknown>;
    };
};
