<?php

namespace WordpressPluginFramework\Classes;

use WordpressPluginFramework\Utilities\Utils;

class Action
{
  /** @var string[] $hook */
  public array $hooks = [];
  public string $callable;
  public int $priority = 10;
  public int $accepted_args = 1;

  /** 
   * @param (array{
   *    hooks:string[],
   *    callable:callable|mixed,
   *    priority: int,
   *    accepted_args: int
   * }) $props */
  function __construct($props)
  {
    $this->hooks = $props['hooks'];
    $this->callable = Utils::get_callable_name($props['callable']);
    if (isset($props['priority'])) {
      $this->priority = $props['priority'];
    }
    if (isset($props['accepted_args'])) {
      $this->accepted_args = $props['accepted_args'];
    }
  }

  function init()
  {
    foreach ($this->hooks as $hook) {
      add_action(
        $hook,
        $this->callable,
        $this->priority,
        $this->accepted_args
      );
    }
  }
}
