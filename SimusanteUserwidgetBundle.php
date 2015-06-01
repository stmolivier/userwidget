<?php

namespace Simusante\UserwidgetBundle;

use Claroline\CoreBundle\Library\PluginBundle;
use Claroline\KernelBundle\Bundle\ConfigurationBuilder;

class SimusanteUserwidgetBundle extends PluginBundle
{
    public function hasMigrations()
    {
        return false;
    }

    public function getConfiguration($environment)
    {
        $config = new ConfigurationBuilder();

        return $config->addRoutingResource(__DIR__ . '/Resources/config/routing.yml', null, 'simuser');
    }
}