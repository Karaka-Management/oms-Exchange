<?php
/**
 * Karaka
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

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$logs = $this->getData('logs') ?? [];

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Logs'); ?><i class="lni lni-download download btn end-xs"></i></div>
            <div class="slider">
            <table id="exchangeLogs" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="exchangeLogs-sort-1">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="exchangeLogs-sort-2">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Type'); ?>
                        <label for="exchangeLogs-sort-3">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="exchangeLogs-sort-4">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Subtype'); ?>
                        <label for="exchangeLogs-sort-5">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-5">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="exchangeLogs-sort-6">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-6">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Exchange'); ?>
                        <label for="exchangeLogs-sort-7">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-7">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="exchangeLogs-sort-8">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-8">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('CreatedBy'); ?>
                        <label for="exchangeLogs-sort-9">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-9">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="exchangeLogs-sort-0">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-0">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('CreatedAt'); ?>
                        <label for="exchangeLogs-sort-11">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-11">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="exchangeLogs-sort-12">
                            <input type="radio" name="exchangeLogs-sort" id="exchangeLogs-sort-12">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
                <?php $count = 0; foreach ($logs as $key => $value) :
                    ++$count;
                    $url = UriFactory::build('{/base}/admin/exchange/log?{?}&id=' . $value->id);
                ?>
                    <tr data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $value->id; ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->getType(); ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->subtype; ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->exchange->title; ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->createdBy->name1; ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->createdAt->format('Y-m-d'); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="9" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </div>
    </div>
</div>
