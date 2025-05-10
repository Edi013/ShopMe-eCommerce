<?php
namespace App\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RouteLoadSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if(LoginFormAuthenticator::isUserAuthenticated($request)){
            echo('user is authenticated');
            return;
        }

        $route = $request->get('_route');
        if($route === 'login'){
            return;
        }
        $redirectUrl = '/login';
        echo('user is not authenticated');
        $response = new RedirectResponse($redirectUrl);
        $event->setResponse($response);
    }


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
