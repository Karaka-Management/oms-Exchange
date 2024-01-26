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

use \phpOMS\Uri\UriFactory;

/** @var \Modules\Exchange\Models\ExchangeLog $log */
$log = $this->data['log'];

echo $this->data['nav']->render();
?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Exchange'); ?></div>
            <div class="portlet-body">
                <table class="list w-100">
                    <tbody>
                        <tr><td><?= $this->getHtml('ID', '0', '0'); ?><td class="wf-100"><?= $log->id; ?>
                        <tr><td><?= $this->getHtml('Type'); ?><td class="wf-100"><?= $log->type; ?>
                        <tr><td><?= $this->getHtml('Subtype'); ?><td class="wf-100"><?= $log->subtype; ?>
                        <tr><td><?= $this->getHtml('Created'); ?><td><?= $log->createdAt->format('Y-m-d'); ?>
                        <tr><td><?= $this->getHtml('Creator'); ?><td><a href="<?= UriFactory::build('{/base}/profile/view?for=' . $log->createdBy->id); ?>"><?= $log->createdBy->name1; ?></a>
                        <tr><td colspan="2"><?= $log->message; ?>
                </table>
            </div>
        </section>
    </div>
</div>
