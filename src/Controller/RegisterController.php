<?php
namespace App\Controller;

use App\Controller\Types\RegisterFormType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserService $userService): Response
    {
        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $existingUser = $userService->findUserByUsername($data['username']);

            if ($existingUser) {
                $this->addFlash('error', 'Username already taken.');
            } else {
                $userService->createUser($data['username'], $data['password']);
                $this->addFlash('success', 'Registration successful!');
                return $this->redirectToRoute('login');
            }
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
