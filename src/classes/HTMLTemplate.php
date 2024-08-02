<?php

namespace WordpressPluginFramework;

class HTMLTemplate
{
  public string $filepath = '';
  private string $variables_callable;
  /** @param (array{
   *    variables_callable:string,
   *    filepath:string,
   * }) $props */
  function __construct($props)
  {
    $this->filepath = $props['filepath'];
    $this->variables_callable = get_template_variables_callable(
      $props['variables_callable'],
      'default_view_data_callable'
    );
  }

  function load()
  {
    $callable = $this->variables_callable;
    echo include_with_variables($this->filepath, $callable());
  }
}
