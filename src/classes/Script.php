<?php

namespace WordpressPluginFramework;

class Script extends Includable
{
  public bool $localized = false;
  public string $localized_object_name;

  /** 
   * @param (array{
   *    domain:string,
   *    path:string,
   *    v:string,
   *    condition:array,
   *    deps:array,
   *    admin?:boolean
   *    localized_object_name?:string,
   * }) $props */
  public function __construct($props)
  {
    parent::__construct($props);
    $this->localized_object_name =
      isset($props['localized_object_name']) && !empty($props['localized_object_name'])
      ? $props['localized_object_name']
      : $this->name;
  }

  function set_condition(array $callable)
  {
    if (empty($callable)) return;
    $callable_name =  '';
    $is_valid = is_callable($callable, true, $callable_name);
    if ($is_valid) $this->condition = $callable_name;
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

  function localize(array $globals = [])
  {
    // Ensure localization only happens once
    if ($this->localized || empty($globals)) return;
    wp_localize_script(
      $this->name,
      $this->localized_object_name,
      $globals
    );
    $this->localized = true;
  }
};
