(function() {
    'use strict';

    var registerPaymentMethod = window.wc.wcBlocksRegistry.registerPaymentMethod;
    var createElement = window.wp.element.createElement;
    var decodeEntities = window.wp.htmlEntities.decodeEntities;
    var getSetting = window.wc.wcSettings.getSetting;

    // Get config from PHP
    var config = window.gingerBlocksConfig || {};
    var bankPrefix = config.bankPrefix || 'ginger';
    var paymentMethodsList = config.paymentMethods || [];


    /**
     * Create content component (displayed when payment method is selected)
     *
     * @param {Object} settings Payment method settings
     * @returns {Function} React component
     */
    function createContentComponent(settings) {
        var description = settings.description ? decodeEntities(settings.description) : '';

        return function() {
            if (!description) {
                return createElement('div');
            }

            return createElement(
                'div',
                { className: 'wc-block-components-payment-method-description' },
                description
            );
        };
    }

    /**
     * Create label component
     *
     * @param {Object} settings Payment method settings
     * @returns {Function} React component
     */
    function createLabelComponent(settings) {
        var title = settings.title ? decodeEntities(settings.title) : settings.name;
        var icon = settings.icon;

        return function() {
            if (icon) {
                return createElement(
                    'span',
                    { className: 'wc-block-components-payment-method-label' },
                    createElement('img', {
                        src: icon,
                        alt: title,
                        className: 'wc-block-components-payment-method-icon',
                        style: { maxHeight: '24px', marginRight: '8px' }
                    }),
                    title
                );
            }

            return createElement(
                'span',
                { className: 'wc-block-components-payment-method-label' },
                title
            );
        };
    }

    /**
     * Check if Apple Pay is available on the device
     *
     * @returns {boolean}
     */
    function isApplePayAvailable() {
        return typeof window.ApplePaySession !== 'undefined' && window.ApplePaySession;
    }

    /**
     * Register a payment method with WooCommerce Blocks
     *
     * @param {string} methodName Payment method name (e.g., 'credit-card')
     */
    function registerGingerPaymentMethod(methodName) {
        var fullName = bankPrefix + '_' + methodName;
        var settingsKey = fullName + '_data';
        var settings = getSetting(settingsKey, null);

        if (!settings) {
            return;
        }

        var name = settings.name || fullName;
        var title = settings.title || methodName;
        var supports = settings.supports || ['products'];

        // Create canMakePayment function
        var canMakePayment = function() {
            var supportedCurrencies = settings.supportedCurrencies || [];
            var currentCurrency = settings.currentCurrency || '';

            // Check currency support before showing payment method
            if (supportedCurrencies.length > 0 && currentCurrency) {
                if (supportedCurrencies.indexOf(currentCurrency) === -1) {
                    return false;
                }
            }

            // Special handling for Apple Pay - only show on compatible devices
            if (methodName === 'apple-pay') {
                return isApplePayAvailable();
            }
            return true;
        };

        var ContentComponent = createContentComponent(settings);
        var LabelComponent = createLabelComponent(settings);

        var paymentMethodConfig = {
            name: name,
            label: createElement(LabelComponent),
            content: createElement(ContentComponent),
            edit: createElement(ContentComponent),
            canMakePayment: canMakePayment,
            ariaLabel: decodeEntities(title),
            supports: {
                features: supports,
            },
        };

        registerPaymentMethod(paymentMethodConfig);
    }

    /**
     * Initialize all payment methods
     */
    function init() {
        paymentMethodsList.forEach(function(methodName) {
            registerGingerPaymentMethod(methodName);
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
