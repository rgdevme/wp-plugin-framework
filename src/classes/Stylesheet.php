<?php

namespace WordpressPluginFramework;

class StyleSheet extends Includable
{
  public bool $enqueued = false;

  function init()
  {
    if ($this->enqueued) return;

    add_action(
      $this->action,
      function () {
        $passed = $this->call_condition();
        if (!$passed) return;
        wp_enqueue_style($this->name, $this->path, $this->deps, $this->v, 'all');
        $this->enqueued = true;
      }
    );
  }
};
