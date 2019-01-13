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
    const CONFIG_PHP_REQUIRE = 'require_once __DIR__ . \'/custom/plugins/FroshHttpCacheIpExclude/Components/IpExcludeStore.php\';';

    const CONFIG_STORE_CLASS = 'FroshHttpCacheIpExclude\\Components\\IpExcludeStore';

    const CONFIG_PHP_PROPERTY = "   'httpcache' => [
        'storeClass' => 'FroshHttpCacheIpExclude\\Components\\IpExcludeStore',
        'extended' => [
            'passedStoreClass' => '',
            'ipExcludes' => [],
        ],
    ],";

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

    private function hasConfigValue($value)
    {
        $config = require $this->getConfigPath();

        return isset($config[$value]);
    }

    private function getConfigContent()
    {
        return file_get_contents($this->getConfigPath());
    }

    /**
     * @throws \Exception
     */
    private function addConfigStoreClass()
    {
        $configContent = $this->getConfigContent();
        $search = 'return [';

        if (!$this->hasConfigValue('httpcache')) {
            $configContent = str_replace(
                $search,
                $search . "\r\n" . self::CONFIG_PHP_PROPERTY,
                $configContent
            );
        } else {
            $configContent = str_replace(
                '\'storeClass\' => null',
                '\'storeClass\' => \'' . self::CONFIG_STORE_CLASS . '\'',
                $configContent
            );
        }

        if (strpos($configContent, self::CONFIG_PHP_REQUIRE) === false) {
            $configContent = str_replace(
                $search,
                self::CONFIG_PHP_REQUIRE . "\r\n" . $search,
                $configContent
            );
        }

        file_put_contents($this->getConfigPath(), $configContent);
    }

    private function removeConfigStoreClass()
    {
        $configContent = $this->getConfigContent();

        $configContent = str_replace(
            self::CONFIG_PHP_REQUIRE,
            '',
            $configContent
        );

        $configContent = str_replace(
            "'" . self::CONFIG_STORE_CLASS . "'",
            'null',
            $configContent
        );

        file_put_contents($this->getConfigPath(), $configContent);
    }
}
