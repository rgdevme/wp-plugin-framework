<?php

namespace WordpressPluginFramework;

/** Include a template and expose the variables in the given array
 * @param string $file_path The path to the template file.
 * @param string[] $variables The variables to be exposed.
 */
function include_with_variables(string $file_path, array $variables)
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
