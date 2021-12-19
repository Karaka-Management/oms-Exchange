<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Template
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

$lang = $this->getData('lang');
?>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <section class="portlet">
            <form id="fImport" method="POST" action="<?= \phpOMS\Uri\UriFactory::build('{/api}admin/exchange/import/profile?{?}&id={?id}&type=language&csrf={$CSRF}'); ?>">
                <div class="portlet-head"><?= $this->printHtml($lang['Language']); ?> - OMS</div>
                <div class="portlet-body">
                    <table class="layout wf-100" style="table-layout: fixed">
                        <tbody>
                        <tr><td><label for="iFile"><?= $this->getHtml('File'); ?></label>
                        <tr><td><input type="file" id="iFile" name="file" placeholder="&#xf040; <?= $this->getHtml('File'); ?>" required>
                    </table>
                </div>
                <div class="portlet-foot">
                    <input type="submit" value="<?= $this->getHtml('Import'); ?>">
                </div>
            </form>
        </section>
    </div>
</div>
