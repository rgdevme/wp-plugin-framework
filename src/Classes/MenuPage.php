<?php

namespace WordpressPluginFramework\Classes;

use WordpressPluginFramework\Interfaces\Base;

/** Registers the top-most page of a plugin, acting as its
 * point of entry in the WP sidebar. Submenu pages can be added
 * through the submenu property, using the same api and the
 * [SubmenuPage](#) class.
 * */
class MenuPage implements Base
{
  public string $name = '';
  public HTMLTemplate $html;
  public string $slug = '';
  public ?string $domain;
  public bool $hidden = false;
  /** @var SubmenuPage[] */ private array $submenu = [];
  /** @var array<Stylesheet|Script> */ public array $inject = [];

  /** @param (array{
   *    name:string,
   *    html: HTMLTemplate,
   *    slug:string,
   *    submenu:?(SubmenuPage[]),
   *    hidden:?boolean,
   *    inject:?array<Stylesheet|Script>,
   * }) $props */
  function __construct($props)
  {
    $this->name = $props['name'];
    $this->html = $props['html'];

    $this->slug = strtolower($props['slug']);

    if (isset($props['hidden'])) $this->hidden = $props['hidden'];
    if (isset($props['inject'])) {
      $this->inject = $props['inject'];
    }
    if (isset($props['submenu'])) {
      $this->submenu = $props['submenu'];
      array_walk($this->submenu, fn($x) => $x->domain = $this->domain);
    }
  }

  function init()
  {
    add_action('admin_menu', [$this, 'init_menu'], 20);
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

    foreach ($this->submenu as $page) {
      $page->init();
    }

    if (!key_exists('page', $_GET) || $_GET['page'] !== $this->slug) return;

    foreach ($this->inject as $injected) {
      $injected->init();
    }
  }

  function load_view()
  {
    $classes = [$this->slug, $this->name];
    echo '<div class="' . join(' ', $classes) . '">';
    echo '<div class="container">';
    echo '<div class="inner">';

    $this->html->init();

    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
}
