<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Exchange\Controller;

use Modules\Exchange\Models\ExchangeLogMapper;
use Modules\Exchange\Models\InterfaceManagerMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Exchange controller class.
 *
 * @package Modules\Exchange
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeLogList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-log-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        $view->data['logs'] = ExchangeLogMapper::getAll()
            ->with('exchange')
            ->with('createdBy')
            ->limit(50)
            ->paginate(
                'id',
                $request->getDataString('ptype') ?? '',
                $request->getDataInt('offset')
            )->executeGetArray();

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeLog(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-log');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        $log               = ExchangeLogMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $view->data['log'] = $log;

        return $view;
    }

    /**
     * Method which generates the export list view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeExportList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-export-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager[] $interfaces */
        $interfaces = InterfaceManagerMapper::getAll()->executeGetArray();

        $export = [];
        foreach ($interfaces as $interface) {
            if ($interface->hasExport) {
                $export[] = $interface;
            }
        }

        $view->data['interfaces'] = $export;

        return $view;
    }

    /**
     * Method which generates the import list view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeImportList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-import-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager[] $interfaces */
        $interfaces = InterfaceManagerMapper::getAll()->executeGetArray();

        $import = [];
        foreach ($interfaces as $interface) {
            if ($interface->hasImport) {
                $import[] = $interface;
            }
        }

        $view->data['interfaces'] = $import;

        return $view;
    }

    /**
     * Method which generates the export view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeExport(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('source/sources')
            ->with('settings')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        if ($interface->id === 0) {
            $response->header->status = RequestStatusCode::R_404;
            $view->setTemplate('/Web/Backend/Error/404');

            return $view;
        }

        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-export');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        $view->data['interface'] = $interface;
        $view->data['db']        = $this->app->dbPool->get();

        $lang = \is_file($langFile = $interface->source->getAbsolutePath() . '/'
                . '/Lang/' . $response->header->l11n->language . '.lang.php'
            ) ? include $langFile : [];

        $view->data['lang'] = $lang;

        return $view;
    }

    /**
     * Method which generates the import view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeImport(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('source/sources')
            ->with('settings')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        if ($interface->id === 0) {
            $response->header->status = RequestStatusCode::R_404;
            $view->setTemplate('/Web/Backend/Error/404');

            return $view;
        }

        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-import');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        $view->data['interface'] = $interface;
        $view->data['db']        = $this->app->dbPool->get();

        $lang = \is_file($langFile = $interface->source->getAbsolutePath() . '/'
                . '/Lang/' . $response->header->l11n->language . '.lang.php'
            ) ? include $langFile : [];

        $view->data['lang'] = $lang;

        return $view;
    }
}
