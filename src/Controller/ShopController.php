<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'shop')]
    public function index(Request $request): Response
    {
        $shopItems = [
            ['name' => 'Item 1', 'price' => 10.00],
            ['name' => 'Item 2', 'price' => 20.00],
            ['name' => 'Item 3', 'price' => 30.00],
            ['name' => 'Item 4', 'price' => 40.00]
        ];

        $searchTerm = $request->query->get('q', '');


        if ($searchTerm) {
            $shopItems = array_filter($shopItems, function ($item) use ($searchTerm) {
                return stripos($item['name'], $searchTerm) !== false;
            });
        }

        return $this->render('shop/index.html.twig', [
            'shopItems' => $shopItems,
            'searchTerm' => $searchTerm
        ]);
    }
}
