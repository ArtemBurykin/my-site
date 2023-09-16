/**
 *  @jest-environment jsdom
 */
import {gaConversions} from './ga-conversions';

describe('gaConversions', () => {
    const gtagStub = jest.fn();
    beforeEach(() => {
        document.body.innerHTML = `
            <body>
            </body>
            `;

        window.gtag = gtagStub;

        gaConversions();
    });

    afterEach(() => {
        document.body.innerHTML = '';
    });

    test('should dispatch event using gtag', () => {
        const event = new CustomEvent(
            'analyticsEventOccurred',
            {detail: {name: 'test'}}
        );

        document.dispatchEvent(event);

        expect(gtagStub).toHaveBeenCalledWith('event', 'test', {'location': 'http://localhost/'});
    });
});
