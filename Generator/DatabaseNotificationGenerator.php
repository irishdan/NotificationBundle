<?php

namespace IrishDan\NotificationBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Generates a Notification inside a bundle.
 */
class DatabaseNotificationGenerator extends Generator
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param BundleInterface $bundle
     * @param                 $entityName
     */
    public function generate(BundleInterface $bundle, $entityName)
    {
        $bundleDir = $bundle->getPath();
        $notificationDir = $bundleDir . '/Entity';
        self::mkdir($notificationDir);

        $notificationFile = $notificationDir . '/' . $entityName . '.php';

        // Check that each file does not already exist
        if ($this->filesystem->exists($notificationFile)) {
            throw new \RuntimeException(sprintf('"%s" already exists', $notificationFile));
        }

        $parameters = [
            'namespace' => $bundle->getNamespace(),
            'class_name' => $entityName,
        ];

        // Set generator to look in correct directory for notifications template.
        $path = __DIR__ . '/../Resources/skeleton';
        $this->setSkeletonDirs([$path]);

        // Template, destination, params
        $this->renderFile('notification/DatabaseNotification.php.twig', $notificationFile, $parameters);
    }
}