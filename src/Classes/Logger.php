<?php

class Logger
{
  public string $path;

  function __construct(string $path)
  {
    $this->path = $path;
  }

  function log(array $data)
  {
    error_log(json_encode($data));
    error_log(
      json_encode($data, JSON_PRETTY_PRINT),
      3,
      $this->path . 'error_log.txt'
    );
  }
}
