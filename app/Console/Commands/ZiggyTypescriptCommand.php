<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Tightenco\Ziggy\Ziggy;

class ZiggyTypescriptCommand extends Command
{
    protected $signature = 'ziggy:typescript';

    protected $description = 'Generate TypeScript definitions for ziggy';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle(): void
    {
        $path = './resources/js/types/ziggy-shim.d.ts';
        $generatedRoutes = $this->generate();

        $this->makeDirectory($path);
        $this->files->put(base_path($path), $generatedRoutes);

        $this->info('File generated!');
    }

    private function generate(): string
    {
        $ziggy = (new Ziggy(false, null));
        $routes = collect($ziggy->toArray()['routes'])
            ->map(function ($route, $key) {
                $methods = json_encode($route['methods'] ?? []);

                // These are needed for Prettier compliance
                $methods = str_replace('"', "'", $methods);
                $methods = str_replace(',', ', ', $methods);
                $key = str_contains($key, '.') ? "'{$key}'" : $key;

                return <<<TYPESCRIPT
                    {$key}: {
                        uri: '{$route['uri']}';
                        methods: {$methods};
                    };
                TYPESCRIPT;

            })
            ->join("\n");

        return <<<TYPESCRIPT
            import {
                Config,
                RouteParam,
                RouteParamsWithQueryOverload,
                Router,
            } from 'ziggy-js';

            type Routes = {
                [key: string]: { uri: string; methods: string[] };
            {$routes}
            };
            
            declare global {
                type Ziggy = Routes;
                function route(): Router;
                function route(
                    name: keyof Ziggy,
                    params?: RouteParamsWithQueryOverload | RouteParam,
                    absolute?: boolean,
                    config?: Config,
                ): string;
            }

            declare module '@vue/runtime-core' {
                interface ComponentCustomProperties {
                    route(
                        name: keyof Ziggy,
                        params?: RouteParamsWithQueryOverload | RouteParam,
                        absolute?: boolean,
                        config?: Config,
                    ): string;
                }
            }

            export { Routes };

            TYPESCRIPT;
    }

    protected function makeDirectory(string $path): string
    {
        if (! $this->files->isDirectory(dirname(base_path($path)))) {
            $this->files->makeDirectory(dirname(base_path($path)), 0755, true, true);
        }

        return $path;
    }
}
