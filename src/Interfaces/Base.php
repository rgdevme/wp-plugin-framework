<?php

namespace WordpressPluginFramework\Interfaces;

/** Base interface from which every WPF class shoud inherit from */
interface Base
{
  public function __construct($props);
  public function init();
};
