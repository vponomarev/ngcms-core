<?php
$linkz = array (
  'rewrite' => 
  array (
    'category' => 'category/{alt}',
    'category_page' => 'category/{alt}/page/{page}.(html|htm)',
    'date' => '{year}/{month}/{day}',
    'date_page' => '{year}/{month}/{day}/page/{page}',
    'year' => '{year}/',
    'year_page' => '{year}/page/{page}',
    'month' => '{year}/{month}',
    'month_page' => '{year}/{month}/page/{page}',
    'user' => 'users/{author}',
    'firstpage' => 'page',
    'page' => 'page/{page}',
    'addnews' => 'addnews.(htm|html)',
    'profile' => 'profile.(htm|html)',
    'registration' => 'registration.(htm|html)',
    'activation' => 'activation.(htm|html)',
    'activation_do' => 'activation/{userid}/{code}',
    'lostpassword' => 'lostpassword.(htm|html)',
    'rss' => '(feed|rss|rss2).xml',
    'category_rss' => 'category/{alt}/(feed|rss|rss2).xml',
    'static' => 'static/{alt_name}.(html|htm)',
    'full' => 
    array (
      'by_cat' => 'category/{catlink}/{alt_name}.(html|htm)',
      'by_date' => '{year}/{month}/{day}/{alt_name}.(html|htm)',
    ),
    'full_page' => 
    array (
      'by_cat' => 'category/{catlink}/{alt_name}/{page}',
      'by_date' => '{year}/{month}/{day}/{alt_name}/{page}',
    ),
    'print' => 
    array (
      'by_cat' => 'category/{catlink}/{alt_name}.print',
      'by_date' => '{year}/{month}/{day}/{alt_name}.print',
    ),
  ),
  'plain' => 
  array (
    'category' => '?category={alt}',
    'category_page' => '?category={alt}&cstart={page}',
    'category_rss' => '?action=plugin&plugin=rss_export&category={alt}',
    'full' => 
    array (
      'by_cat' => '?category={catlink}&altname={alt_name}',
      'by_date' => '?year={year}&month={month}&day={day}&altname={alt_name}',
    ),
    'full_page' => 
    array (
      'by_cat' => '?category={catlink}&altname={alt_name}&page={page}',
      'by_date' => '?year={year}&month={month}&day={day}&altname={alt_name}&page={page}',
    ),
    'date' => '?year={year}&month={month}&day={day}',
    'date_page' => '?year={year}&month={month}&day={day}&cstart={page}',
    'year' => '?year={year}',
    'year_page' => '?year={year}&cstart={page}',
    'month' => '?year={year}&month={month}',
    'month_page' => '?year={year}&month={month}&cstart={page}',
    'user' => '?action=users&user={author}',
    'print' => 
    array (
      'by_cat' => 'engine/includes/print.php?category={catlink}&altname={alt_name}',
      'by_date' => 'engine/includes/print.php?year={year}&month={month}&day={day}&altname={alt_name}',
    ),
    'registration' => '?action=registration',
    'activation' => '?action=activation',
    'activation_do' => '?action=activation&userid={userid}&code={code}',
    'lostpassword' => '?action=lostpassword',
    'rss' => '?action=plugin&plugin=rss_export',
    'firstpage' => '?cstart=0',
    'page' => '?cstart={page}',
    'profile' => '?action=profile',
    'addnews' => '?action=addnews',
    'static' => '?action=static&altname={alt_name}',
  ),
);
?>