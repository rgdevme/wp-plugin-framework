<?php

namespace WordpressPluginFramework\Classes;

use WordpressPluginFramework\Interfaces\Base;
use WordpressPluginFramework\Utilities\Utils;

class Includable implements Base
{
  public string $name;
  public string $url;
  public string $condition;
  public string $v;
  public array $deps = [];
  public bool $is_admin = false;

  public string $action;

  /** 
   * @param (array{
   *    url:string,
   *    condition:null|callable|mixed,
   *    admin:?boolean,
   *    deps:?array,
   *    v:?string
   * }) $props */
  public function __construct($props)
  {
    $u = new Utils();
    $this->url = $props['url'];
    $this->name = pathinfo($this->url)['filename'];

    if (isset($props['admin'])) $this->is_admin = $props['admin'];

    $this->action = $this->is_admin
      ? 'admin_enqueue_scripts'
      : 'wp_enqueue_scripts';

    if (isset($props['deps'])) $this->deps = $props['deps'];

    if (!empty($props['v'])) $this->v = $props['v'];
    else $this->v = $u->rndv();

    if (isset($props['condition'])) $this->set_condition($props['condition']);
  }

  function set_condition(mixed $callable)
  {
    $u = new Utils();
    $callable_name =  $u->get_callable_name($callable);
    if ($callable_name) $this->condition = $callable_name;
  }

  function call_condition()
  {
    if (!isset($this->condition)) return true;
    $fn = $this->condition;
    if (!$fn()) return false;
    else return true;
  }

  function init()
  {
    add_action(
      $this->action,
      function () {
        $passed = $this->call_condition();
        if (!$passed) return;
        wp_enqueue_script($this->name, $this->url, $this->deps, $this->v, true);
      }
    );
  }
};
