import type { ThemeDefinition } from 'vuetify';

export const light: ThemeDefinition = {
    dark: false,
    colors: {
        background: '#fafaf9',
        surface: '#fafaf9',
        surfaceDark: '#f5f5f4',
        primary: '#6a306d',
        secondary: '#E75A7C',
        tertiary: '#15B097',
        success: '#4caf50',
        warning: '#ff9800',
        error: '#f44336',
        info: '#2196f3',
    },
};

export const dark: ThemeDefinition = {
    dark: true,
    colors: {
        background: '#1c1917',
        surface: '#292524',
        surfaceDark: '#1c1917',
        primary: '#E75A7C',
        secondary: '#6a306d',
        tertiary: '#15B097',
        success: '#4caf50',
        warning: '#ff9800',
        error: '#f44336',
        info: '#2196f3',
    },
};
