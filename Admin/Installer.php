<?php
/**
 * Karaka
 *
 * PHP Version 8.0
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
use Modules\Exchange\Models\InterfaceManagerMapper;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;
use phpOMS\System\File\Local\Directory;

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
    /*
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);


        $interfaces = Directory::list(__DIR__ . '/../Interfaces', '.*interface\.json', true);

        foreach ($interfaces as $interface) {
            $exchange = new InterfaceManager(__DIR__ . '/../Interfaces/' . $interface);
            $exchange->load();

            InterfaceManagerMapper::create()->execute($exchange);
        }
    }
    */
}
