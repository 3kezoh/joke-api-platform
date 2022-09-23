<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PasswordSubscriber implements EventSubscriberInterface
{

  public function __construct(private UserPasswordHasherInterface $passwordHasher)
  {
  }

  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::VIEW => ["hashPassword", EventPriorities::PRE_WRITE],
    ];
  }

  public function hashPassword(ViewEvent $event): void
  {
    $user = $event->getControllerResult();
    $method = $event->getRequest()->getMethod();

    $isUser = $user instanceof User;
    $isPostMethod = Request::METHOD_POST === $method;
    $isPatchMethod = Request::METHOD_PATCH === $method;
    $isAllowedMethod = $isPostMethod || $isPatchMethod;

    if (!$isUser || !$isAllowedMethod) {
      return;
    }

    /** @var User $user */
    $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());

    $user->setPassword($hashedPassword);

    return;
  }
}
