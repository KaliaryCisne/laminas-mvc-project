<?php

namespace Application\Listener;

use Auth\Service\AuthService;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;

class LayoutListener extends AbstractListenerAggregate
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, [$this, 'injectVariables'], $priority);
    }

    public function injectVariables(EventInterface $event)
    {
        $viewModel = $event->getViewModel();
        $isLogged = $this->authService->hasIdentity();
        $viewModel->setVariable('isLogged', $isLogged);
    }
}