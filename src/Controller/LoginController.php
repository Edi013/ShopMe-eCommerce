<?php
namespace App\Controller;

use App\Common\UserSession;
use App\Controller\FormTypes\LoginFormType;
use App\UseCases\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, UserService $userService): Response
    {
        UserSession::logout($this->requestStack->getSession());       ;
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userService->findUserByUsername($data['username']);

            if (!$user) {
                $this->addFlash('error', 'Invalid username.');
            } elseif (!password_verify($data['password'], $user->getPassword())) {
                $this->addFlash('error', 'Incorrect password.');
            } else {
                $session = $this->requestStack->getSession();
                UserSession::setUserId($session, $user->getId());
                UserSession::setUsername($session, $user->getUsername());
                UserSession::setLastLoginDate($session);
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response{
        UserSession::logout($this->requestStack->getSession());
        return $this->redirectToRoute('login');
    }
}