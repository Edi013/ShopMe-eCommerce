<?php
namespace Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'shop')]
    public function index(Request $request): Response
    {
        // Example shop items (in a real app, this could be fetched from a database)
        $shopItems = [
            ['name' => 'Item 1', 'price' => 10.00],
            ['name' => 'Item 2', 'price' => 20.00],
            ['name' => 'Item 3', 'price' => 30.00],
            ['name' => 'Item 4', 'price' => 40.00]
        ];

        $searchTerm = $request->query->get('q', '');

        // Filter shop items by the search term
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
