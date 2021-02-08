<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Interfaces
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Exchange\Interfaces\OMS;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Message\RequestAbstract;
use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\IO\Zip\Zip;
use Modules\Media\Controller\ApiController;
use Modules\Exchange\Models\ImporterAbstract;

/**
 * OMS import class
 *
 * @package Modules\Exchange\Models\Interfaces\OMS
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Importer extends ImporterAbstract
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    private ConnectionAbstract $remote;

    /**
     * Account
     *
     * @var int
     * @since 1.0.0
     */
    private int $account = 1;

    /**
     * Import all data in time span
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function import(\DateTime $start, \DateTime $end) : void
    {
        $this->importLanguage();
    }

    /**
     * Import data from request
     *
     * @param RequestAbstract $request Request
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function importFromRequest(RequestAbstract $request) : bool
    {
        $start = new \DateTime($request->getData('start') ?? 'now');
        $end   = new \DateTime($request->getData('end') ?? 'now');

        if ($request->getData('db') !== null) {
            $this->remote = ConnectionFactory::create([
                'db'             => (string) ($request->getData('db') ?? ''),
                'host'           => (string) ($request->getData('host') ?? ''),
                'port'           => (int) ($request->getData('port') ?? 0),
                'database'       => (string) ($request->getData('database') ?? ''),
                'login'          => (string) ($request->getData('login') ?? ''),
                'password'       => (string) ($request->getData('password') ?? ''),
                'datetimeformat' => (string) ($request->getData('datetimeformat') ?? 'Y-m-d H:i:s'),
            ]);

            $this->remote->connect();

            if ($this->remote->getStatus() !== DatabaseStatus::OK) {
                return false;
            }
        }

        $this->account = $request->header->account;

        if ($request->getData('type') === 'language') {
            $this->importLanguage($request);
        }

        return true;
    }

    /**
     * Import language
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importLanguage(RequestAbstract $request) : void
    {
        $upload = ApiController::uploadFilesToDestination($request->getFiles());

        $fp = \fopen($upload['file0']['path'] . '/' . $upload['file0']['filename'], 'r');
        $header = \fgetcsv($fp, ';', '"');

        $languageArray = [];
        $supportedLanguages = \array_slice($header, 3);

        while(($line = \fgetcsv($fp, ';', '"')) !== false) {
            $translations = \array_slice($header, 3);

            foreach ($languageArray as $index => $language) {
                if (empty(\trim($language))) {
                    continue;
                }

                $languageArray[\trim($line[0])][\trim($line[1])][\trim($line[2])][\trim($language)] = $translations[$index];
            }
        }

        \fclose($fp);

        \unlink($upload['file0']['path'] . '/' . $upload['file0']['filename']);

        foreach ($languageArray as $module => $themes) {
            foreach ($themes as $theme => $keys) {
                foreach ($supportedLanguages as $language) {
                    $langFile = __DIR__ . '/../../../' . \trim($module) . '/Theme/' . $theme . '/Lang/' . \trim($language) . '.lang.php';
                    if (\is_file($langFile)) {
                        \unlink($langFile);
                    }

                    $fp = \fopen($langFile, 'w+');
                    \fwrite($fp,
                        "<?php\n"
                        . "/**\n"
                        . " * Orange Management\n"
                        . " *\n"
                        . " * PHP Version 8.0\n"
                        . " *\n"
                        . " * @copyright Dennis Eichhorn\n"
                        . " * @license   OMS License 1.0\n"
                        . " * @version   1.0.0\n"
                        . " * @link      https://orange-management.org\n"
                        . " */\n"
                        . "declare(strict_types=1);\n\n"
                        . "return [\'' . '\'] => [\n"
                    );

                    foreach ($keys as $key => $values) {
                        \fwrite($fp,
                            "    '" . $key . "' => '" . ($values[$language] ?? '') . "',\n"
                        );
                    }

                    \fwrite($fp, "]];\n");
                    \fclose($fp);
                }
            }
        }
    }
}
