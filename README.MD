# Shopware HTTP Cache IP Filter/Exclude

[![Join the chat at https://gitter.im/FriendsOfShopware/Lobby](https://badges.gitter.im/FriendsOfShopware/Lobby.svg)](https://gitter.im/FriendsOfShopware/Lobby)

This plugin configures Shopware to use a custom store for the HTTP Cache which includes an IP filter, effectively
skipping the HTTP Cache delivery for the provided IP addresses. Useful for testing/debugging in production environments, when
there is no alternative.

After the installation your `config.php` should start like this:

```php
require_once __DIR__ . '/custom/plugins/FroshHttpCacheIpExclude/Components/IpExcludeStore.php';
return [
   'httpcache' => [
        'storeClass' => 'FroshHttpCacheIpExclude\\Components\\IpExcludeStore',
        'extended' => [
            'passedStoreClass' => '',
            'ipExcludes' => [],
        ],
    ],
    'db' => [
```

If the additional lines are missing this may be because you already had used `httpcache` property in your 
config file prior to the installation. Also note the use of short array syntax. The plugin looks for the line
`return [` and appends/prepends text to that line.

Use the `passedStoreClass` property under `extended` to provide a custom store class that will then be passed through
the `IpExcludeStore`.

Use the `ipExcludes` property under `extended` for an array of IP addresses that will subsequently not be served
cached pages.