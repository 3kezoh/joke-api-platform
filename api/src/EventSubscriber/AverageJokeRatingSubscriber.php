<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Joke;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AverageJokeRatingSubscriber implements EventSubscriberInterface
{
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::VIEW => ["serializeAverageJokeRating", EventPriorities::PRE_SERIALIZE],
    ];
  }

  public function serializeAverageJokeRating(ViewEvent $event): void
  {
    $joke = $event->getControllerResult();
    $method = $event->getRequest()->getMethod();

    $isJoke = $joke instanceof Joke;
    $isGetMethod = Request::METHOD_GET === $method;

    if (!$isJoke || !$isGetMethod) {
      return;
    }

    /** @var Joke $joke */
    $ratings = $joke->getRatings();

    if ($ratings->isEmpty()) {
      return;
    }

    $stars = $ratings->map(fn ($rating) => $rating->getStar())->toArray();

    $average = array_sum($stars) / count($stars);

    $joke->setAverageRating($average);
  }
}
