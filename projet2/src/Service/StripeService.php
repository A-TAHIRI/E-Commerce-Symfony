<?php

namespace App\Service;

use App\Entity\Order;

use App\Entity\Product;

class StripeService
{
    private $privateKey;

    public function __construct()
    {
        if($_ENV['APP_ENV']  === 'dev') {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
    }

    /**
     * @param Product $product
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentIntent(Product $product)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $product->getPrice() ,
            'currency' => Order::DEVISE,
            'payment_method_types' => ['card']
        ]);
    }

     /**
      * Summary of paiement
      * @param mixed $amount
      * @param mixed $currency
      * @param mixed $description
      * @param array $stripeParameter
      * @return \Stripe\PaymentIntent|null
      */

    public function paiement(
        $amount,
        $currency,
        $description,
        array $stripeParameter
    )
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        if(isset($stripeParameter['stripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }

        if($stripeParameter['stripeIntentStatus'] ==='succeeded') {
            //TODO
        } else {
            $payment_intent->cancel();
        }

        return $payment_intent;
    }

    /**
     * @param array $stripeParameter
     * @param Product $product
     * @return \Stripe\PaymentIntent|null
     */
    public function stripe(array $stripeParameter, Product $product)
    {
        return $this->paiement(
            $product->getPrice() ,
            Order::DEVISE,
            $product->getName(),
            $stripeParameter
        );
    }
}