# WPF (Wordpress Plugin Framework)
Another Wordpress plugin building framework, made by a JS dev.

```bash
# install
composer require rgdevme/wordpress-plufing-framework
```

## Why?
This framework came as a solution to the daily necesity to build custom plug-ins for my clients.

The idea was to simplify the work and streamline the process of building a personalized interface to handle data at specific use-cases, while also keeping the learning curve to a minimum.

## How to use it?
Once installed, initialize the plugin in your main file, as such:

```php
$plugin = new Plugin([
  'plugin_path' => string ,
  'menu_page'   => new MenuPage([...]),
  'db'          => new DBTable([...]),
  'components'  => [...], // Anything extending Base, and not in the other props. 
]);
$plugin->init();
``` 

To initialize a component it's as easy as:
```php
$admin_view_main = new MenuPage([
  'name'     => 'HS Sync',
  'domain'   => BRADI,
  'html'     => new HTMLTemplate([
    'filepath'            => BRADI_SRC . 'inc/view-admin-main.php',
    'variables_callable'  => 'bradi_admin_main_variables',
  ]),
  'inject'   => [
    new Stylesheet([
      'url'   => BRADI_URL . 'src/inc/view-admin-main.css',
      'admin' => true
    ])
  ]
]);

if (!function_exists('bradi_admin_main_variables')) {
  function bradi_admin_main_variables()
  {
    return [
      'bradi_hs_key' => get_option('bradi_hs_key', ''),
    ];
  }
}
```