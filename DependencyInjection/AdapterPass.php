<?php


namespace IrishDan\NotificationBundle\DependencyInjection;

use IrishDan\NotificationBundle\Adapter\MessageAdapterInterface;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
// use App\Mail\TransportChain;

class AdapterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('notification.channel_subscriber')) {
            return;
        }

        // Add all adapters to the subscriber so ot can handle
        // any type of message.
        $subscriberDefinition = $container->findDefinition('notification.channel_subscriber');

        // find all service IDs with the app.mail_transport tag.
        // @TODO: Inject only the adapters that are used
        $taggedServices = $container->findTaggedServiceIds('notification.adapter');

        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($def->getClass());

            if (!is_subclass_of($class, MessageAdapterInterface::class)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, MakerInterface::class));
            }

            $subscriberDefinition->addMethodCall('addAdapter', [$id, new Reference($id)]);
        }
    }
}