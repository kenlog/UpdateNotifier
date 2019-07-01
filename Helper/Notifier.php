<?php

namespace Kanboard\Plugin\UpdateNotifier\Helper;

use Kanboard\Core\Base;
use Kanboard\Core\Plugin\Directory;
use Kanboard\Controller\PluginController;

class Notifier extends Base 
{
    /**
     * Returns URI Dashboard Controller
     *
     * @access private
     * @var string
     */
    private $dashboard = 'controller=DashboardController&action=show';
    
    /**
     * Returns URI Plugin Controller
     *
     * @access private
     * @var string
     */
    private $pluginDirectory = 'controller=PluginController&action=directory';

    /**
     * Show the notification for the plugin update
     *
     * @access public
     * @return string
     */
    public function renderUpdatePlugin()
    {
        
        if (($this->request->getQueryString() == $this->dashboard || $this->request->getQueryString() == $this->pluginDirectory) || (strpos($this->request->getUri(), 'directory') !== false || strpos($this->request->getUri(), 'dashboard') !== false)) {

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
                    if (version_compare($installed_plugins[$key]['version'], $available_plugins[$key]['version']) < 0) {
                        $message = t('New update:') . " " . ucfirst($key) ." v". $available_plugins[$key]['version'];
                        $anchorLink = ucfirst($key); 
                        return '<a href="?controller=PluginController&action=directory#'.$anchorLink.'">' . $message . '</a>';
                    }
                }
            }
        }
         
    }

    /**
     * Show notification for kanboard update
     *
     * @access public
     * @return string
     */
    public function renderUpdatekanboard()
    {
        $applications_version = str_replace('v', '', APP_VERSION);
        if (strpos(APP_VERSION, 'master') !== false && file_exists('ChangeLog')) { $applications_version = trim(file_get_contents('ChangeLog', false, null, 8, 6), ' '); }

        if (($this->request->getQueryString() == $this->dashboard || $this->request->getQueryString() == $this->pluginDirectory) || (strpos($this->request->getUri(), 'directory') !== false || strpos($this->request->getUri(), 'dashboard') !== false)) {
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
                        $latest = trim(preg_replace('/[A-Za-z]/', '', $value->title), ' ');
                        $clean_latest = preg_replace('/\s+/', '', $latest);
                        $clean_appversion = preg_replace('/\s+/', '', $applications_version);
                        if (version_compare($clean_appversion, $clean_latest, '<')) {
                            return '<a href="https://github.com/kanboard/kanboard/releases/latest" target="_blank">' . t('New version: Kanboard v') . trim(preg_replace('/[A-Za-z]/', '', $value->title), ' ') . '</a> ';
                        }

                        $i++;
                    }
                }
            }
        }
    }
}
