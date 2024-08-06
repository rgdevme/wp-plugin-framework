<?php

namespace WordpressPluginFramework\Classes;

class SubmenuPage extends MenuPage
{
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
  }
}
