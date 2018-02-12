<?php

namespace Drupal\event_hook_api\Extension;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\event_hook_api\Event\DrupalHookEvent;
use Drupal\event_hook_api\HookSubscriberDiscovery;
use Drupal\event_hook_api\Event\DrupalHookAlterEvent;
use Drupal\Core\Extension\ModuleHandler as ModuleHandlerBase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ModuleHandler extends ModuleHandlerBase {

  /**
   * Symfony event dispatcher.
   *
   * @var Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Constructor for EventDispatcherModuleHandler.
   *
   * @param string $root
   *   The app root.
   * @param array $moduleList
   *   An associative array whose keys are the names of installed modules and
   *   whose values are Extension class parameters. This is normally the
   *   %container.modules% parameter being set up by DrupalKernel.
   * @param Drupal\Core\Cache\CacheBackendInterface $cacheBackend
   *   Cache backend for storing module hook implementation information.
   * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   Symfony event dispatcher.
   */
  public function __construct($root, array $moduleList, CacheBackendInterface $cacheBackend, EventDispatcherInterface $eventDispatcher) {
    $this->eventDispatcher = $eventDispatcher;
    parent::__construct($root, $moduleList, $cacheBackend);
  }

  /**
   * {@inheritdoc}
   */
  public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL) {
    foreach ((array) $type as $hook) {
      $event = new DrupalHookAlterEvent($data, $context1, $context2);
      $this->eventDispatcher->dispatch("drupal.hook_{$hook}_alter", $event);

      $data = $event->getData();
      $context1 = $event->getFirstContext();
      $context2 = $event->getSecondContext();
    }

    return parent::alter($type, $data, $context1, $context2);
  }

  /**
   * {@inheritdoc}
   */
  public function invoke($module, $hook, array $args = []) {
    $discovery = new HookSubscriberDiscovery($this->eventDispatcher);
    $listeners = $discovery->findByModuleAndHook($module, $hook);

    foreach ($listeners as $callable) {
      call_user_func($callable, new DrupalHookEvent($args));
    }

    return parent::invoke($module, $hook, $args);
  }

  /**
   * {@inheritdoc}
   */
  public function invokeAll($hook, array $args = []) {
    $this->eventDispatcher->dispatch(
      'drupal.hook_' . $hook,
      new DrupalHookEvent($args)
    );

    return parent::invokeAll($hook, $args);
  }

}
