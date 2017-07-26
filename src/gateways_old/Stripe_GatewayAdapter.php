<?php

namespace craft\commerce\gateways;

use Craft;
use craft\commerce\gateway\models\StripePaymentForm;

class Stripe_GatewayAdapter extends CreditCardGatewayAdapter
{

    /**
     * @var string
     */
    public $publishableKey;

    public function handle()
    {
        return 'Stripe';
    }

    public function getPaymentFormModel()
    {
        return new StripePaymentForm();
    }

    public function cpPaymentsEnabled()
    {
        return true;
    }

    /*public function attributes() {
        $params = $this->getGateway()->getParameters();
        return $attr;
    }*/

    public function getPaymentFormHtml(array $params)
    {
        $defaults = [
            'paymentMethod' => $this->getPaymentMethod(),
            'paymentForm' => $this->getPaymentMethod()->getPaymentFormModel(),
            'adapter' => $this
        ];

        $params = array_merge($defaults, $params);

        Craft::$app->getView()->includeJsFile('https://js.stripe.com/v2/');
        Craft::$app->getView()->includeJsResource('lib/jquery.payment.js');

        return Craft::$app->getView()->render('commerce/_gateways/_paymentforms/stripe', $params);
    }

    public function defineAttributes()
    {
        // In addition to the standard gateway config, here is some custom config that is useful.
        $attr = parent::defineAttributes();
        $attr['publishableKey'] = [AttributeType::String];
        $attr['publishableKey']['label'] = $this->generateAttributeLabel('publishableKey');

        return $attr;
    }

}