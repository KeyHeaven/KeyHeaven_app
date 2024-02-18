<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController  extends AbstractController
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;

    private $stripeService;
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/login', name: 'user_login', methods: ['POST'])]
    public function login(Request $request, JWTEncoderInterface $encoder): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Invalid email or password');
        }
        $token = $encoder->encode([
            'email' => $user->getEmail(),
            'username' => $user->getEmail(),
            'exp' => time() + 14000 // Expiration en une heure
        ]);

        return $this->json(['token' => $token, 'message' => 'User login successful!']);
    }

    #[Route('/api/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request, JWTEncoderInterface $encoder): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $email = $data['email'];
            $password = $data['password'];
            $user = new User();
            $user->setemail($email);
            $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
            $user->setRegistrationDate(new \DateTime());
            $user->setRoles(['ROLE_USER']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $token = $encoder->encode([
                'email' => $user->getEmail(),
                'exp' => time() + 14000 // Expiration en une heure
            ]);

            return $this->json(['token' => $token, 'message' => 'User created successful!']);
        } catch (\Exception $e) {
            if($e->getCode() === 1062){
                return $this->json(['message' => 'email already exists!'], 409);
            }
            return $this->json(['message' => $e->getMessage()], 409);
        }
    }
}
