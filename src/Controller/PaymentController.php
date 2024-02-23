<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GameRepository;
use App\Entity\Game;
use Stripe\PaymentIntent;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Purchasing;
use App\Entity\PurchaseDetail;
use App\Entity\ActivationCode;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PaymentController extends AbstractController
{
    private $stripeSecretKey;
    private $gameRepository; // Ajoutez cette ligne
    private $entityManager;
    private $tokenStorage;
    public function __construct(string $stripeSecretKey, GameRepository $gameRepository, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage) // Injectez GameRepository
    {
        $this->stripeSecretKey = $stripeSecretKey;
        $this->gameRepository = $gameRepository; // Initialisez GameRepository
        Stripe::setApiKey($this->stripeSecretKey);
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/api/create-payment-intent', name: 'app_create_payment_intent', methods: ['POST'])]
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $gameIds = $data['items'];
        $metadata = [];
        $totalAmount = 0;

        foreach ($gameIds as $index => $gameId) {
            $game = $this->gameRepository->find($gameId['id']);
            if (!$game) {
                return $this->json(['error' => "Game with id $gameId not found"], 400);
            }

            // Convertissez le prix en centimes pour Stripe
            $totalAmount += $game->getPrice() * 100;

            // Ajoutez les informations du jeu aux métadonnées
            $metadata["jeu_${index}_id"] = $game->getId();
            $metadata["jeu_${index}_titre"] = $game->getTitle();
            $metadata["jeu_${index}_prix"] = $game->getPrice(); 
        }

        try {
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $totalAmount,
                'currency' => 'eur',
                'customer' => $user->getStripeId(),
                'payment_method_types' => ['card'],
                'metadata' => $metadata,
            ]);

            return $this->json(['clientSecret' => $intent->client_secret]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/payment-success', name: 'app_payment_success', methods: ['POST'])]
    public function handlePaymentSuccess(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $paymentIntentId = $data['paymentIntentId'];
        $purchaseId = $data['purchaseId'];
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {

                $purchase = $this->entityManager->getRepository(Purchasing::class)->find($purchaseId);

                if (!$purchase) {
                    throw new NotFoundHttpException("Purchase not found");
                }

                $purchase->setStatus('PAID');
                 $purchaseDetails = $this->entityManager->getRepository(PurchaseDetail::class)->findByPurchaseId($purchaseId);
                foreach ($purchaseDetails as $purchaseDetail) {
                    $game = $purchaseDetail->getGame();
                    $activationCode = $this->entityManager->getRepository(ActivationCode::class)->findOneBy(['game' => $game, 'isAvailable' => true]);
                    $purchaseDetail->setActivationCode($activationCode);
                    $activationCode->setIsAvailable(false);
                    $this->entityManager->persist($game);
                }

                $this->entityManager->flush(); 

                return $this->json(['success' => true, 'message' => 'Payment processed successfully.']);
            } else {
                return $this->json(['error' => 'PaymentIntent not succeeded.'], 400);
            }
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    // create stripe customer
    #[Route('/api/create-stripe-customer', name: 'app_create_stripe_customer', methods: ['POST'])]
    public function createStripeCustomer(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        try {
            $customer = Customer::create([
                'email' => $user->getEmail(),
            ]);

            $user->setStripeId($customer->id);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->json(['success' => true, 'message' => 'Stripe customer created successfully.', 'stripeId' => $customer->id]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}