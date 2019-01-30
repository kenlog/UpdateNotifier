<?php

namespace Kanboard\Plugin\UpdateNotifier\Controller;

use Kanboard\Plugin\UpdateNotifier\Model\PluginTimestampedModel;
use Kanboard\Core\Plugin\Directory;
use Kanboard\Core\Plugin\Installer;
use Kanboard\Core\Plugin\PluginInstallerException;
use Kanboard\Controller\BaseController;

class PluginTimestampedController extends BaseController
{
    public function show()
    {
        $set_plugins = $this->configModel->get('plugins_are_set', 0);
        
        $availablePlugins = Directory::getInstance($this->container)->getAvailablePlugins();
        $installedPlugins = array();
        
        foreach ($this->pluginLoader->getPlugins() as $plugin) {
            $installedPlugins[$plugin->getPluginName()] = $plugin->getPluginVersion();
        }
        
        if ($set_plugins == 1) {
            foreach ($availablePlugins as $name => $value) {
                $this->pluginTimestampedModel->save($name);
            }
        } else {
            foreach ($availablePlugins as $name => $value) {
                $this->pluginTimestampedModel->saveWithoutTimestamp($name);
            }
        }
        
        $this->configModel->save(array('plugins_are_set' => 1));
        
        $onlyTenLatestPlugins = $this->pluginTimestampedModel->getTen();
        
        foreach ($availablePlugins as $name => $value) {
            if (!in_array($name, $onlyTenLatestPlugins, true)) { unset($availablePlugins[$name]); }
        }
        
        $this->response->html($this->helper->layout->plugin('UpdateNotifier:plugin/latest_plugins', array(
           'installed_plugins' => $installedPlugins,
            'available_plugins' => $availablePlugins,
            'title' => t('Latest Plugins'),
            'is_configured' => Installer::isConfigured(),
        )));
    }

}
