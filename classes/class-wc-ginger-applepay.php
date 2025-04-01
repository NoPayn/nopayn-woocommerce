<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Ginger_Applepay extends WC_Ginger_Gateway
{
    public function __construct()
    {
        $this->id = WC_Ginger_BankConfig::BANK_PREFIX.'_apple-pay';
        $this->icon = false;
        $this->has_fields = false;
        $this->method_title = __(WC_Ginger_BankConfig::BANK_LABEL, WC_Ginger_BankConfig::BANK_PREFIX);
        $this->method_description = __('Accept Apple Pay Payments', WC_Ginger_BankConfig::BANK_PREFIX);

        parent::__construct();
    }
}
