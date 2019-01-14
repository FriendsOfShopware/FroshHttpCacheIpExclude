<?php

class HttpCacheIpExclude extends Enlight_Components_Test_Controller_TestCase
{
    /**
     * @var \Shopware\Components\HttpCache\AppCache
     */
    public $kernel;

    /**
     * @throws Exception
     */
    public function testRegularCacheStore()
    {
        $this->doRequest();
        /** @var \FroshHttpCacheIpExclude\Components\IpExcludeStore $store */
        $store = $this->kernel->getStore();
        $this->assertTrue($store->store instanceof \Shopware\Components\HttpCache\Store);
    }

    /**
     * @throws Exception
     */
    public function testBlackHoleCacheStoreByParam()
    {
        $this->doRequest('/?preview');
        /** @var \FroshHttpCacheIpExclude\Components\IpExcludeStore $store */
        $store = $this->kernel->getStore();
        $this->assertTrue($store->store instanceof \Shopware\Components\HttpCache\BlackHoleStore);
    }

    /**
     * @param string $path
     *
     * @throws Exception
     */
    private function doRequest($path = '/')
    {
        $this->dispatch($path);

        $kernel = Shopware()->Container()->get('kernel');
        $options = $kernel->getHttpCacheConfig();
        $options['ignored_url_parameters'] = [];
        $this->kernel = new \Shopware\Components\HttpCache\AppCache($kernel, $options);

        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $this->kernel->handle($request);
    }
}
