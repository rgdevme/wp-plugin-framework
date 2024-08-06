<?php

namespace WordpressPluginFramework\Classes;

class Notification
{
  public string $prefix;
  public function __construct(string $prefix)
  {
    $this->prefix = $prefix;
  }

  private function notify(string $message, string $level)
  {
    add_settings_error(
      $this->prefix . '_msg',
      $this->prefix . '_msg_option',
      __($message),
      $level
    );
    set_transient($this->prefix . '_errors', get_settings_errors(), 30);
  }

  public function error(string $message)
  {
    $this->notify($message, 'error');
  }
  public function warn(string $message)
  {
    $this->notify($message, 'warning');
  }
  public function inform(string $message)
  {
    $this->notify($message, 'info');
  }
  public function succeess(string $message)
  {
    $this->notify($message, 'success');
  }

  public function get()
  {
    $notifications = (array) get_transient("csvu_errors");
    delete_transient("csvu_errors");
    return $notifications;
  }
}
