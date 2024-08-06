<?php

namespace WordpressPluginFramework\Classes;

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
   *    admin?:boolean,
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
