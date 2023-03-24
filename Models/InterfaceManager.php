<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\Collection;
use Modules\Media\Models\NullCollection;
use phpOMS\System\File\PathException;

/**
 * ModuleInfo class.
 *
 * Handling the info files for modules
 *
 * @package phpOMS\Module
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class InterfaceManager
{
    /**
     * Interface ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * File path.
     *
     * @var string
     * @since 1.0.0
     */
    private string $path = '';

    /**
     * Title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Interface Version.
     *
     * @var string
     * @since 1.0.0
     */
    public string $version = '';

    /**
     * Interface Website.
     *
     * @var string
     * @since 1.0.0
     */
    public string $website = '';

    /**
     * Export.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasExport = false;

    /**
     * Import.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasImport = false;

    /**
     * Creator.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $createdBy;

    /**
     * Created.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Template source.
     *
     * @var Collection
     * @since 1.0.0
     */
    public Collection $source;

    /**
     * Settings.
     *
     * @var ExchangeSetting[]
     * @since 1.0.0
     */
    protected array $settings = [];

    /**
     * Object constructor.
     *
     * @param string $path Info file path
     *
     * @since 1.0.0
     */
    public function __construct(string $path = '')
    {
        $this->path      = $path;
        $this->createdBy = new NullAccount();
        $this->createdAt = new \DateTimeImmutable();
        $this->source    = new NullCollection();
    }

    /**
     * Load info data from path.
     *
     * @return void
     *
     * @throws PathException this exception is thrown in case the info file path doesn't exist
     *
     * @since 1.0.0
     */
    public function load() : void
    {
        if (!\is_file($this->path)) {
            throw new PathException($this->path);
        }

        $contents = \file_get_contents($this->path);

        /** @var array $info */
        $info = \json_decode($contents === false ? '[]' : $contents, true);
        if ($info === false) {
            return;
        }

        $this->title     = $info['name'];
        $this->version   = $info['version'];
        $this->website   = $info['website'];
        $this->hasExport = $info['export'];
        $this->hasImport = $info['import'];
    }

    /**
     * Get info path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get id
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get settings.
     *
     * @return ExchangeSetting[]
     *
     * @since 1.0.0
     */
    public function getSettings() : array
    {
        return $this->settings;
    }

    /**
     * Adding new setting.
     *
     * @param ExchangeSetting $setting Setting
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function addSetting(ExchangeSetting $setting) : int
    {
        $this->settings[] = $setting;

        \end($this->settings);
        $key = (int) \key($this->settings);
        \reset($this->settings);

        return $key;
    }

    /**
     * Get array
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function toArray() : array
    {
        return [
            'id'        => $this->id,
            'path'      => $this->path,
            'title'     => $this->title,
            'version'   => $this->version,
            'website'   => $this->website,
            'hasExport' => $this->hasExport,
            'hasImport' => $this->hasImport,
        ];
    }
}
