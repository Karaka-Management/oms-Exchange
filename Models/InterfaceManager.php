<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Models;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\Collection;
use Modules\Media\Models\NullCollection;

/**
 * ModuleInfo class.
 *
 * Handling the info files for modules
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://karaka.app
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
    public bool $hasImport = true;

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
     * @var ExchangeSettings[]
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
    public function __construct()
    {
        $this->createdBy = new NullAccount();
        $this->createdAt = new \DateTimeImmutable('now');
        $this->source    = new NullCollection();
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
}
