<?php

namespace WordpressPluginFramework\Classes;

use WordpressPluginFramework\Interfaces\Base;
use WordpressPluginFramework\Utils;

class HTMLTemplate implements Base
{
  public string $filepath = '';
  public string $variables_callable;

  /** @param (array{
   *    variables_callable:callable|mixed,
   *    filepath:string,
   * }) $props */
  function __construct($props)
  {
    $this->filepath = $props['filepath'];
    $this->set_callable($props['variables_callable']);
  }

  function set_callable(mixed $callable)
  {
    $u = new Utils();
    $this->variables_callable = $u->get_callable_name(
      isset($callable) ? $callable : null,
      'default_view_data_callable'
    );
  }

  function init()
  {
    $u = new Utils();
    $callable = $this->variables_callable;
    echo $u->include_with_variables($this->filepath, $callable());
  }
}
