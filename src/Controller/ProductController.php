<?php
namespace App\Controller;

use App\Common\UserSession;
use App\Controller\FormTypes\ProductFormType;
use App\Entity\Product;
use App\UseCases\Services\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController{
    private ProductService $productService;
    private EntityManagerInterface $entityManager;


    public function __construct(ProductService $productService, EntityManagerInterface $entityManager)
    {
        $this->productService = $productService;
        $this->entityManager = $entityManager;
    }
    #[Route('/products/{id}/delete', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Product deleted successfully.');
            return $this->redirectToRoute('products');

        }
        $this->addFlash('error', 'Product was not deleted successfully.');
        return $this->redirectToRoute('products');
    }

    #[Route('/products/{id}/quantity', name: 'product_update_quantity', methods: ['POST'])]
    public function updateQuantity(Request $request, Product $product): RedirectResponse
    {
        $quantity = (int) $request->request->get('quantity');
        if ($quantity >= 0) {
            $product->setStock($quantity);
            $this->entityManager->flush();
            $this->addFlash('success', 'Quantity updated.');
        } else {
            $this->addFlash('error', 'Invalid quantity.');
        }

        return $this->redirectToRoute('products');
    }
    #[Route('/products', name: 'products')]
    public function index(Request $request): Response
    {
        $data = $this->productService->getProducts($request);

        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Product added successfully!');
            return $this->redirectToRoute('products');
        }
        return $this->render('products/index.html.twig', [
            'form' => $form->createView(),
            'searchTerm' => $data['searchTerm'],
            'products' => $data['products'],
        ]);
    }
}