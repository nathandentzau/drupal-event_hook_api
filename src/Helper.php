<?php

namespace Drupal\event_hook_api;

final class Helper {

  public static function eventNameFromHook($hook) {
    return 'drupal.hook_' . $hook;
  }

  public static function moduleNameFromNamespace($namespace) {
    if (preg_match('/^Drupal\\\\(\w+)\\\\.+$/', $namespace, $matches) === 0) {
      return NULL;
    }

    return $matches[1];
  }

}
