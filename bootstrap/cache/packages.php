<?php return array (
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'maatwebsite/excel' => 
  array (
    'providers' => 
    array (
      0 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
    ),
    'aliases' => 
    array (
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
    ),
  ),
  'overtrue/laravel-lang' => 
  array (
    'providers' => 
    array (
      0 => 'Overtrue\\LaravelLang\\TranslationServiceProvider',
    ),
  ),
  'overtrue/laravel-wechat' => 
  array (
    'providers' => 
    array (
      0 => 'Overtrue\\LaravelWeChat\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'EasyWeChat' => 'Overtrue\\LaravelWeChat\\Facade',
    ),
  ),
  'yansongda/laravel-pay' => 
  array (
    'providers' => 
    array (
      0 => 'Yansongda\\LaravelPay\\PayServiceProvider',
    ),
    'aliases' => 
    array (
      'Pay' => 'Yansongda\\LaravelPay\\Facades\\Pay',
    ),
  ),
);