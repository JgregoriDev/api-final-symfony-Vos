<?php

namespace App\Controller;

use App\Entity\Genere;
use App\Entity\Usuari;
use App\Form\VideojocType;
use App\Repository\VideojocRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UsuariType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Videojoc;
use App\Entity\Comprar;
use App\Form\BanejarType;
use App\Form\CompraType;
use App\Form\GeneresType;
use App\Form\UsuariLoginType;
use App\Repository\GenereRepository;
use App\Repository\UsuariRepository;
use Doctrine\ORM\Mapping\Entity;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Services\StripePayment;

/**
 * @Route("/api")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/v1/usuaris", name="api_llistar_usuari")
     * @Rest\View(serializerGroups={"usuari"}, serializerEnableMaxDepthChecks=true)
     */
    public function llistar(UsuariRepository $ur)
    {
        return $ur->findAll();
    }

    /**
     * @Rest\Post(path="/login", name="api_login_usuari")
     * @Rest\View(serializerGroups={"usuari"}, serializerEnableMaxDepthChecks=true)
     */
    public function login(
        UsuariRepository $ur,
        Request $request,
        JWTTokenManagerInterface $JWTManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $usuari = new Usuari();
        $form = $this->createForm(UsuariLoginType::class, $usuari);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $usuari->getPassword();
            // $usuari->setPassword($hashedPassword);
            $user = $ur->findOneBy(["email" => $usuari->getEmail()]);
            if ($user->isBan()) {
                return $this->view(["Title" => "Usuari banejat", "usuari" => "Usuari banejat"], Response::HTTP_FORBIDDEN);
            }
            if ($passwordHasher->isPasswordValid($user, $password)) {
                return ($this->view(["Title" => "Login correcte", "id" => $user->getId(), 'email' => $user->getEmail(), 'token' => $JWTManager->create($user)], 200));
            }
            return ($this->view(["Title" => "Login incocorrecte", 'email' => $user->getEmail()], Response::HTTP_NOT_FOUND));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Post(path="/v1/usuari/cambiar/contrasenya", name="api_insertar_genere")
     * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
     */
    public function insertarGenere(EntityManagerInterface $emi, Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $genere = new Usuari();
        $form = $this->createForm(GeneresType::class, $genere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emi->persist($genere);
            $emi->flush();
            return ($this->view($genere, Response::HTTP_OK));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put(path="/v1/usuari/{id}/banejar", name="api_ban_usuari")
     * @Rest\View(serializerGroups={"usuari"}, serializerEnableMaxDepthChecks=true)
     */
    public function banejarUsuari(int $id, EntityManagerInterface $emi, Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $usuari = $emi->getRepository(Usuari::class)->find($id);
        if (!$usuari) {
            $this->createNotFoundException("Usuari no trobat");
        }
        // $form = $this->createForm(BanejarType::class, $usuari);
        // $form->handleRequest($request);


        $usuari->setBan(!$usuari->isBan());
        $emi->flush();
        if ($usuari->isBan()) {
            return ($this->view(["Title" => "Usuari banejat", "Resultat" => $usuari], Response::HTTP_OK));
        } else {
            return ($this->view(["Title" => "Usuari desbanejat", "Resultat" => $usuari], Response::HTTP_OK));
        }
    }

    /**
     * @Rest\Post(path="/v1/registrar", name="api_usuari_nou")
     * @Rest\View(serializerGroups={"usuari"}, serializerEnableMaxDepthChecks=true)
     */
    public function newUser(EntityManagerInterface $emi, Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $usuari = new Usuari();

        $form = $this->createForm(UsuariType::class, $usuari);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pass = $passwordHasher->hashPassword($usuari, $usuari->getPassword());
            $usuari
                ->setBan(false)
                ->setRoles(["ROLE_USER"])
                ->setPassword($pass);
            $emi->persist($usuari);
            $emi->flush();
            return ($this->view(["Title" => "usuari registrat", "Usuari" => $usuari], Response::HTTP_OK));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }


    /**
     * @Rest\Post(path="/v1/pago", name="api_usuari_pagament")
     * @Rest\View(serializerGroups={"usuari"}, serializerEnableMaxDepthChecks=true)
     */
    public function nou(EntityManagerInterface $emi,$stripeSK, Request $request,UserPasswordHasherInterface $passwordHasher)
    {
    # code...
    
    // header('Content-Type: application/json');
    $form = $this->createForm(CompraType::class, null);
    $form->handleRequest($request);
    
    //    return $this->view([$_POST])
    $arrayProductes=$_POST['arrayProductes']??[];
    $productes=$_POST['productes']??'';
    $preu=(int)$_POST['preu']??'';
    if(is_int($preu)){
            Stripe::setApiKey($stripeSK);
            $YOUR_DOMAIN = 'https://11josep.daw.iesevalorpego.es/';
            $checkout_session = Session::create([
                'customer_email' => 'customer@example.com',
                'submit_type' => 'pay',
                'billing_address_collection' => 'auto',
                'shipping_address_collection' => [
                    'allowed_countries' => ['US', 'ES'],
                ],
                'line_items' => [[
                    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' => $productes,
                        ],
                        'unit_amount'  => $preu*100,
                    ],
                    'quantity'   => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . 'FAQ',
                'cancel_url' => $YOUR_DOMAIN . 'denegat',
            ]);
            if($checkout_session->url===$YOUR_DOMAIN . 'pago-realitzat'){
                $compra=new Comprar();
                $usuari = $emi->getRepository(Usuari::class)->findBy(['email'=>"admin"]);
                $result = new \DateTime(); 
                $compra->setPreu($preu)
                    // ->setProductes($arrayProductes)
                    // ->setUsuari($usuari)
                    ->setEstatPagament(true)
                    ->setDataCompra(new \DateTime());
                $emi->persist($compra);
                $emi->flush();
            }else{ $compra=new Comprar();
                $usuari = $emi->getRepository(Usuari::class)->findBy(['email'=>"admin"]);
                $result = new \DateTime(); 
                $compra->setPreu($preu)
                    // ->setProductes($arrayProductes)
                    ->setEstatPagament(false)
                    // ->setUsuari($usuari)
                    ->setDataCompra(new \DateTime());
                $emi->persist($compra);
                $emi->flush();
            }
            return $this->redirect($checkout_session->url, 303);
        }

        return $this->view([$_POST]);
    
    }
	
function calculateOrderAmount(array $items): int {
    return $items[0]->preu*100;
}
	
    /**
     * @Rest\Post(path="/v1/payment", name="api_user_pay")
     * @Rest\View(serializerGroups={"usuari"}, serializerEnableMaxDepthChecks=true)
     */
    public function PaymentIntentNou(EntityManagerInterface $emi, $stripeSK, Request $request)
    {
        # code...


        //    return $this->view([$_POST])
        // $arrayProductes=$_POST['arrayProductes']??[];
        // $productes=$_POST['productes']??'';
        // $preu=(int)$_POST['preu']??'';
        Stripe::setApiKey($stripeSK);
		//$jsonStr = file_get_contents('php://input');
    	//$jsonObj = json_decode($jsonStr);
		$content = $request->getContent();
    	$jsonObj = json_decode($content);
        $paymentIntent = \Stripe\PaymentIntent::create([
               'amount' => $this->calculateOrderAmount($jsonObj->items),
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
        ];
        echo json_encode($output);
    }
}
