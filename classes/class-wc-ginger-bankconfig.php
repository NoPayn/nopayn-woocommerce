<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_Ginger_BankConfig
{

    /**
     * GINGER_ENDPOINT used for create Ginger client
     */
    const GINGER_BANK_ENDPOINT = 'https://api.nopayn.co.uk';

    /**
     * BANK_PREFIX and BANK_LABEL used to provide GPE solution
     */
    const BANK_PREFIX = "nopayn";
    const BANK_LABEL = "NoPayn";
    const PLUGIN_NAME = "nopayn-woocommerce";

    /**
     * Xpate supported payment methods
     */
    public static $BANK_PAYMENT_METHODS = [
        'nopayn_credit-card'
    ];

    /**
     * Xpate payment methods classnames
     */
    public static $WC_BANK_PAYMENT_METHODS = [
        'WC_Ginger_Callback',
        'WC_Ginger_Creditcard'
    ];
}
