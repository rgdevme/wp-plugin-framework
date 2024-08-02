<?php

namespace WordpressPluginFramework;

class Shortcode implements Base
{
  public string $name;
  public string $callable;
  /** @var array<string> */ public array $attributes = [];
  /** @var array<Stylesheet|Script> */ public array $inject = [];

  /** @param (array{
   *    attributes:array<string>,
   *    callable:callable|mixed,
   *    inject?:array<Stylesheet|Script>,
   *    name:string,
   * }) $props */
  public function __construct($props)
  {
    $this->name = $props['name'];
    if (isset($props['attributes'])) $this->attributes = $props['attributes'];
    if (isset($props['inject'])) $this->inject = $props['inject'];

    $callable_name = get_callable_name($props['callable']);
    if ($callable_name) $this->callable = $callable_name;
  }

  function init()
  {
    foreach ($this->inject as $injected) {
      $injected->init();
    }
    add_shortcode($this->name, $this->callable);
  }


  /** @param array<string,string> $attributes */
  function get($attributes)
  {
    $shortcode = "[" . $this->name . " ";
    foreach ($attributes as $key => $value) {
      if (!array_key_exists($key, $this->attributes)) continue;
      $shortcode .= $key . '="' . $value . '" ';
    }
    $shortcode .= "/]";
    return $shortcode;
  }

  function in_content()
  {
    global $post;
    if (!$post->post_content) return false;
    return has_shortcode($post->content, $this->name);
  }
};
