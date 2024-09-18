<?php

namespace WordpressPluginFramework\Classes;

class Ajax
{
  public string $name;
  public bool $public = false;

  /** 
   * @param (array{
   *    name:string,
   *    public?:bool,
   * }) $props */
  function __construct($props)
  {
    $this->name = $props['name'];
    if (isset($props['public'])) $this->public = $props['public'];
  }
  function init()
  {
    $func = $this->name;
    if ($this->public) add_action('wp_ajax_nopriv_' . $this->name, $func);
    add_action('wp_ajax_' . $this->name, $func);
  }
}
