<?php

namespace WordpressPluginFramework;

class HTMLTemplate
{
  public string $filepath = '';
  private string $callable;
  /** @param (array{
   *    callable:string,
   *    filepath:string,
   * }) $props */
  function __construct($props)
  {
    $this->filepath = $props['filepath'];
    $this->callable = !isset($props['callable'])
      ? 'default_view_data_callable'
      : $props['callable'];
  }

  function load()
  {
    $callable = $this->callable;
    echo include_with_variables($this->filepath, $callable());
  }
}
