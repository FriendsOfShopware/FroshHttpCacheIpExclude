<?php

namespace FroshHttpCacheIpExclude\Components;

use Shopware\Components\HttpCache\BlackHoleStore;
use Shopware\Components\HttpCache\Store;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class IpExcludeStore extends BlackHoleStore
{
    private $store;

    /**
     * @param array                                $options
     * @param \Shopware\Kernel|HttpKernelInterface $kernel
     */
    public function __construct(
        array $options,
        HttpKernelInterface $kernel
    ) {
        if (
            !empty($options['extended']['ipExcludes']) &&
            is_array($options['extended']['ipExcludes']) &&
            in_array($_SERVER['REMOTE_ADDR'], $options['extended']['ipExcludes'])
        ) {
            $this->store = $this;

            return;
        }

        if (!empty($options['extended']['passedStoreClass'])) {
            $class = $options['extended']['passedStoreClass'];

            $this->store = new $class($options, $kernel);
        } else {
            $this->store = new Store(
                $kernel->getCacheDir() . '/http_cache',
                $options['cache_cookies'],
                $options['lookup_optimization'],
                $options['ignored_url_parameters']
            );
        }
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->store->$name;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->store->$name = $value;
    }

    /**
     * @param string $name
     * @param mixed  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([
                $this->store,
                $name,
            ],
            $arguments
        );
    }
}
