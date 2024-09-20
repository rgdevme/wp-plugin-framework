<?php

namespace WordpressPluginFramework;

use WordpressPluginFramework\Interfaces\Base;
use WordpressPluginFramework\Classes\DBTable;
use WordpressPluginFramework\Classes\MenuPage;

/** Main and single point of entry for the app.
 * Once instatiated, it should be immediatly initialized with
 * the `init` method. The `components` key is used to handle the
 * load of everything that's not a menu page or a database table.
 */
class Plugin
{
  public string $plugin_path;
  public ?string $domain = null;

  public DBTable $db;
  /** @var ?MenuPage */ public ?MenuPage $menu_page = null;
  /** @var Base[] */ public array $components = [];

  /** 
   * @param (array{
   *    plugin_path:?string,
   *    domain:?string
   *    menu_page:?MenuPage,
   *    db:?DBTable,
   *    components:?(Base[]),
   * }) $props */
  public function __construct(
    $props
  ) {
    if (isset($props['plugin_path'])) {
      $this->plugin_path = $props['plugin_path'];
    }
    if (isset($props['domain'])) {
      $this->domain = $props['domain'];
    }
    if (isset($props['menu_page'])) {
      $this->menu_page = $props['menu_page'];
      $this->menu_page->domain = $this->domain;
    }
    if (isset($props['db'])) {
      $this->db = $props['db'];
    }
    if (isset($props['components'])) {
      $this->components = $props['components'];
    }
  }

  public function init()
  {
    // Create new table on plugin resitration
    if (isset($this->db) && !is_null($this->db)) {
      register_activation_hook($this->plugin_path, [$this->db, 'init']);
      register_deactivation_hook($this->plugin_path, [$this->db, 'kill']);
      register_uninstall_hook($this->plugin_path, [$this->db, 'kill']);
    }

    if (isset($this->menu_page)) {
      $this->menu_page->init();
    }
    foreach ($this->components as $dep) {
      $dep->init();
    }
  }
}
