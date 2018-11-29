<?php

namespace Kanboard\Plugin\UpdateNotifier\Helper;

use Kanboard\Core\Base;
use Kanboard\Core\Plugin\Directory;
use Kanboard\Controller\PluginController;

class Notifier extends Base 
{
    private $dashboard = 'controller=DashboardController&action=show';
    private $pluginDirectory = 'controller=PluginController&action=directory';

    public function renderUpdatePlugin()
    {
        if ($this->request->getQueryString() == $this->dashboard || $this->request->getQueryString() == $this->pluginDirectory) {

            $installed_plugins = array();

            foreach ($this->pluginLoader->getPlugins() as $plugin) {
                $installed_plugins[strtolower($plugin->getPluginName())] = array('version' => $plugin->getPluginVersion());
                
            }

            $available_plugins = Directory::getInstance($this->container)->getAvailablePlugins();
            
            foreach ($available_plugins as $key => $plugin) {
                unset($available_plugins[$key]['title']);
                unset($available_plugins[$key]['author']);
                unset($available_plugins[$key]['license']);
                unset($available_plugins[$key]['description']);
                unset($available_plugins[$key]['homepage']);
                unset($available_plugins[$key]['readme']);
                unset($available_plugins[$key]['download']);
                unset($available_plugins[$key]['remote_install']);
                unset($available_plugins[$key]['compatible_version']);
            }

            
            foreach($installed_plugins as $key => $value) {
                if (isset($installed_plugins[$key]['version']) && isset($available_plugins[$key]['version'])) {
                    if ($installed_plugins[$key]['version'] < $available_plugins[$key]['version']) {
                        $message = t('New update:') . " " . ucfirst($key) ." v". $available_plugins[$key]['version'];
                        $anchorLink = ucfirst($key); 
                        return '<a href="?controller=PluginController&action=directory#'.$anchorLink.'">' . $message . '</a>';
                    }
                }
            }
        }
         
    }

    public function renderUpdatekanboard()
    {
        if ($this->request->getQueryString() == $this->dashboard || $this->request->getQueryString() == $this->pluginDirectory) {
            $file = 'https://github.com/kanboard/kanboard/releases.atom';
            $file_headers = @get_headers($file);
            if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                return false;
            }
            else {
                $xml = simplexml_load_file($file);
                $i = 0;
                $length = count($xml->entry);
                foreach($xml->entry as $value) {
                    if ($i == 0) {
                        if (filter_var(APP_VERSION, FILTER_SANITIZE_NUMBER_INT) < filter_var($value->title, FILTER_SANITIZE_NUMBER_INT)) {
                            return '<a href="https://github.com/kanboard/kanboard/releases/latest" target="_blank">' . t('New version:') . " " . $value->title . '</a> ';
                        }

                        $i++;
                    }
                }
            }
        }
    }
}
