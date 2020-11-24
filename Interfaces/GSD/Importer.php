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
use Modules\Admin\Models\Account;
use Modules\Admin\Models\Address;
use Modules\Admin\Models\NullAccount;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDArticle;
use Modules\Exchange\Interfaces\GSD\Model\GSDArticleMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDCostCenter;
use Modules\Exchange\Interfaces\GSD\Model\GSDCostCenterMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDCostObject;
use Modules\Exchange\Interfaces\GSD\Model\GSDCostObjectMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDCustomer;
use Modules\Exchange\Interfaces\GSD\Model\GSDCustomerMapper;
use Modules\Exchange\Interfaces\GSD\Model\GSDSupplier;
use Modules\Exchange\Interfaces\GSD\Model\GSDSupplierMapper;
use Modules\Exchange\Models\ImporterAbstract;
use Modules\ItemManagement\Models\Item;
use Modules\ItemManagement\Models\ItemAttributeType;
use Modules\ItemManagement\Models\ItemAttributeTypeL11n;
use Modules\ItemManagement\Models\ItemAttributeTypeL11nMapper;
use Modules\ItemManagement\Models\ItemAttributeTypeMapper;
use Modules\ItemManagement\Models\ItemAttributeValue;
use Modules\ItemManagement\Models\ItemL11n;
use Modules\ItemManagement\Models\ItemL11nType;
use Modules\ItemManagement\Models\ItemL11nTypeMapper;
use Modules\ItemManagement\Models\ItemMapper;
use Modules\ItemManagement\Models\NullItemAttributeType;
use Modules\ItemManagement\Models\NullItemL11nType;
use Modules\Media\Controller\Controller;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaMapper;
use Modules\Profile\Models\ContactElement;
use Modules\Profile\Models\ContactType;
use Modules\Profile\Models\Profile;
use Modules\SupplierManagement\Models\Supplier;
use Modules\SupplierManagement\Models\SupplierMapper;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Message\RequestAbstract;
use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\IO\Zip\Zip;

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
            'db'             => (string) ($request->getData('db') ?? ''),
            'host'           => (string) ($request->getData('host') ?? ''),
            'port'           => (int) ($request->getData('port') ?? 0),
            'database'       => (string) ($request->getData('database') ?? ''),
            'login'          => (string) ($request->getData('login') ?? ''),
            'password'       => (string) ($request->getData('password') ?? ''),
            'datetimeformat' => (string) ($request->getData('datetimeformat') ?? 'Y-m-d H:i:s'),
        ]);

        $this->remote->connect();

        $this->account = $request->header->account;

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
            $this->importArticle($start, $end, $request->getFiles()['articles_backend'] ?? []);
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
        $query->where('FiKostenstellen_3.row_create_time', '>=', $start)
            ->andWhere('FiKostenstellen_3.row_create_time', '<=', $end);

        /** @var GSDCostCenter[] $costCenters */
        $costCenters = GSDCostCenterMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($costCenters as $cc) {
            $obj = new CostCenter();
            $obj->code = $cc->getCostCenter();
            $obj->l11n->name = \trim($cc->description, " ,\t");

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
        $query->where('FiKostentraeger_3.row_create_time', '>=', $start)
            ->andWhere('FiKostentraeger_3.row_create_time', '<=', $end);

        /** @var GSDCostObject[] $costObjects */
        $costObjects = GSDCostObjectMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($costObjects as $co) {
            $obj = new CostObject();
            $obj->code = $co->getCostObject();
            $obj->l11n->name = \trim($co->description, " ,\t");

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
        $query->where('Kunden_3.row_create_time', '>=', $start)
            ->andWhere('Kunden_3.row_create_time', '<=', $end);

        /** @var GSDCustomer[] $customers */
        $customers = GSDCustomerMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($customers as $customer) {
            $account = new Account();
            $account->name1 = \trim($customer->addr->name1, " ,\t");

            $a = \trim($customer->addr->name2, " ,\t");
            $b = \trim($customer->addr->name3, " ,\t");
            $account->name2 = \trim($a . ' ' . $b);

            $profile = new Profile($account);

            $obj = new Client();
            $obj->number = \trim($customer->number, "., \t");
            $obj->profile = $profile;

            $addr = new Address();
            $addr->address = \trim($customer->addr->street, ", \t");
            $addr->postal = \trim($customer->addr->zip, ",. \t");
            $addr->city = \trim($customer->addr->city, ",. \t");
            $addr->setCountry(ISO3166TwoEnum::_DEU);
            $obj->setMainAddress($addr);

            if (!empty(\trim($customer->addr->phone, ",. \t"))) {
                $phone = new ContactElement();
                $phone->setType(ContactType::PHONE);
                $phone->setSubtype(0);
                $phone->setContent(\trim($customer->addr->phone, ",. \t"));
                $obj->addContactElement($phone);
            }

            if (!empty(\trim($customer->addr->website, ",. \t"))) {
                $website = new ContactElement();
                $website->setType(ContactType::WEBSITE);
                $website->setSubtype(0);
                $website->setContent(\trim($customer->addr->website, ",. \t"));
                $obj->addContactElement($website);
            }

            if (!empty(\trim($customer->addr->fax, ",. \t"))) {
                $fax = new ContactElement();
                $fax->setType(ContactType::FAX);
                $fax->setSubtype(0);
                $fax->setContent(\trim($customer->addr->fax, ",. \t"));
                $obj->addContactElement($fax);
            }

            if (!empty(\trim($customer->addr->email, ",. \t"))) {
                $email = new ContactElement();
                $email->setType(ContactType::EMAIL);
                $email->setSubtype(0);
                $email->setContent(\trim($customer->addr->email, ",. \t"));
                $obj->addContactElement($email);
            }

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
        $query->where('Lieferanten_3.row_create_time', '>=', $start)
            ->andWhere('Lieferanten_3.row_create_time', '<=', $end);

        /** @var GSDSupplier[] $suppliers */
        $suppliers = GSDSupplierMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        foreach ($suppliers as $supplier) {
            $account = new Account();
            $account->name1 = \trim($supplier->addr->name1, " ,\t");

            $a = \trim($supplier->addr->name2, " ,\t");
            $b = \trim($supplier->addr->name3, " ,\t");
            $account->name2 = \trim($a . ' ' . $b);

            $profile = new Profile($account);

            $obj = new Supplier();
            $obj->number = \trim($supplier->number, "., \t");
            $obj->profile = $profile;

            $addr = new Address();
            $addr->address = \trim($supplier->addr->street, ", \t");
            $addr->postal = \trim($supplier->addr->zip, ",. \t");
            $addr->city = \trim($supplier->addr->city, ",. \t");
            $addr->setCountry(ISO3166TwoEnum::_DEU);
            $obj->setMainAddress($addr);

            if (!empty(\trim($supplier->addr->phone, ",. \t"))) {
                $phone = new ContactElement();
                $phone->setType(ContactType::PHONE);
                $phone->setSubtype(0);
                $phone->setContent(\trim($supplier->addr->phone, ",. \t"));
                $obj->addContactElement($phone);
            }

            if (!empty(\trim($supplier->addr->website, ",. \t"))) {
                $website = new ContactElement();
                $website->setType(ContactType::WEBSITE);
                $website->setSubtype(0);
                $website->setContent(\trim($supplier->addr->website, ",. \t"));
                $obj->addContactElement($website);
            }

            if (!empty(\trim($supplier->addr->fax, ",. \t"))) {
                $fax = new ContactElement();
                $fax->setType(ContactType::FAX);
                $fax->setSubtype(0);
                $fax->setContent(\trim($supplier->addr->fax, ",. \t"));
                $obj->addContactElement($fax);
            }

            if (!empty(\trim($supplier->addr->email, ",. \t"))) {
                $email = new ContactElement();
                $email->setType(ContactType::EMAIL);
                $email->setSubtype(0);
                $email->setContent(\trim($supplier->addr->email, ",. \t"));
                $obj->addContactElement($email);
            }

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
     * @param array     $files Article images
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importArticle(\DateTime $start, \DateTime $end, array $files = []) : void
    {
        DataMapperAbstract::setConnection($this->remote);
        $query = GSDArticleMapper::getQuery();
        $query->where('Artikel_3.row_create_time', '>=', $start)
            ->andWhere('Artikel_3.row_create_time', '<=', $end);

        /** @var GSDArticle[] $articles */
        $articles = GSDArticleMapper::getAllByQuery($query);

        DataMapperAbstract::setConnection($this->local);

        $itemL11nType  = $this->createItemL11nTypes();
        $itemAttrType  = $this->createItemAttributeTypes();
        $itemAttrValue = $this->createItemAttributeValues($itemAttrType);

        $images    = [];
        $imagePath = Controller::FILE_PATH . '/Modules/ItemManagement/Articles/Images';

        /** @var Media[] $media */
        $media = [];
        if (!empty($files)) {
            if (!\is_dir($imagePath)) {
                \mkdir($imagePath, 0755, true);
            }

            $jpgOld    = Directory::listByExtension($imagePath, 'jpg');
            $pngOld    = Directory::listByExtension($imagePath, 'png');
            $imagesOld = \array_merge($jpgOld, $pngOld);

            Zip::unpack($files['tmp_name'], $imagePath);

            $jpg    = Directory::listByExtension($imagePath, 'jpg');
            $png    = Directory::listByExtension($imagePath, 'png');
            $images = \array_merge($jpg, $png);
            $images = \array_diff($images, $imagesOld);

            /** @var string[] $images */
            /** @var string $image */
            foreach ($images as $image) {
                $number = (int) \explode('.', $image)[0];

                $media[$number] = new Media();
                $media[$number]->name = (string) $number;
                $media[$number]->type = 'backend_image';
                $media[$number]->setPath('/Modules/Media/Files/Modules/ItemManagement/Articles/Images/' . $image);
                $media[$number]->setVirtualPath('/Modules/ItemManagement/Articles/Images');
                $media[$number]->extension = \explode('.', $image)[1];
                $media[$number]->size = (int) \filesize($imagePath . '/' . $image);
                $media[$number]->createdBy = new NullAccount($this->account);

                MediaMapper::create($media[$number]);
            }
        }

        //$itemAttrType['segment'] = new ItemAttributeType();
        //$itemAttrType['productgroup'] = new ItemAttributeType();
        //$itemAttrType['devaluation'] = new ItemAttributeType();

        foreach ($articles as $article) {
            $number = (int) \trim($article->number, ",. \t");

            $obj = new Item();
            $obj->number = (string) $number;

            // German Language
            $obj->addL11n(new ItemL11n(
                $itemL11nType['name1']->getId(),
                \trim($article->name1, " ,\t"),
                ISO639x1Enum::_DE
            ));

            $obj->addL11n(new ItemL11n(
                $itemL11nType['name2']->getId(),
                \trim($article->name2, " ,\t"),
                ISO639x1Enum::_DE
            ));

            $obj->addL11n(new ItemL11n(
                $itemL11nType['info']->getId(),
                \trim($article->infoSales, " ,\t"),
                ISO639x1Enum::_DE
            ));

            // English Language
            $obj->addL11n(new ItemL11n(
                $itemL11nType['name1']->getId(),
                empty($t = \trim($article->name1Eng, " ,\t"))
                    ? \trim($article->name1, " ,\t")
                    : $t,
                ISO639x1Enum::_EN
            ));

            $obj->addL11n(new ItemL11n(
                $itemL11nType['name2']->getId(),
                empty($t = \trim($article->name2Eng, " ,\t"))
                    ? \trim($article->name2, " ,\t")
                    : $t,
                ISO639x1Enum::_EN
            ));

            if (isset($media[$number])) {
                $obj->addFile($media[$number]);
            }

            ItemMapper::create($obj);
        }
    }

    /**
     * Create and get item l11n types
     *
     * @return ItemL11nType[]
     *
     * @since 1.0.0
     */
    private function createItemL11nTypes() : array
    {
        $itemL11nType = [];

        if (($itemL11nType['name1'] = ItemL11nTypeMapper::getBy('name1', 'itemmgmt_attr_type_name')) instanceof NullItemL11nType) {
            $itemL11nType['name1'] = new ItemL11nType('name1');
            ItemL11nTypeMapper::create($itemL11nType['name1']);
        }

        if (($itemL11nType['name2'] = ItemL11nTypeMapper::getBy('name2', 'itemmgmt_attr_type_name')) instanceof NullItemL11nType) {
            $itemL11nType['name2'] = new ItemL11nType('name2');
            ItemL11nTypeMapper::create($itemL11nType['name2']);
        }

        if (($itemL11nType['info'] = ItemL11nTypeMapper::getBy('info', 'itemmgmt_attr_type_name')) instanceof NullItemL11nType) {
            $itemL11nType['info'] = new ItemL11nType('info');
            ItemL11nTypeMapper::create($itemL11nType['info']);
        }

        return $itemL11nType;
    }

    /**
     * Create and get item attribute types
     *
     * @return ItemAttributeType[]
     *
     * @since 1.0.0
     */
    private function createItemAttributeTypes() : array
    {
        $itemAttrType = [];

        // @todo check if attr. types already exist, if yes don't create, just save them in here.
        if (($itemAttrType['tradegroup'] = ItemAttributeTypeMapper::getBy('tradegroup', 'name')) instanceof NullItemAttributeType) {
            $itemAttrType['tradegroup'] = new ItemAttributeType('tradegroup');
            ItemAttributeTypeMapper::create($itemAttrType['tradegroup']);

            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['tradegroup']->getId(), 'Trade Group', ISO639x1Enum::_EN));
            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['tradegroup']->getId(), 'Handelsgruppe', ISO639x1Enum::_DE));
        }

        if (($itemAttrType['exportcontrolgroup'] = ItemAttributeTypeMapper::getBy('exportcontrolgroup', 'name')) instanceof NullItemAttributeType) {
            $itemAttrType['exportcontrolgroup'] = new ItemAttributeType('exportcontrolgroup');
            ItemAttributeTypeMapper::create($itemAttrType['exportcontrolgroup']);

            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['exportcontrolgroup']->getId(), 'Export Control Group', ISO639x1Enum::_EN));
            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['exportcontrolgroup']->getId(), 'Exportkontrollgruppe', ISO639x1Enum::_DE));
        }

        if (($itemAttrType['medicalgroup'] = ItemAttributeTypeMapper::getBy('medicalgroup', 'name')) instanceof NullItemAttributeType) {
            $itemAttrType['medicalgroup'] = new ItemAttributeType('medicalgroup');
            ItemAttributeTypeMapper::create($itemAttrType['medicalgroup']);

            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['medicalgroup']->getId(), 'Medical Device Group', ISO639x1Enum::_EN));
            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['medicalgroup']->getId(), 'Medezinproduktklasse', ISO639x1Enum::_DE));
        }

        if (($itemAttrType['customsnumber'] = ItemAttributeTypeMapper::getBy('customsnumber', 'name')) instanceof NullItemAttributeType) {
            $itemAttrType['customsnumber'] = new ItemAttributeType('customsnumber');
            ItemAttributeTypeMapper::create($itemAttrType['customsnumber']);

            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['customsnumber']->getId(), 'Customs Number', ISO639x1Enum::_EN));
            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['customsnumber']->getId(), 'Zolltarifnummer', ISO639x1Enum::_DE));
        }

        if (($itemAttrType['unnumber'] = ItemAttributeTypeMapper::getBy('unnumber', 'name')) instanceof NullItemAttributeType) {
            $itemAttrType['unnumber'] = new ItemAttributeType('unnumber');
            ItemAttributeTypeMapper::create($itemAttrType['unnumber']);

            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['unnumber']->getId(), 'UN-Number', ISO639x1Enum::_EN));
            ItemAttributeTypeL11nMapper::create(new ItemAttributeTypeL11n($itemAttrType['unnumber']->getId(), 'UN-Nummer', ISO639x1Enum::_DE));
        }

        return $itemAttrType;
    }

    /**
     * Create and get item attribute values
     *
     * @param ItemAttributeType[] $itemAttrType Attribute types
     *
     * @return ItemAttributeValue[]
     *
     * @since 1.0.0
     */
    private function createItemAttributeValues(array $itemAttrType) : array
    {
        $itemAttrValue = [];

        return $itemAttrType;
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
