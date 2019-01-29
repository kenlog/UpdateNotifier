<!-- ADD <li> TO SIDEBAR FOR LATEST PLUGINS WILL GO TO CONTROLLER FIRST -->
<div class="sidebar">
    <ul>
        <li <?= $this->app->checkMenuSelection('PluginController', 'show') ?>>
            <?= $this->url->link(t('Installed Plugins'), 'PluginController', 'show') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('PluginController', 'directory') ?>>
            <?= $this->url->link(t('Plugin Directory'), 'PluginController', 'directory') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('PluginTimestampedController', 'show', ['plugin' => 'updatenotifier']) ?>>
            <?= $this->url->link(t('Latest Plugins'), 'PluginTimestampedController', 'show', ['plugin' => 'updatenotifier']) ?>
        </li>
    </ul>
</div>
