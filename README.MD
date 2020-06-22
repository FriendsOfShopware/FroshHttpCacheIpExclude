# Shopware HTTP Cache IP Filter/Exclude

[![Travis CI](https://travis-ci.org/FriendsOfShopware/FroshHttpCacheIpExclude.svg?branch=master)](https://travis-ci.org/FriendsOfShopware/FroshHttpCacheIpExclude)
[![Download @ Community Store](https://img.shields.io/badge/endpoint.svg?url=https://api.friendsofshopware.com/FroshHttpCacheIpExclude)](https://store.shopware.com/en/frosh65843297862f/http-cache-switch.html)
[![Join the chat at https://gitter.im/FriendsOfShopware/Lobby](https://badges.gitter.im/FriendsOfShopware/Lobby.svg)](https://gitter.im/FriendsOfShopware/Lobby)

This plugin configures Shopware to use a custom store for the HTTP Cache which includes an IP filter, effectively
skipping the HTTP Cache delivery for the provided IP addresses. Useful for testing/debugging in production environments, when
there is no alternative.

## Installation

* Clone this repository into a folder **FroshHttpCacheIpExclude** within the **custom/plugins** directory of the Shopware installation.
* Install the plugin through the Plugin-Manager within the Shopware backend.


### Install with composer
* Change to your root installation of shopware
* Run command `composer require frosh/http-cache-ip-exclude` and install and activate plugin with Plugin Manager 

## Usage

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
      'passedStoreClass' => NULL,
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
