<?php

namespace IrishDan\NotificationBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Generates a Notification inside a bundle.
 */
class NotificationGenerator extends Generator
{
    /** @var Filesystem */
    private $filesystem;
    private $channels;
    private $rootDirectory;

    /**
     * NotificationGenerator constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem, array $channels, $rootDirectory)
    {
        $this->filesystem = $filesystem;
        $this->channels = $channels;
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * @param BundleInterface $bundle
     * @param                 $name
     */
    public function generate(BundleInterface $bundle, $name)
    {
        $bundleDir = $bundle->getPath();
        $notificationDir = $bundleDir . '/Notification';
        self::mkdir($notificationDir);

        $notificationClassName = $name . 'Notification';
        $notificationFile = $notificationDir . '/' . $notificationClassName . '.php';

        $parameters = [
            'namespace' => $bundle->getNamespace(),
            'class_name' => $notificationClassName,
            'name' => $name,
        ];

        // Build an array of files to be created
        $filesArray = [];
        $filesArray[] = [
            'notification/Notification.php.twig',
            $notificationFile,
            $parameters,
        ];

        // Generate the templates for each channel.
        $templateDir = $this->rootDirectory . '/Resources/NotificationBundle/views/' . $name . '/';
        foreach ($this->channels as $channel) {
            $channel = explode('_', $channel)[0];
            $destination = $templateDir . $channel . '.message.html.twig';

            $filesArray[] = [
                'message/' . $channel . '.message.html.twig',
                $destination,
                [],
            ];
        }

        if (!empty($filesArray)) {
            $this->generateFiles($filesArray);
        }
    }

    protected function generateFiles(array $files)
    {
        // Set generator to look in correct directory for notifications template.
        $path = __DIR__ . '/../Resources/skeleton';
        $this->setSkeletonDirs([$path]);

        // Check that each file does not already exist
        foreach ($files as $file) {
            if ($this->filesystem->exists($file[1])) {
                throw new \RuntimeException(sprintf('"%s" already exists', $file[1]));
            }
        }

        // Generate each file
        foreach ($files as $file) {
            // Template, destination, params
            $this->renderFile($file[0], $file[1], $file[2]);
        }
    }
}