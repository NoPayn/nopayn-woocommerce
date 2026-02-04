<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_Ginger_Swish_Block extends WC_Ginger_Abstract_Block_Payment {

    /**
     * Get the payment method name.
     *
     * @return string
     */
    protected function get_payment_method_name() {
        return 'swish';
    }

    /**
     * Get the gateway class name.
     *
     * @return string
     */
    protected function get_gateway_class() {
        return 'WC_Ginger_Swish';
    }

    /**
     * Get the display label for the payment method.
     *
     * @return string
     */
    protected function get_payment_method_label() {
        return __('Swish', WC_Ginger_BankConfig::BANK_PREFIX);
    }
}
