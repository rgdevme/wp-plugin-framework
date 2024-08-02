<?php

namespace WordpressPluginFramework;

class MenuPage implements Base
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

    add_action('admin_menu', [$this, 'init_menu'], 20);

    foreach ($this->inject as $injected) {
      $injected->init();
    }
  }

  function init_menu()
  {
    if ($this->hidden) return;
    add_menu_page(
      esc_html__($this->name, $this->domain),
      esc_html__($this->name, $this->domain),
      'manage_options',
      $this->slug,
      [$this, 'load_view'],
      'dashicons-admin-page'
    );
  }

  function load_view()
  {
    $classes = [$this->slug, $this->name];
    echo '<div class="' . join(' ', $classes) . '">';
    echo '<div class="container">';
    echo '<div class="inner">';

    echo include_with_variables($this->filepath, $this->getData());

    echo '</div>';
    echo '</div>';
    echo '</div>';
  }

  function getData()
  {
    $func = $this->callable;
    return $func();
  }
}
