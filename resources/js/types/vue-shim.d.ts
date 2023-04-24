declare module '*.vue' {
    import type { DefineComponent } from 'vue';
    const Component: DefineComponent;
    export default Component;
}

declare module '*.md' {
    import type { ComponentOptions } from 'vue';
    const Component: ComponentOptions;
    export default Component;
}
