<?php

namespace Drupal\event_hook_api;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HookSubscriberDiscovery {

  protected $eventDispatcher;

  public function __construct(EventDispatcherInterface $eventDispatcher) {
    $this->eventDispatcher = $eventDispatcher;
  }

  public function findAll() {
    $eventListeners = [];

    foreach ($this->eventDispatcher->getListeners() as $eventName => $listeners) {
      if (strpos($eventName, 'drupal.hook') !== 0) {
        continue;
      }

      foreach ($listeners as $priority => $listener) {
        $eventListeners[$eventName][] = $listener;
      }
    }

    return $eventListeners;
  }

  public function findByHook($hook) {
    $listeners = $this->findAll();
    $event = Helper::eventNameFromHook($hook);

    if (empty($listeners[$event])) {
      return [];
    }

    return $listeners[$event];
  }

  public function findByModule($module) {
    $eventListeners = [];

    foreach ($this->findAll() as $eventName => $listeners) {
      foreach ($listeners as $listener) {
        if ($listener[0] instanceof \Closure) {
          continue;
        }

        $namespace = get_class($listener[0]);
        if (Helper::moduleNameFromNamespace($namespace) === $module) {
          $eventListeners[$eventName][] = $listener;
        }
      }
    }

    return $eventListeners;
  }

  public function findByModuleAndHook($module, $hook) {
    $moduleListeners = $this->findByModule($module);
    $eventName = Helper::eventNameFromHook($hook);

    if (empty($moduleListeners[$eventName])) {
      return NULL;
    }

    return $moduleListeners[$eventName];
  }

}
