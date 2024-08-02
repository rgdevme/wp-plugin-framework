<?php

namespace WordpressPluginFramework;

class Includable implements Base
{
  public string $name;
  public string $path;
  public string $condition;
  public string $v;
  public array $deps = [];
  public bool $is_admin = false;

  public string $action;

  /** 
   * @param (array{
   *    domain:string,
   *    path:string,
   *    v:string,
   *    condition:array,
   *    deps:array,
   *    admin?:boolean
   * }) $props */
  public function __construct($props)
  {
    $this->name = name($props['path'], $props['domain']);
    $this->path = $props['path'];
    if (isset($props['admin'])) $this->is_admin = $props['admin'];
    $this->action = $this->is_admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

    if (isset($props['deps'])) $this->deps = $props['deps'];
    if (isset($props['v']) && !empty($props['v'])) {
      $this->v = $props['v'];
    } else $this->v = rndv();
    $this->set_condition($props['condition']);
  }

  function set_condition(array $callable)
  {
    $callable_name =  get_template_variables_callable($callable);
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
        wp_enqueue_script($this->name, $this->path, $this->deps, $this->v, true);
      }
    );
  }
};
