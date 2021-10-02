<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
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
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
    public function viewExchangeLogList(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-log-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response));

        if ($request->getData('ptype') === 'p') {
            $view->setData('logs',
                ExchangeLogMapper::getBeforePivot((int) ($request->getData('id') ?? 0), limit: 25, depth: 4)
            );
        } elseif ($request->getData('ptype') === 'n') {
            $view->setData('logs',
                ExchangeLogMapper::getAfterPivot((int) ($request->getData('id') ?? 0), limit: 25, depth: 4)
            );
        } else {
            $view->setData('logs',
                ExchangeLogMapper::getAfterPivot(0, limit: 25, depth: 4)
            );
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
    public function viewExchangeLog(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-log');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response));

        $log = ExchangeLogMapper::get((int) $request->getData('id'));
        $view->setData('log', $log);

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
    public function viewExchangeExportList(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-export-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response));

        /** @var \Modules\Exchange\Models\InterfaceManager[] $interfaces */
        $interfaces = InterfaceManagerMapper::getAll();

        $export = [];
        foreach ($interfaces as $interface) {
            if ($interface->hasExport()) {
                $export[] = $interface;
            }
        }

        $view->addData('interfaces', $export);

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
    public function viewExchangeImportList(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-import-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response));

        /** @var \Modules\Exchange\Models\InterfaceManager[] $interfaces */
        $interfaces = InterfaceManagerMapper::getAll();

        $import = [];
        foreach ($interfaces as $interface) {
            if ($interface->hasImport()) {
                $import[] = $interface;
            }
        }

        $view->addData('interfaces', $import);

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
    public function viewExchangeExport(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-export');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response));

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get((int) $request->getData('id'));

        $view->addData('interface', $interface);

        $lang = include __DIR__ . '/../Interfaces/' . $interface->getInterfacePath() . '/Lang/' . $response->getLanguage() . '.lang.php';
        $view->addData('lang', $lang);

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
    public function viewExchangeImport(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Exchange/Theme/Backend/exchange-import');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007001001, $request, $response));

        /** @var \Modules\Exchange\Models\InterfaceManager $interface */
        $interface = InterfaceManagerMapper::get((int) $request->getData('id'));

        $view->addData('interface', $interface);

        $lang = include __DIR__ . '/../Interfaces/' . $interface->getInterfacePath() . '/Lang/' . $response->getLanguage() . '.lang.php';
        $view->addData('lang', $lang);

        return $view;
    }
}
