<?php

namespace WordpressPluginFramework;

/**
 * Gets the requested view name and file path, from the $this->views list,
 * based on the "page" parameter of the request.
 * @param ViewData[] $views The views object to filter through.
 * @param string $fallback The default value to fall back to. 
 */
function get_view_data(array $views, string $fallback): ViewData
{
  $param = isset($_GET['page']) ? $_GET['page'] : $fallback;
  $subpaths = explode('_', $param);
  $view = $subpaths[1];

  if (isset($views[$view])) return $views[$view];
  return $views[$fallback];
}
