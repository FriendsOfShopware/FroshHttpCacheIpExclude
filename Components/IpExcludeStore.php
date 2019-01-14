<?php

namespace FroshHttpCacheIpExclude\Components;

use Shopware\Components\HttpCache\BlackHoleStore;
use Shopware\Components\HttpCache\Store;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class IpExcludeStore implements StoreInterface
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
        if ($this->useBlackHoleStore($options)) {
            $this->store = new BlackHoleStore();

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

    /**
     * @inheritdoc
     */
    public function lookup(Request $request)
    {
        return $this->store->lookup($request);
    }

    /**
     * @inheritdoc
     */
    public function write(Request $request, Response $response)
    {
        return $this->store->write($request, $response);
    }

    /**
     * @inheritdoc
     */
    public function invalidate(Request $request)
    {
        $this->store->invalidate($request);
    }

    /**
     * @inheritdoc
     */
    public function lock(Request $request)
    {
        return $this->store->lock($request);
    }

    /**
     * @inheritdoc
     */
    public function unlock(Request $request)
    {
        return $this->store->unlock($request);
    }

    /**
     * @inheritdoc
     */
    public function isLocked(Request $request)
    {
        return $this->store->isLocked($request);
    }

    /**
     * @inheritdoc
     */
    public function purge($url)
    {
        return $this->store->purge($url);
    }

    /**
     * @inheritdoc
     */
    public function cleanup()
    {
        $this->store->cleanup();
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    private function useBlackHoleStore(array $options)
    {
        if (
            !empty($options['extended']['ipExcludes']) &&
            is_array($options['extended']['ipExcludes']) &&
            in_array($_SERVER['REMOTE_ADDR'], $options['extended']['ipExcludes'])
        ) {
            return true;
        }

        if (
            !empty($options['extended']['paramExcludes']) &&
            is_array($options['extended']['paramExcludes'])
        ) {
            foreach ($options['extended']['paramExcludes'] as $param) {
                if (isset($_GET[$param])) {
                    return true;
                }
            }
        }

        if (
            !empty($options['extended']['cookieExcludes']) &&
            is_array($options['extended']['cookieExcludes'])
        ) {
            foreach ($options['extended']['cookieExcludes'] as $cookie) {
                if (isset($_COOKIE[$cookie])) {
                    return true;
                }
            }
        }

        return false;
    }
}
