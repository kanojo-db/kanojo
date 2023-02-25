import type { JestConfigWithTsJest } from 'ts-jest';
import { defaults as tsjPreset } from 'ts-jest/presets';

const jestConfig: JestConfigWithTsJest = {
    clearMocks: true,
    collectCoverage: true,
    coverageDirectory: '../../coverage',
    coverageProvider: 'v8',
    coverageReporters: ['text', 'clover'],
    preset: 'ts-jest',
    rootDir: 'resources/js',
    testEnvironment: 'jsdom',
    testEnvironmentOptions: {
        customExportConditions: ['node', 'node-addons'],
    },
    transform: {
        ...tsjPreset.transform,
        '^.+\\.vue$': '@vue/vue3-jest',
    },
};

export default jestConfig;
