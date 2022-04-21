<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Admin;

use Modules\Exchange\Models\InterfaceManager;
use Modules\Exchange\Models\NullInterfaceManager;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;
use phpOMS\Uri\HttpUri;

/**
 * Installer class.
 *
 * @package Modules\Exchange
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);

        $interfaces = \scandir(__DIR__ . '/Install/Interfaces');
        if ($interfaces === false) {
            return;
        }

        foreach ($interfaces as $interface) {
            if (!\is_dir(__DIR__ . '/Install/Interfaces/' . $interface)
                || $interface === '.'
                || $interface === '..'
            ) {
                continue;
            }

            self::createInterface($app, __DIR__ . '/Install/Interfaces/' . $interface);
        }
    }

    /**
     * Create workflow interface.
     *
     * @param ApplicationAbstract $app  Application
     * @param string              $path File path
     *
     * @return InterfaceManager
     *
     * @since 1.0.0
     */
    private static function createInterface(ApplicationAbstract $app, string $path) : InterfaceManager
    {
        /** @var \Modules\Exchange\Controller\ApiController $module */
        $module = $app->moduleManager->get('Exchange');

        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $contents = \file_get_contents($path . '/interface.json');
        if ($contents === false) {
            return new NullInterfaceManager();
        }

        $data = \json_decode($contents, true);
        if (!\is_array($data)) {
            return new NullInterfaceManager();
        }

        $request->header->account = 1;
        $request->setData('title', $data['name']);
        $request->setData('export', (int) $data['export']);
        $request->setData('import', (int) $data['import']);
        $request->setData('website', $data['website']);

        if (!\is_dir($tmpPath = __DIR__ . '/../../../temp/' . $data['name'] . '/')) {
            \mkdir($tmpPath, 0755, true);
        }

        $exchangeFiles = \scandir($path);
        if ($exchangeFiles === false) {
            return new NullInterfaceManager();
        }

        foreach ($exchangeFiles as $filePath) {
            if ($filePath === '..' || $filePath === '.') {
                continue;
            }

            if (\is_dir($path . '/' . $filePath)) {
                $subdir = \scandir($path . '/' . $filePath);
                if ($subdir === false) {
                    continue;
                }

                foreach ($subdir as $subPath) {
                    if (!\is_file($path . '/' . $filePath . '/' . $subPath)) {
                        continue;
                    }

                    \copy(
                        $path . '/' . $filePath . '/' . $subPath,
                        $tmpPath . $subPath
                    );

                    $request->addFile([
                        'error'    => \UPLOAD_ERR_OK,
                        'type'     => \substr($subPath, \strrpos($subPath, '.') + 1),
                        'name'     => $filePath . '/' . $subPath,
                        'tmp_name' => $tmpPath . $subPath,
                        'size'     => \filesize($tmpPath . $subPath),
                    ]);
                }
            } else {
                if (!\is_file($path . '/' . $filePath)) {
                    continue;
                }

                \copy($path . '/' . $filePath, $tmpPath . $filePath);

                $request->addFile([
                    'error'    => \UPLOAD_ERR_OK,
                    'type'     => \substr($filePath, \strrpos($filePath, '.') + 1),
                    'name'     => $filePath,
                    'tmp_name' => $tmpPath . $filePath,
                    'size'     => \filesize($tmpPath . $filePath),
                ]);
            }
        }

        $module->apiInterfaceInstall($request, $response);
        \rmdir($tmpPath);

        $interface = $response->get('')['response'];

        return $interface;
    }
}
