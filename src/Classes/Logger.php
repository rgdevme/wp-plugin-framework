<?php

namespace WordpressPluginFramework\Classes;

class Logger
{
  public string $path;
  public string $filename = 'logs';
  public string $format = 'json';

  function __construct(
    string $path,
    ?string $filename = 'logs',
    ?string $format = 'json'
  ) {
    $this->path = $path;
    if (isset($this->filename)) $this->filename = $filename;
    if (isset($this->format)) $this->format = $format;
  }

  function write_txt(string $level, string $message)
  {
    if (!is_dir($this->path)) mkdir($this->path, 0777, true);
    $file_path = $this->path . '/' . $this->filename . '.log';
    $timestamp = date_create()->format('c');
    $new_log = join(' ', [
      $level,
      $timestamp,
      $message
    ]) . PHP_EOL;

    // Write the updated data back to the JSON file
    file_put_contents($file_path, $new_log, FILE_APPEND);
  }

  function write_json(string $level, string $message, ?array $data = null)
  {
    $file_path = $this->path . $this->filename . '.json';
    $timestamp = date_create()->format('c');
    $logs = [];

    if (file_exists($file_path)) {
      $file_contents = file_get_contents($file_path);
      $logs = json_decode($file_contents, true);
    }

    $new_log = [
      'timestamp' => $timestamp,
      'level'     => $level,
      'message'   => $message
    ];

    if (is_array($data) && !empty($data)) {
      $new_log = array_merge($new_log, $data);
    }

    // Append the new log to the existing data
    $logs[] = $new_log;

    $json = json_encode($logs, JSON_PRETTY_PRINT);

    // Write the updated data back to the JSON file
    file_put_contents($file_path, $json);
  }

  function write(string $level, string $message, ?array $data = null)
  {
    if ($this->format === 'json') {
      $this->write_json($level, $message, $data);
    } else {
      $this->write_txt($level, $message);
    }
  }

  function log(string $message, ?array $data = null)
  {
    $this->write('INFO  ', $message, $data);
  }
  function debug(string $message, ?array $data = null)
  {
    $this->write('DEBUG ', $message, $data);
  }
  function error(string $message, ?array $data = null)
  {
    $this->write('ERROR ', $message, $data);
  }
  function warn(string $message, ?array $data = null)
  {
    $this->write('WARN  ', $message, $data);
  }
}
