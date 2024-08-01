<?php

namespace WordpressPluginFramework;

/** Generate a four digit random version number, to bust the wp cache */
function rndv()
{
  $v = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
  return $v;
}
