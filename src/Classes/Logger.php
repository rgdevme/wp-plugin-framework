<?php

namespace WordpressPluginFramework\Classes;

use DateTime;

class Logger
{
  public string $path;

  function __construct(string $path)
  {
    $this->path = $path;
  }

  function write(array $data, string $level)
  {
    $date = new DateTime();
    $date = $date->format("Y-m-d H:i ");

    error_log(
      $level .
        $date .
        json_encode($data, JSON_PRETTY_PRINT),
      3,
      $this->path . 'log.txt'
    );
  }

  function log(array $data)
  {
    $this->write($data, 'INFO ');
  }
  function error(array $data)
  {
    $this->write($data, 'ERR  ');
  }
  function warn(array $data)
  {
    $this->write($data, 'WARN ');
  }
}
