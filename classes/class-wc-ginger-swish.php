<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Ginger_Swish extends WC_Ginger_Gateway
{
    public function __construct()
    {
        $this->id = WC_Ginger_BankConfig::BANK_PREFIX.'_swish';
        $this->icon = false;
        $this->has_fields = false;
        $this->method_title = __(WC_Ginger_BankConfig::BANK_LABEL, WC_Ginger_BankConfig::BANK_PREFIX);
        $this->method_description = __('Accept Swish Payments', WC_Ginger_BankConfig::BANK_PREFIX);

        parent::__construct();
    }
}
