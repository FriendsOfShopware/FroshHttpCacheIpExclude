# Shopware HTTP Cache IP Filter/Exclude

[![Join the chat at https://gitter.im/FriendsOfShopware/Lobby](https://badges.gitter.im/FriendsOfShopware/Lobby.svg)](https://gitter.im/FriendsOfShopware/Lobby)

This plugin configures Shopware to use a custom store for the HTTP Cache which includes an IP filter, effectively
skipping the HTTP Cache delivery for the provided IP addresses. Useful for testing/debugging in production environments, when
there is no alternative.

After the installation your `config.php` should look something like this:

```php
require_once __DIR__ . '/custom/plugins/FroshHttpCacheIpExclude/Components/IpExcludeStore.php';
return array (
  'db' => 
  array (
    // ...
  ),
  // ...
  'httpcache' => 
  array (
    'storeClass' => 'FroshHttpCacheIpExclude\\Components\\IpExcludeStore',
    'extended' => 
    array (
      'passedStoreClass' => '',
      'ipExcludes' => 
      array (
      ),
      'paramExcludes' => 
      array (
      ),
      'cookieExcludes' => 
      array (
      ),
    ),
  ),
);
```

Use the `passedStoreClass` property under `extended` to provide a custom store class that will then be passed through
the `IpExcludeStore`.

Additionally you can use `paramExcludes` to define a list of GET parameters
or `cookieExcludes` to define a list of cookies that, if set, will circumvent the cache.

Use the `ipExcludes` property under `extended` for an array of IP addresses that will subsequently not be served
cached pages.