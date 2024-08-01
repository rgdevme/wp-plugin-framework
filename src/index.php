<?php

namespace WordpressPluginFramework;

class Plugin
{
  public string $domain;
  public string $plugin_path;
  public string $root_file;
  public string $slug;
  public string $menu_title;

  private $current_page = '';

  public DBTable $db;
  /** @var ViewData[] */ private array $views;
  /** @var ActionData[] */ public array $actions = [];
  /** @var Shortcode[] */ public array $shortcodes = [];
  /** @var Base[] */ public array $initializables = [];

  /** 
   * @param (array{
   *    menu_title:string,
   *    domain:string
   *    plugin_path:string,
   *    root_file:string,
   *    slug:string
   *    views: ViewData[],
   *    db?: DBTable,
   *    actions?: ActionData[],
   *    shortcodes?: Shortcode[],
   *    init: Base[]
   * }) $props */
  public function __construct(
    $props
  ) {
    $this->domain = $props['domain'];
    $this->menu_title = $props['menu_title'];
    $this->plugin_path = $props['plugin_path'];
    $this->root_file = $props['root_file'];
    $this->slug = $props['slug'];

    $this->views = $props['views'];

    if (!isset($props['db'])) {
      $this->db = $props['db'];
    }
    if (!isset($props['actions'])) {
      $this->actions = $props['actions'];
    }
    if (!isset($props['shortcodes'])) {
      $this->shortcodes = $props['shortcodes'];
    }
    if (!isset($props['init'])) {
      $this->initializables = $props['init'];
    }
  }

  public function init()
  {
    // Add menu items
    add_action('admin_menu', [$this, 'init_menu'], 20);

    // Create new table on plugin resitration
    if ($this->db === null) {
      $path = $this->plugin_path . $this->root_file;
      register_activation_hook($path, [$this->db, 'init']);
      register_deactivation_hook($path, [$this->db, 'kill']);
      register_uninstall_hook($path, [$this->db, 'kill']);
    }

    // Initialize shortcodes
    /** @var Shortcode */
    foreach ($this->shortcodes as $sc) {
      $sc->init();
    }

    // Initialize actions
    /** @var ActionData */
    foreach ($this->actions as $ac) {
      $ac->init();
    }
    // Initialize views
    foreach ($this->views as $vd) {
      $vd->init();
    }
    // Initialize initializables
    foreach ($this->initializables as $initializable) {
      $initializable->init();
    }
  }


  public function init_menu()
  {
    add_menu_page(
      esc_html__($this->menu_title, $this->domain),
      esc_html__($this->menu_title, $this->domain),
      'manage_options',
      $this->slug,
      [$this, 'load_view'],
      'dashicons-admin-page'
    );

    foreach ($this->views as $view) {
      if ($view->hidden) continue;
      add_submenu_page(
        $view->domain,
        esc_html__($view->name, $this->domain),
        esc_html__($view->name, $this->domain),
        'manage_options',
        $view->slug,
        [$this, 'load_view']
      );
    }
  }

  function load_view()
  {

    $view = get_view_data($this->views, 'list');
    $this->current_page = $view->name;

    echo '<div class="' . $this->slug . ' ' . $this->current_page . '">';
    echo '<div class="container">';
    echo '<div class="inner">';

    echo include_with_variables($view->filepath, $view->getData());

    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
}
