
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
        $availablePlugins = Directory::getInstance($this->container)->getAvailablePlugins();
        $installedPlugins = array();
        
        foreach ($this->pluginLoader->getPlugins() as $plugin) {
            $installedPlugins[$plugin->getPluginName()] = $plugin->getPluginVersion();
        }
        
        foreach ($availablePlugins as $name => $value) {
            $this->pluginTimestampedModel->save($name);
        }
        
        $onlyTenLatestPlugins = $this->pluginTimestampedModel->getTen();
        
        foreach ($availablePlugins as $name => $value) {
            if (!in_array($name, $onlyTenLatestPlugins)) { unset($availablePlugins[$name]); }
        }
        
        $this->response->html($this->helper->layout->plugin('updatenotifier:plugin/latest_plugins', array(
           'installed_plugins' => $installedPlugins,
            'available_plugins' => $availablePlugins,
            'title' => t('Plugin Directory'),
            'is_configured' => Installer::isConfigured(),
        )));
    }

}
