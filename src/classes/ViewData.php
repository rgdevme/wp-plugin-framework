<?php

namespace WordpressPluginFramework;

class ViewData implements Base
{

  public string $slug = '';
  public string $name = '';
  public string $domain = '';
  public string $route = '';
  public string $url = '';
  public string $filepath = '';
  public bool $hidden;
  private string $callable;
  /** @var array<Stylesheet|Script> */ public array $inject;

  /** @param (array{
   *    name:string,
   *    route:string,
   *    url:string,
   *    slug:string,
   *    domain:string,
   *    callable:string,
   *    hidden:boolean,
   *    inject:array<Stylesheet|Script>,
   * }) $props */
  function __construct($props)
  {
    $this->name = $props['name'];
    $this->domain = $props['domain'];
    $slug = !isset($props['slug']) || empty($props['slug'])
      ? $props['domain']
      : $props['domain'] . '_' . $props['slug'];
    $this->slug = $slug;
    $this->route = $props['route'];
    $this->url = $props['url'];
    $this->filepath = $props['route'] . '/index.php';
    $this->hidden = isset($props['hidden']) ?  $props['hidden'] : false;
    $this->callable = !isset($props['callable'])
      ? 'default_view_data_callable'
      : $props['callable'];
    $this->inject = isset($props['inject']) ? $props['inject'] : [];
  }

  function init()
  {

    if ($_GET['page'] !== $this->slug) return;
    foreach ($this->inject as $injected) {
      $injected->init();
    }
  }

  function getData()
  {
    $func = $this->callable;
    return $func();
  }
}
