<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeLogList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-log-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        if ($request->getData('ptype') === 'p') {
            $view->data['logs'] = ExchangeLogMapper::getAll()->where('id', $request->getDataInt('id') ?? 0, '<')->limit(25)->execute();
        } elseif ($request->getData('ptype') === 'n') {
            $view->data['logs'] = ExchangeLogMapper::getAll()->where('id', $request->getDataInt('id') ?? 0, '>')->limit(25)->execute();
        } else {
            $view->data['logs'] = ExchangeLogMapper::getAll()->where('id', 0, '>')->limit(25)->execute();
        }

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeLog(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
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
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeExportList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-export-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager[] $interfaces */
        $interfaces = InterfaceManagerMapper::getAll()->execute();

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
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeImportList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-import-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager[] $interfaces */
        $interfaces = InterfaceManagerMapper::getAll()->execute();

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
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeExport(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-export');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('settings')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['interface'] = $interface;
        $view->data['db']        = $this->app->dbPool->get();

        $lang = include $interface->source->getAbsolutePath()
            . $interface->source->name
            . '/Lang/' . $response->header->l11n->language . '.lang.php';

        $view->data['lang'] = $lang;

        return $view;
    }

    /**
     * Method which generates the import view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewExchangeImport(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-import');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response);

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get()
            ->with('source')
            ->with('settings')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['interface'] = $interface;
        $view->data['db']        = $this->app->dbPool->get();

        $lang = include $interface->source->getAbsolutePath()
            . $interface->source->name
            . '/Lang/' . $response->header->l11n->language . '.lang.php';

        $view->data['lang'] = $lang;

        return $view;
    }
}
