<?php

use function WordpressPluginFramework\default_view_data_callable;

/** Get the function name to calculate variables for an html template 
 * @param callable|mixed $value
 * @param string|null $fallback
 */
function get_template_variables_callable(
  mixed $value,
  string $fallback = null
) {
  $callable_name = null;
  $is_valid = false;
  if (isset($value) && !empty($value) && !is_null($value)) {
    $is_valid = is_callable($value, true, $callable_name);
  }
  return $is_valid
    ? $callable_name
    : $fallback;
}
