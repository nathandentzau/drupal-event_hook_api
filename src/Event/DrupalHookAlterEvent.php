<?php

namespace Drupal\event_hook_api\Event;

use Symfony\Component\EventDispatcher\Event;

class DrupalHookAlterEvent extends Event {

  protected $data;

  protected $context1;

  protected $context2;

  public function __construct($data, $context1, $context2) {
    $this->data = $data;
    $this->context1 = $context1;
    $this->context2 = $context2;
  }

  public function getData() {
    return $this->data;
  }

  public function setData($data) {
    $this->data = $data;
    return $this;
  }

  public function getFirstContext() {
    return $this->context1;
  }

  public function setFirstContext($context) {
    $this->context1 = $context;
    return $this;
  }

  public function getSecondContext() {
    return $this->context2;
  }

  public function setSecondContext($context) {
    $this->context2 = $context;
    return $this;
  }

}
