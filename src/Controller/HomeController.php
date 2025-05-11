<?php
namespace App\Controller;

use App\Common\UserSession;
use App\UseCases\Interfaces\Services\ISaleService;
use App\UseCases\Interfaces\Services\IUserService;
use App\UseCases\Services\SaleService;
use App\UseCases\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private SessionInterface $session;
    private ISaleService $saleService;
    private IUserService $userService;

    public function __construct(RequestStack $rs, ISaleService $saleService, IUserService $userService)
    {
        $this->session = $rs->getSession();
        $this->saleService = $saleService;
        $this->userService = $userService;
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $referer = $request->headers->get('referer');
        if ($referer && str_contains($referer, '/login')) {
            $this->addFlash('success', 'Login successful!');
        }

        $userId   = UserSession::getUserId($this->session);
        $username = UserSession::getUsername($this->session);
        $user = $this->userService->findByUserId($userId);
        $sales = $this->saleService->getSalesAndProductsByUser($user);

        return $this->render('home/index.html.twig', [
            'user_id'  => $userId,
            'username' => $username,
            'sales' => $sales,
        ]);
    }
}
