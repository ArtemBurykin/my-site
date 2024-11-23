/**
 *  @jest-environment jsdom
 */
import {analyticsConversions} from './analytics-conversions';

describe('gaConversions', () => {
    const analyticsStub = jest.fn();
    beforeEach(() => {
        document.body.innerHTML = `
            <body>
            </body>
            `;

        window.ym = analyticsStub;

        analyticsConversions('345');
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

        expect(analyticsStub).toHaveBeenCalledWith('345', 'reachGoal', 'test');
    });
});
