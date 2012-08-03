<?php
return array (
  0 => 
  array (
    'min' => '0',
    'hour' => '2',
    'day' => '*',
    'month' => '*',
    'dow' => '*',
    'plugin' => 'core',
    'handler' => 'db_backup',
  ),
  1 => 
  array (
    'min' => '0,15,30,45',
    'hour' => '*',
    'day' => '*',
    'month' => '*',
    'dow' => '*',
    'plugin' => 'core',
    'handler' => 'news_views',
  ),
  2 => 
  array (
    'min' => '20',
    'hour' => '2',
    'day' => '*',
    'month' => '*',
    'dow' => '*',
    'plugin' => 'core',
    'handler' => 'load_truncate',
  ),
);