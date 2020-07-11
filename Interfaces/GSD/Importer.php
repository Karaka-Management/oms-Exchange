<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Interfaces
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Exchange\Interfaces\GSD;

use Modules\Accounting\Models\CostCenter;
use Modules\Accounting\Models\CostCenterMapper;
use Modules\Accounting\Models\CostObject;
use Modules\Accounting\Models\CostObjectMapper;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDCostCenterMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDCostObjectMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDCustomerMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDSupplierMapper;
use Modules\Exchange\Models\ImporterAbstract;
use Modules\SupplierManagement\Models\Supplier;
use Modules\SupplierManagement\Models\SupplierMapper;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Message\RequestAbstract;

/**
 * GSD import class
 *
 * @package Modules\Exchange\Models\Interfaces\GSD
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
    private $remote = null;

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
        $this->importCostCenter($start, $end);
        $this->importCostObject($start, $end);
        $this->importCustomer($start, $end);
        $this->importSupplier($start, $end);
        $this->importArticle($start, $end);
        $this->importAccount($start, $end);
        $this->importInvoice($start, $end);
        $this->importPosting($start, $end);
        $this->importBatchPosting($start, $end);
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

        $this->remote = ConnectionFactory::create([
            'db'       => (string) ($request->getData('db') ?? ''),
            'host'     => (string) ($request->getData('host') ?? ''),
            'port'     => (int) ($request->getData('port') ?? 0),
            'database' => (string) ($request->getData('database') ?? ''),
            'login'    => (string) ($request->getData('login') ?? ''),
            'password' => (string) ($request->getData('password') ?? ''),
            'prefix'   => '',
        ]);

        if ($this->remote->getStatus() !== DatabaseStatus::OK) {
            return false;
        }

        if (((bool) ($request->getData('customers') ?? false))) {
            $this->importCustomer($start, $end);
        }

        if (((bool) ($request->getData('suppliers') ?? false))) {
            $this->importSupplier($start, $end);
        }

        if (((bool) ($request->getData('accounts') ?? false))) {
            $this->importAccount($start, $end);
        }

        if (((bool) ($request->getData('costcenters') ?? false))) {
            $this->importCostCenter($start, $end);
        }

        if (((bool) ($request->getData('costobjects') ?? false))) {
            $this->importCostObject($start, $end);
        }

        if (((bool) ($request->getData('articles') ?? false))) {
            $this->importArticle($start, $end);
        }

        if (((bool) ($request->getData('invoices') ?? false))) {
            $this->importInvoice($start, $end);
        }

        return true;
    }

    /**
     * Import cost centers
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importCostCenter(\DateTime $start, \DateTime $end) : void
    {
        DataMapperAbstract::setConnection($this->remote);
        $query = GSDCostCenterMapper::getQuery();
        $query->where('row_create_time', '=>', $start->format('Y-m-d H:i:s'))
            ->andWhere('row_create_time', '<=', $end->format('Y-m-d H:i:s'));

        $costCenters = GSDCostCenterMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($costCenters as $cc) {
            $obj = new CostCenter();
            $obj->setCode($cc->getCostCenter());
            $obj->setName($cc->getDescription());

            CostCenterMapper::create($obj);
        }
    }

    /**
     * Import cost objects
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importCostObject(\DateTime $start, \DateTime $end) : void
    {
        DataMapperAbstract::setConnection($this->remote);
        $query = GSDCostObjectMapper::getQuery();
        $query->where('row_create_time', '=>', $start->format('Y-m-d H:i:s'))
            ->andWhere('row_create_time', '<=', $end->format('Y-m-d H:i:s'));

        $costObjects = GSDCostObjectMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($costObjects as $co) {
            $obj = new CostObject();
            $obj->setCode($co->getCostObject());
            $obj->setName($co->getDescription());

            CostObjectMapper::create($obj);
        }
    }

    /**
     * Import customers
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importCustomer(\DateTime $start, \DateTime $end) : void
    {
        DataMapperAbstract::setConnection($this->remote);
        $query = GSDCustomerMapper::getQuery();
        $query->where('row_create_time', '=>', $start->format('Y-m-d H:i:s'))
            ->andWhere('row_create_time', '<=', $end->format('Y-m-d H:i:s'));

        $customers = GSDCustomerMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($customers as $customer) {
            $obj = new Client();
            $obj->setNumber($customer->getNumber());

            ClientMapper::create($obj);
        }
    }

    /**
     * Import suppliers
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importSupplier(\DateTime $start, \DateTime $end) : void
    {
        DataMapperAbstract::setConnection($this->remote);
        $query = GSDSupplierMapper::getQuery();
        $query->where('row_create_time', '=>', $start->format('Y-m-d H:i:s'))
            ->andWhere('row_create_time', '<=', $end->format('Y-m-d H:i:s'));

        $suppliers = GSDSupplierMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($suppliers as $supplier) {
            $obj = new Supplier();
            $obj->setNumber($supplier->getNumber());

            SupplierMapper::create($obj);
        }
    }

    /**
     * Import accounts
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importAccount(\DateTime $start, \DateTime $end) : void
    {
    }

    /**
     * Import articles
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importArticle(\DateTime $start, \DateTime $end) : void
    {
    }

    /**
     * Import invoices
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importInvoice(\DateTime $start, \DateTime $end) : void
    {
    }

    /**
     * Import postings
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importPosting(\DateTime $start, \DateTime $end) : void
    {
    }

    /**
     * Import batch postings
     *
     * @param \DateTime $start Start time (inclusive)
     * @param \DateTime $end   End time (inclusive)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importBatchPosting(\DateTime $start, \DateTime $end) : void
    {
    }
}
