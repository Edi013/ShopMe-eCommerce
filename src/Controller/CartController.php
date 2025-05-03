<?php
namespace App\Controller;

include_once "CartController.php";

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    //#[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        $cartItems = [
            ['name' => 'Item 1', 'price' => 10.00],
            ['name' => 'Item 2', 'price' => 20.00],
            ['name' => 'Item 3', 'price' => 30.00]
        ];

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }
}
