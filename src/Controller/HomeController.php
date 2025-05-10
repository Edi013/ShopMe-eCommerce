<?php
namespace App\Controller;

use App\Common\UserSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private \Symfony\Component\HttpFoundation\Session\SessionInterface $session;

    public function __construct(RequestStack $rs)
    {
        $this->session = $rs->getSession();
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $userId   = UserSession::getUserId($this->session);
        $username = UserSession::getUsername($this->session);

        return $this->render('home/index.html.twig', [
            'user_id'  => $userId,
            'username' => $username,
        ]);
    }
}
