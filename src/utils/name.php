<?php

namespace WordpressPluginFramework;

/** Get the name of a file from their path. Otherwise return the domain name. */
function name($path, $domain)
{
  $info = pathinfo($path);
  $is_index = $info['filename'] === 'index';
  if (($is_index && !isset($domain)) || !$is_index) return $info['filename'];
  return $domain;
}
