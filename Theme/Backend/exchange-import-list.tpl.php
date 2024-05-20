<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

/** @var \phpOMS\Views\View $this */
$interfaces = $this->data['interfaces'];

echo $this->data['nav']->render();
?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Imports'); ?><i class="g-icon download btn end-xs">download</i></div>
            <div class="slider">
            <table id="importList" class="default sticky">
                <thead>
                <tr>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                        <label for="importList-sort-1">
                            <input type="radio" name="importList-sort" id="importList-sort-1">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="importList-sort-2">
                            <input type="radio" name="importList-sort" id="importList-sort-2">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                <tbody>
                <?php $count = 0; foreach ($interfaces as $key => $value) : ++$count;
                $url         = \phpOMS\Uri\UriFactory::build('{/base}/admin/exchange/import/view?{?}&id=' . $value->id); ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td data-label="<?= $this->getHtml('Title'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->title); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="2" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </section>
    </div>
</div>
