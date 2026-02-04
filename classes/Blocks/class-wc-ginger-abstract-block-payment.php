<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

if (!defined('ABSPATH')) {
    exit;
}

abstract class WC_Ginger_Abstract_Block_Payment extends AbstractPaymentMethodType {

    /**
     * Payment method name/id/slug.
     *
     * @var string
     */
    protected $name;

    /**
     * The gateway instance.
     *
     * @var WC_Ginger_Gateway
     */
    protected $gateway;

    /**
     * Settings from the gateway.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Get the payment method name (e.g., 'credit-card', 'apple-pay').
     *
     * @return string
     */
    abstract protected function get_payment_method_name();

    /**
     * Get the gateway class name.
     *
     * @return string
     */
    abstract protected function get_gateway_class();

    /**
     * Get the display label for the payment method.
     *
     * @return string
     */
    abstract protected function get_payment_method_label();

    /**
     * Constructor - sets the payment method name.
     */
    public function __construct() {
        $this->name = WC_Ginger_BankConfig::BANK_PREFIX . '_' . $this->get_payment_method_name();
    }

    /**
     * Initializes the payment method type.
     */
    public function initialize() {
        $this->settings = get_option('woocommerce_' . $this->name . '_settings', []);

        $gateway_class = $this->get_gateway_class();
        if (class_exists($gateway_class)) {
            $this->gateway = new $gateway_class();
        }
    }

    /**
     * Returns if this payment method should be active.
     *
     * @return boolean
     */
    public function is_active() {
        $enabled = $this->get_setting('enabled');
        return !empty($enabled) && $enabled === 'yes';
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles() {
        $script_handle = 'wc-ginger-blocks-integration';

        if (!wp_script_is($script_handle, 'registered')) {
            wp_register_script(
                $script_handle,
                plugins_url('assets/js/blocks/ginger-blocks.js', dirname(dirname(__FILE__))),
                [
                    'wc-blocks-registry',
                    'wc-settings',
                    'wp-element',
                    'wp-html-entities',
                    'wp-i18n',
                ],
                GINGER_PLUGIN_VERSION,
                true
            );

            wp_localize_script(
                $script_handle,
                'gingerBlocksConfig',
                [
                    'bankPrefix' => WC_Ginger_BankConfig::BANK_PREFIX,
                    'paymentMethods' => [
                        'credit-card',
                        'apple-pay',
                        'google-pay',
                        'mobilepay',
                        'swish',
                    ],
                ]
            );
        }

        return [$script_handle];
    }

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */
    public function get_payment_method_data() {
        $title = $this->get_setting('title');
        $description = $this->get_setting('description');

        return [
            'name' => $this->name,
            'title' => !empty($title) ? $title : $this->get_payment_method_label(),
            'description' => !empty($description) ? $description : '',
            'supports' => $this->get_supported_features(),
            'icon' => $this->get_payment_method_icon(),
            'supportedCurrencies' => $this->get_supported_currencies(),
            'currentCurrency' => function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : '',
        ];
    }

    /**
     * Get the supported currencies for this payment method using the gateway's currency logic.
     * This ensures the cached currency list is refreshed from the API when expired.
     *
     * @return array
     */
    protected function get_supported_currencies() {
        if (!$this->gateway) {
            return ["EUR"];
        }

        $allowed_currencies = $this->gateway->ginger_get_allowed_currencies();

        if (!$allowed_currencies || empty($allowed_currencies['payment_methods'])) {
            return ["EUR"];
        }

        $payment_method = $this->get_payment_method_name();
        $payment_methods = $allowed_currencies['payment_methods'];

        if (isset($payment_methods[$payment_method]['currencies'])) {
            return $payment_methods[$payment_method]['currencies'];
        }

        return ["EUR"];
    }

    /**
     * Get the icon URL for this payment method.
     *
     * @return string
     */
    protected function get_payment_method_icon() {
        $icon_path = 'images/' . $this->get_payment_method_name() . '.png';
        $plugin_path = dirname(dirname(dirname(__FILE__)));

        if (file_exists($plugin_path . '/' . $icon_path)) {
            return plugins_url($icon_path, dirname(dirname(__FILE__)));
        }

        return '';
    }

    /**
     * Returns an array of supported features.
     *
     * @return array
     */
    public function get_supported_features() {
        $features = ['products'];

        if ($this->gateway && $this->gateway->supports('refunds')) {
            $features[] = 'refunds';
        }

        return $features;
    }
}
