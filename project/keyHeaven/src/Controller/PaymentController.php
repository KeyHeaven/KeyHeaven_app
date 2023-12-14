<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class PaymentController extends AbstractController
{
    private $stripeSecretKey;

    public function __construct(string $stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
        Stripe::setApiKey($this->stripeSecretKey);
    }

    // create customer
    #[Route('/create-customer', name: 'app_create_customer')]
    public function createCustomer(): JsonResponse
    {
           try {
            $customer = Customer::create([
                'email' => 'me+khv@matthieu.pw', 
            ]);

            return $this->json(['customerId' => $customer->id]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    // create payment intent POST
    #[Route('/create-payment-intent', name: 'app_create_payment_intent', methods: ['POST'])]
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // $items = $data['items'];
        // $metadata = [];
        // foreach ($items as $index => $item) {
        //     $metadata["item_${index}_id"] = $item['productId'];
        //     $metadata["item_${index}_name"] = $item['productName'];
        //     $metadata["item_${index}_price"] = 1000;
        // }
        try {
            $intent = \Stripe\PaymentIntent::create([
                'amount' => 1099,
                'currency' => 'eur',
                'customer' => 'cus_PAran2pH3B5egS',
                'payment_method_types' => ['card'],
                // 'description' => 'Multiple product purchase',
                // 'metadata' => $metadata,
            ]);

            return $this->json(['clientSecret' => $intent->client_secret]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
    
}
