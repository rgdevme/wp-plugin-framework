<?php

namespace WordpressPluginFramework;

use WordpressPluginFramework\Interfaces\Base;
use WordpressPluginFramework\Classes\DBTable;
use WordpressPluginFramework\Classes\MenuPage;
use WordpressPluginFramework\Classes\SubmenuPage;

class Plugin
{
  public string $plugin_path;
  public string $root_file;

  public DBTable $db;
  /** @var MenuPage */ private array $menu_page;
  /** @var SubmenuPage[] */ private array $submenu_pages = [];
  /** @var Base[] */ public array $inc = [];

  /** 
   * @param (array{
   *    plugin_path?:string,
   *    root_file?:string,
   *    menu_page?:MenuPage,
   *    submenu_pages?: SubmenuPage[],
   *    db?: DBTable,
   *    inc?: Base[],
   * }) $props */
  public function __construct(
    $props
  ) {
    if (isset($props['plugin_path'])) {
      $this->plugin_path = $props['plugin_path'];
    }
    if (isset($props['root_file'])) {
      $this->root_file = $props['root_file'];
    }
    if (isset($props['menu_page'])) {
      $this->menu_page = $props['menu_page'];
    }
    if (isset($props['submenu_pages'])) {
      $this->submenu_pages = $props['submenu_pages'];
    }
    if (isset($props['db'])) {
      $this->db = $props['db'];
    }
    if (isset($props['inc'])) {
      $this->inc = $props['inc'];
    }
  }

  public function init()
  {
    // Create new table on plugin resitration
    if (isset($this->db) && !is_null($this->db)) {
      $path = $this->plugin_path . $this->root_file;
      register_activation_hook($path, [$this->db, 'init']);
      register_deactivation_hook($path, [$this->db, 'kill']);
      register_uninstall_hook($path, [$this->db, 'kill']);
    }

    foreach ($this->inc as $dep) {
      $dep->init();
    }

    if (isset($this->menu_page)) {
      $this->menu_page->init();
      foreach ($this->submenu_pages as $vd) {
        $vd->init();
      }
    }
  }
}
