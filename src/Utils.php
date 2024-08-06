<?php

namespace WordpressPluginFramework;

use WordpressPluginFramework\Classes\SubmenuPage;

class Utils
{

  static public function default_view_data_callable()
  {
    return [];
  }

  /** Get the function name to calculate variables for an html template 
   * @param callable|mixed $value
   * @param string|null $fallback
   */
  static public function get_callable_name(
    mixed $value,
    string $fallback = null
  ) {
    $callable_name = null;
    $is_valid = false;
    if (!empty($value) && !is_null($value)) {
      $is_valid = is_callable($value, true, $callable_name);
    }
    return $is_valid
      ? $callable_name
      : $fallback;
  }

  /**
   * Gets the requested view name and file path, from the $this->views list,
   * based on the "page" parameter of the request.
   * @param SubmenuPage[] $views The views object to filter through.
   * @param string $fallback The default value to fall back to. 
   */
  static public function get_view_data(array $views, string $fallback): SubmenuPage
  {
    $param = isset($_GET['page']) ? $_GET['page'] : $fallback;
    $subpaths = explode('_', $param);
    $view = $subpaths[1];

    if (isset($views[$view])) return $views[$view];
    return $views[$fallback];
  }

  /** Include a template and expose the variables in the given array
   * @param string $file_path The path to the template file.
   * @param string[] $variables The variables to be exposed.
   */
  static public function include_with_variables(string $file_path, array $variables)
  {
    $output = NULL;
    if (file_exists($file_path)) {
      extract($variables); // Import the variables so the template can user them 
      ob_start(); // Start output buffering
      include $file_path; // Include the template file
      $output = ob_get_clean(); // End buffering and return its contents
    }
    return $output;
  }

  /** Get the name of a file from their path. Otherwise return the domain name. */
  static public function name($path, $domain)
  {
    $info = pathinfo($path);
    $is_index = $info['filename'] === 'index';
    if (($is_index && !isset($domain)) || !$is_index) return $info['filename'];
    return $domain;
  }

  /** Generate a four digit random version number, to bust the wp cache */
  static public function rndv()
  {
    $v = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    return $v;
  }
}
