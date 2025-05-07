<?php
namespace App\Controller;

use App\Controller\Types\LoginFormType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, UserService $userService): Response
    {
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userService->findUserByUsername($data['username']);

            if (!$user) {
                $this->addFlash('error', 'Invalid username.');
            } elseif ($user->getPassword() !== $data['password']) {
                $this->addFlash('error', 'Incorrect password.');
            } else {
                $this->addFlash('success', 'Login successful!');
            }
        }

        return $this->render('auth/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
