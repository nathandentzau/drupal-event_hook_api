<?php

namespace Drupal\event_hook_api\Event;

use Symfony\Component\EventDispatcher\Event;

class DrupalHookEvent extends Event {

  protected $args;

  public function __construct(array $args = []) {
    $this->args = $args;
  }

  public function __get($name) {
    return $this->getArgument($name);
  }

  public function getArgument($name) {
    return isset($this->args[$name]) ? $this->args[$name] : NULL;
  }

  public function getArguments() {
    return $this->args;
  }

}
