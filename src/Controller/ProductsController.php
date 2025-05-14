<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ProductsController extends AbstractController{
    #[Route('/products/add', name: 'add-products')]
    public function add(){}

    #[Route('/products/update', name: 'update-product')]
    public function update(){}

    #[Route('/products', name: 'products')]
    public function index(){}

}