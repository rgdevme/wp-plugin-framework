<?php

namespace WordpressPluginFramework\Classes;

/** Registers a page of a plugin in the WP sidebar
 * under the define MenuPage.
 * */
class SubmenuPage extends MenuPage
{
  /** @param (array{
   *    name:string,
   *    html: HTMLTemplate,
   *    slug:string,
   *    hidden:?boolean,
   *    inject:?array<Stylesheet|Script>,
   * }) $props */
  function __construct($props)
  {
    parent::__construct($props);
  }

  function init_menu()
  {
    if ($this->hidden) return;
    add_submenu_page(
      $this->domain,
      esc_html__($this->name, $this->domain),
      esc_html__($this->name, $this->domain),
      'manage_options',
      $this->slug,
      [$this, 'load_view']
    );
    foreach ($this->inject as $injected) {
      $injected->init();
    }
  }
}
