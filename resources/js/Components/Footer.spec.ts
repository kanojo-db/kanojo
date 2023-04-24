import { renderToString, shallowMount } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';

import Footer from '@/Components/Footer.vue';

expect.extend(toHaveNoViolations);

describe('BtnDropdown', () => {
    it('renders properly in SSR mode', async () => {
        const contents = await renderToString(Footer);

        expect(contents).toMatchSnapshot();
    });

    it('has no a11y violations', async () => {
        const wrapper = shallowMount(Footer);

        expect(await axe(wrapper.html())).toHaveNoViolations();
    });
});
