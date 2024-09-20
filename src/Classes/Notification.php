<?php

namespace WordpressPluginFramework\Classes;

class Notification
{
  public string $domain;
  public string $prefix;
  public string $transient_id;
  public HTMLTemplate $html;

  public function __construct(string $prefix, ?HTMLTemplate $html)
  {
    $this->prefix = $prefix;
    $this->transient_id = $prefix . '_notifications';
    if (isset($html)) {
      $this->html = $html;
    } else {
      $this->html = new HTMLTemplate([
        'filepath' => __DIR__ . '/../Templates/DefaultNotification.php',
      ]);
    }
    if (!isset($this->html->variables_callable)) {
      $this->html->set_callable([$this, 'get']);
    }
  }

  private function notify(string $message, string $level)
  {
    add_settings_error(
      $this->prefix . '_msg',
      $this->prefix . '_msg_option',
      __($message),
      $level
    );
    set_transient($this->transient_id, get_settings_errors(), 30);
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

  public function get_notificactions()
  {
    /** @var (array{
     *  message: string,
     *  code: string,
     *  setting: string,
     *  type: string
     * }[]) */
    $notification = get_transient($this->transient_id);
    if ($notification === false) return [];
    delete_transient($this->transient_id);
    return $notification;
  }

  public function get_html_variables()
  {
    return ['notifications' => $this->get_notificactions()];
  }

  function render()
  {
    $this->html->init();
  }
}
