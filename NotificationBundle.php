<?php

namespace IrishDan\NotificationBundle;

use IrishDan\NotificationBundle\DependencyInjection\AdapterPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NotificationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AdapterPass(),PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
    }
}
