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

use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$logs = $this->getData('logs') ?? [];

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Logs'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <table id="exchangeLogs" class="default">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                    <td><?= $this->getHtml('Type'); ?>
                    <td><?= $this->getHtml('Subtype'); ?>
                    <td class="wf-100"><?= $this->getHtml('Exchange'); ?>
                    <td><?= $this->getHtml('CreatedBy'); ?>
                    <td><?= $this->getHtml('CreatedAt'); ?>
                <tbody>
                <?php $count = 0; foreach ($logs as $key => $value) :
                    ++$count;
                    $url = UriFactory::build('{/prefix}admin/exchange/log?{?}&id=' . $value->getId());
                ?>
                    <tr data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $value->getId(); ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->getType(); ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->subtype; ?></a>
                        <td><a href="<?= $url; ?>"><?= $value->exchange->getName(); ?></a>
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
