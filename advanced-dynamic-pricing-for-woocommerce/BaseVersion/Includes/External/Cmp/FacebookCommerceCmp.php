<?php

namespace ADP\BaseVersion\Includes\External\Cmp;

use ADP\BaseVersion\Includes\Context;
use ADP\BaseVersion\Includes\Rule\Interfaces\Rule;
use ADP\BaseVersion\Includes\Translators\RuleTranslator;

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class FacebookCommerceCmp
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct($context)
    {
        $this->context = $context;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return class_exists("WC_Facebook_Loader");
    }

    public function applyCompatibility()
    {
        if ( ! $this->isActive()) {
            return;
        }

        add_filter('wc_facebook_product_price', function ($price, $facebook_price, $product) {
            if ( ! $price) {
                return $price;
            }

            $discountPrice = adp_functions()->getDiscountedProductPrice($product, 1.0, true);
            if (empty($discountPrice)) {
                return $price;
            }

            return is_array($discountPrice) ? (int)(current($discountPrice) * 100) : (int)($discountPrice * 100);
        }, 10, 3);
    }
}
