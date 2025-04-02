<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Interfaces
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Interface;

use Modules\Exchange\Models\ExchangeSettingMapper;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\RequestAbstract;

/**
 * Exchange trait
 *
 * @package Interfaces
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
trait ExchangeTrait
{
    /**
     * Import data from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function exchangeFromRequest(RequestAbstract $request) : array
    {
        /** @var \Modules\Exchange\Models\ExchangeSetting $setting */
        $setting = ExchangeSettingMapper::get()
            ->where('id', (int) $request->getData('setting'))
            ->execute();

        $settingData = $setting->getData();

        $lang             = [];
        $lang['Exchange'] = include __DIR__ . '/Lang/' . $request->header->l11n->language . '.lang.php';

        $this->l11n->loadLanguage($request->header->l11n->language, 'Exchange', $lang);

        $importConnection = ($settingData['import']['db']['self'] ?? true)
            ? $this->local ?? new NullConnection()
            : ConnectionFactory::create([
                'db'       => $settingData['import']['db']['db'],
                'host'     => $settingData['import']['db']['host'],
                'port'     => $settingData['import']['db']['port'],
                'database' => $settingData['import']['db']['database'],
                'login'    => $settingData['import']['db']['login'],
                'password' => $settingData['import']['db']['password'],
            ]
        );
        $exportConnection = ($settingData['export']['db']['self'] ?? true)
            ? $this->remote ?? new NullConnection()
            : ConnectionFactory::create([
                'db'       => $settingData['export']['db']['db'],
                'host'     => $settingData['export']['db']['host'],
                'port'     => $settingData['export']['db']['port'],
                'database' => $settingData['export']['db']['database'],
                'login'    => $settingData['export']['db']['login'],
                'password' => $settingData['export']['db']['password'],
            ]
        );

        $relations = $setting->getRelations();
        foreach ($relations as $table) {
            $importQuery = new Builder($importConnection);
            $importQuery->from($table['src']);

            $exportQuery = new Builder($exportConnection);
            $exportQuery->into($table['dest']);

            $importFields = [];
            $exportFields = [];

            foreach ($table['match'] as $match) {
                $importFields[] = $match['src_field']['column'];
                $exportFields[] = $match['dest_field']['column'];
            }

            if (\count($importFields) !== \count($exportFields)) {
                continue;
            }

            $importQuery->select(...$importFields);

            if (!empty($column = $request->getDataString('filter1_column'))
                && !empty($value = $request->getDataString('filter1_value'))
            ) {
                $importQuery->where($column, $request->getDataString('filter1_operator') ?? '=', $value);
            }

            if (!empty($column = $request->getDataString('filter2_column'))
                && !empty($value = $request->getDataString('filter2_value'))
            ) {
                $importQuery->where($column, $request->getDataString('filter2_operator') ?? '=', $value);
            }

            $importData = $importQuery->execute();

            if (empty($importData)) {
                continue;
            }

            $exportQuery->insert(...$exportFields);
            foreach ($importData as $data) {
                $exportQuery->values(...$data);
            }

            $exportQuery->execute();
        }

        return [];
    }
}
