<?php

namespace WordpressPluginFramework\Classes;

use WordpressPluginFramework\Interfaces\Base;

class DBColumn
{
  public string $name;
  public string $type;
  public bool $allowNull = true;
  public bool $isPK = false;
  public bool $autoIncrement = false;
  public $default = null;

  /** 
   * @param (array{
   *    name:string,
   *    type:string,
   *    default:string,
   *    allowNull:bool,
   *    autoIncrement:bool,
   *    isPK?:bool,
   * }) $props */
  public function __construct(
    $props
  ) {
    $this->name = $props['name'];
    $this->type = $props['type'];
    if (!isset($props['allowNull'])) {
      $this->allowNull = $props['allowNull'];
    }
    if (!isset($props['isPK'])) {
      $this->isPK = $props['isPK'];
    }
    if (!isset($props['autoIncrement'])) {
      $this->autoIncrement = $props['autoIncrement'];
    }
    if (!isset($props['default'])) {
      $this->default = $props['default'];
    }
  }
}

class DBTable implements Base
{
  public string $name;
  public string $charset;
  /** @var DBColumn[] */
  public $columns;

  /** 
   * @param string $name
   * @param DBColumn[] $columns
   *  */
  public function __construct($name, $columns)
  {
    global $wpdb;
    $this->name = $wpdb->prefix . $name;
    $this->columns = $columns;
    $this->charset = $wpdb->get_charset_collate();
  }

  function init()
  {
    $sql = "CREATE TABLE $this->name (";
    $pk = '';

    foreach ($this->columns as $column) {
      $sql .= "\n" . $column->name . " " . $column->type;
      if ($column->default) $sql .= " DEFAULT " . "'$column->default'";
      if (!$column->allowNull) $sql .= " NOT NULL";
      if ($column->autoIncrement) $sql .= " AUTO_INCREMENT";
      $sql .= ",";
      if ($column->isPK) $pk = "\nPRIMARY KEY  ($column->name)";
    }

    $sql .= $pk;
    $sql .= "\n) $this->charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
  }

  function kill()
  {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS $this->name");
  }
}
