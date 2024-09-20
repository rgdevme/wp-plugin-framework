<?php

namespace WordpressPluginFramework\Classes;

/** Registers an ajax action in as many hooks as passed in the parameters.
 * As different hooks may have different arguments, some distinction 
 * functionality for the parameters might be needed.
 */
class Ajax extends Action
{
  /** 
   * @param (array{
   *    callable:callable|mixed,
   *    priority:?int,
   *    accepted_args:?int,
   *    mode:?'public'|'private'|'both'
   * }) $props */
  function __construct($props)
  {
    $mode = 'private';
    if (isset($props['mode'])) $mode = $props['mode'];

    $new_props = $props;
    $new_props['hooks'] = [];

    if ($mode === 'private' || $mode=== 'both') {
      $new_props['hooks'][] = 'wp_ajax_' . $props['callable']; 
    }
    if ($mode === 'public' || $mode=== 'both') {
      $new_props['hooks'][] = 'wp_ajax_nopriv_' . $props['callable']; 
    }
    parent::__construct($new_props);
  }
}
