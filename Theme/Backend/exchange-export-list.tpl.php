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

/** @var \phpOMS\Views\View $this */
$interfaces = $this->getData('interfaces');

echo $this->getData('nav')->render();
?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Exports'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <table id="exportList" class="default sticky">
                <thead>
                <tr>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                        <label for="exportList-sort-1">
                            <input type="radio" name="exportList-sort" id="exportList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="exportList-sort-2">
                            <input type="radio" name="exportList-sort" id="exportList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
                <?php $count = 0; foreach ($interfaces as $key => $value) : ++$count;
                $url         = \phpOMS\Uri\UriFactory::build('{/prefix}admin/exchange/export/profile?{?}&id=' . $value->getId()); ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td data-label="<?= $this->getHtml('Title'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->getName()); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="2" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
        </section>
    </div>
</div>
