<?php

namespace Drupal\event_hook_api;

use Drupal\event_hook_api\Extension\ModuleHandler;
use Symfony\Component\DependencyInjection\Parameter;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;
use Symfony\Component\DependencyInjection\Reference;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

/**
 * Service container provider for this module.
 */
class EventHookApiServiceProvider implements ServiceModifierInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $container->register('module_handler', ModuleHandler::class)
      ->addArgument(new Reference('app.root'))
      ->addArgument(new Parameter('container.modules'))
      ->addArgument(new Reference('cache.bootstrap'))
      ->addArgument(new Reference('event_dispatcher'));
  }

}
