<?php

namespace WordpressPluginFramework\Classes;

use WordpressPluginFramework\Interfaces\Base;
use WordpressPluginFramework\Utils;

class MenuPage implements Base
{
  public string $name = '';
  public string $domain = '';
  public string $filepath = '';
  public string $slug = '';
  private string $callable = 'default_view_data_callable';
  public bool $hidden = false;
  /** @var array<Stylesheet|Script> */ public array $inject = [];

  /** @param (array{
   *    name:string,
   *    domain:string,
   *    filepath:string,
   *    slug:?string,
   *    callable:?string,
   *    hidden:?boolean,
   *    inject:?array<Stylesheet|Script>,
   * }) $props */
  function __construct($props)
  {
    $this->name = $props['name'];
    $this->domain = $props['domain'];
    $this->filepath = $props['filepath'];

    $slug = $this->domain;
    if (!empty($props['slug'])) $slug .= '-' . $props['slug'];
    $this->slug = strtolower($slug);

    if (isset($props['callable'])) $this->callable = $props['callable'];
    if (isset($props['hidden'])) $this->hidden = $props['hidden'];
    if (isset($props['inject'])) $this->inject = $props['inject'];
  }

  function init()
  {
    add_action('admin_menu', [$this, 'init_menu'], 20);

    if (!key_exists('page', $_GET) || $_GET['page'] !== $this->slug) return;
    
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
    $u = new Utils();
    $classes = [$this->slug, $this->name];
    echo '<div class="' . join(' ', $classes) . '">';
    echo '<div class="container">';
    echo '<div class="inner">';

    echo $u->include_with_variables($this->filepath, $this->getData());

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
