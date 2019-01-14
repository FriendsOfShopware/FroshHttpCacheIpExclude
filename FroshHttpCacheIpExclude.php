<?php

namespace FroshHttpCacheIpExclude;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\UninstallContext;

/**
 * Class FroshHttpCacheIpExclude
 */
class FroshHttpCacheIpExclude extends Plugin
{
    const CONFIG_PHP_REQUIRE = "require_once __DIR__ . '/custom/plugins/FroshHttpCacheIpExclude/Components/IpExcludeStore.php';\r\n";

    const CONFIG_STORE_CLASS = 'FroshHttpCacheIpExclude\\Components\\IpExcludeStore';

    /**
     * @param ActivateContext $context
     *
     * @throws \Exception
     */
    public function activate(ActivateContext $context)
    {
        $this->addConfigStoreClass();
    }

    /**
     * @param DeactivateContext $context
     */
    public function deactivate(DeactivateContext $context)
    {
        $this->removeConfigStoreClass();
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        $this->removeConfigStoreClass();
    }

    private function getConfigPath()
    {
        return __DIR__ . '/../../../config.php';
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return require $this->getConfigPath();
    }

    /**
     * @throws \Exception
     */
    private function addConfigStoreClass()
    {
        $config = $this->getConfig();

        $configString = "<?php\r\n\r\n" . self::CONFIG_PHP_REQUIRE;

        $config['httpcache']['storeClass'] = self::CONFIG_STORE_CLASS;
        $config['httpcache']['extended'] = [
            'passedStoreClass' => '',
            'ipExcludes' => [],
            'paramExcludes' => [],
            'cookieExcludes' => [],
        ];

        $configString .= 'return ' . var_export($config, true) . ';';

        file_put_contents($this->getConfigPath(), $configString);
    }

    private function removeConfigStoreClass()
    {
        $config = $this->getConfig();

        $configString = "<?php\r\n\r\n";

        unset($config['httpcache']['storeClass']);
        unset($config['httpcache']['extended']);

        $configString .= 'return ' . var_export($config, true) . ';';

        file_put_contents($this->getConfigPath(), $configString);
    }
}
