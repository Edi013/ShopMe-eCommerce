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

        // Seed only in dev/test
        UserSession::seedIdAndUsernameDevelopmentOnly($this->session);
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $userId   = UserSession::getUserId($this->session);
        $username = UserSession::getUsername($this->session);
        dump('test dump');
        dump($this->session->get('user_id'));
        dump($this->session->get('username'));

        return $this->render('home/index.html.twig', [
            'user_id'  => $userId,
            'username' => $username,
        ]);
    }
}
