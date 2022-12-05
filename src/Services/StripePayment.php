<?php

namespace App\Services;
use Stripe\Checkout\Session;
use Stripe\Stripe;
class StripePayment 
{
  public function Stripe()
  {
    # code...
      Stripe::setApiKey('sk_test_51M152rABbpLr9iO4o0rEP9lqqcmvnBW3CITbQZl75BsWqRfnZvE0CwjxcFrO5VJZfUsVauEyHiScfuTKC0eWT0lZ00ac3XiAln');

    header('Content-Type: application/json');

    $YOUR_DOMAIN = 'http://localhost:3000/';

    $checkout_session = Session::create([
      'customer_email' => 'customer@example.com',
      'submit_type' => 'donate',
      'billing_address_collection' => 'required',
      'shipping_address_collection' => [
        'allowed_countries' => ['US', 'ES'],
      ],
      'line_items' => [[
        # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        'price' => 'price_1M8q8rABbpLr9iO4jevoyAPk',
        'quantity' => 1,
      ]],
      'mode' => 'payment',
      'success_url' => $YOUR_DOMAIN . '?success=true',
      'cancel_url' => $YOUR_DOMAIN . '?canceled=true',
    ]);

    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);

      }
}
