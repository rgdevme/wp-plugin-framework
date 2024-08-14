<?php

namespace WordpressPluginFramework\Classes;

use WordpressPluginFramework\Utils;

class Action
{
  public string $hook;
  public string $callable;

  /** 
   * @param (array{
   *    hook:string,
   *    callable:callable|mixed,
   * }) $props */
  function __construct($props)
  {
    $this->hook = $props['hook'];
    $this->callable = Utils::get_callable_name($props['callable']);
  }

  function init()
  {
    add_action($this->hook, $this->callable);
  }
}
