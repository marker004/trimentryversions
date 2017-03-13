<?php
namespace Craft;

class TrimEntryVersionsPlugin extends BasePlugin
{
    public function getName()
    {
        return 'Trim Entry Versions';
    }

    public function getVersion()
    {
        return '0.0.1';
    }

    public function getSchemaVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'MilesHerndon';
    }

    public function getDeveloperUrl()
    {
        return 'https://github.com/milesherndon';
    }

    public function getSettingsUrl()
    {
        return 'settings/plugins/trimentryversions/index';
    }

    public function registerCpRoutes()
    {
        return array(
            'settings/plugins/trimentryversions/index' => 'trimentryversions/_settings',
        );
    }
}
