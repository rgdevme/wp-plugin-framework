<?php

namespace WordpressPluginFramework;

use WordpressPluginFramework\Classes\ActionData;
use WordpressPluginFramework\Classes\DBTable;
use WordpressPluginFramework\Classes\MenuPage;
use WordpressPluginFramework\Classes\Shortcode;
use WordpressPluginFramework\Classes\SubmenuPage;

class Plugin
{
  public string $plugin_path;
  public string $root_file;

  public DBTable $db;
  /** @var MenuPage */ private array $menu_page;
  /** @var SubmenuPage[] */ private array $submenu_pages = [];
  /** @var ActionData[] */ public array $actions = [];
  /** @var Shortcode[] */ public array $shortcodes = [];

  /** 
   * @param (array{
   *    plugin_path?:string,
   *    root_file?:string,
   *    menu_page?:MenuPage,
   *    submenu_pages?: SubmenuPage[],
   *    db?: DBTable,
   *    actions?: ActionData[],
   *    shortcodes?: Shortcode[],
   * }) $props */
  public function __construct(
    $props
  ) {
    if (isset($props['menu_page'])) {
      $this->menu_page = $props['menu_page'];
    }
    if (isset($props['plugin_path'])) {
      $this->plugin_path = $props['plugin_path'];
    }
    if (isset($props['root_file'])) {
      $this->root_file = $props['root_file'];
    }
    if (isset($props['db'])) {
      $this->db = $props['db'];
    }
    if (isset($props['actions'])) {
      $this->actions = $props['actions'];
    }
    if (isset($props['shortcodes'])) {
      $this->shortcodes = $props['shortcodes'];
    }
    if (isset($props['submenu_pages'])) {
      $this->submenu_pages = $props['submenu_pages'];
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

    /** @var Shortcode Initialize shortcodes */
    foreach ($this->shortcodes as $sc) {
      $sc->init();
    }

    /** @var ActionData Initialize actions */
    foreach ($this->actions as $ac) {
      $ac->init();
    }

    if (isset($this->menu_page)) {
      $this->menu_page->init();
      /** @var SubmenuPage Initialize vies */
      foreach ($this->submenu_pages as $vd) {
        $vd->init();
      }
    }
  }
}
