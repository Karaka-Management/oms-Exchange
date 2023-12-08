<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Template
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Exchange\Models\NullExchangeSetting;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\SchemaMapper;
use phpOMS\Uri\UriFactory;

$lang = $this->getData('lang') ?? [];

// @todo define offset fields
// @todo define create job button
// @todo show job reference

$interface = $this->getData('interface');
$settings  = $interface->getSettings();

$currentSetting = $settings[(int) ($this->request->getData('setting') ?? 0)] ?? new NullExchangeSetting();
$settingData    = $currentSetting->getData();

$dbTypes = DatabaseType::getConstants();

$importTables = [];
$exportTables = [];

$currentImportTableFields = [];
$currentExportTableFields = [];

if (!empty($settingData)) {
    $importConnection = ($settingData['import']['db']['self'] ?? true)
        ? $this->getData('db') ?? new NullConnection()
        : ConnectionFactory::create([
            'db'       => $settingData['import']['db']['db'],
            'host'     => $settingData['import']['db']['host'],
            'port'     => $settingData['import']['db']['port'],
            'database' => $settingData['import']['db']['database'],
            'login'    => $settingData['import']['db']['login'],
            'password' => $settingData['import']['db']['password'],
        ]);
    $exportConnection = ($settingData['export']['db']['self'] ?? true)
        ? $this->getData('db') ?? new NullConnection()
        : ConnectionFactory::create([
            'db'       => $settingData['export']['db']['db'],
            'host'     => $settingData['export']['db']['host'],
            'port'     => $settingData['export']['db']['port'],
            'database' => $settingData['export']['db']['database'],
            'login'    => $settingData['export']['db']['login'],
            'password' => $settingData['export']['db']['password'],
    ]);

    $importSchemaMapper = new SchemaMapper($importConnection);
    $exportSchemaMapper = new SchemaMapper($exportConnection);

    $importTables = $importSchemaMapper->getTables();
    $exportTables = $exportSchemaMapper->getTables();

    $currentImportTableFields = empty($importTables) ? [] : $importSchemaMapper->getFields($importTables[0]);
    $currentExportTableFields = empty($exportTables) ? [] : $exportSchemaMapper->getFields($exportTables[0]);
}

$isNew = $currentSetting->id === 0;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-body">
                <form id="fEditor" method="<?= $isNew ? 'PUT' : 'POST'; ?>" action="<?= UriFactory::build('{/api}admin/exchange/import?{?}&csrf={$CSRF}'); ?>">
                    <div class="ipt-wrap">
                        <div class="ipt-first"><input name="title" type="text" class="wf-100" value="<?= $currentSetting->title; ?>"></div>
                        <div class="ipt-second">
                            <input type="submit" value="<?= $this->getHtml('Save', '0', '0'); ?>" name="save-editor">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-4">
        <section class="portlet">
            <form id="fImport" method="POST" action="<?= UriFactory::build('{/api}admin/exchange/import/profile?{?}&id={?id}&type=language&csrf={$CSRF}'); ?>">
                <div class="portlet-head"><?= $this->printHtml($lang['DatabaseExchanger']); ?> - <?= $this->printHtml($lang['Import']); ?></div>
                <div class="portlet-body">
                    <table class="layout wf-100">
                        <tbody>
                        <tr><td><label for="iDatabaseType"><?= $lang['Connection']; ?></label>
                        <tr><td>
                            <select id="iConnection" name="type">
                                <option value="<?= $lang['Custom']; ?>"<?= ($settingData['import']['db']['self'] ?? false) ? '' : ' selected'; ?>><?= $lang['Custom']; ?>
                                <option value="<?= $lang['Self']; ?>" <?= ($settingData['import']['db']['self'] ?? false) ? ' selected' : ''; ?>><?= $lang['Self']; ?>
                            </select>
                        <tr><td><label for="iDatabaseType"><?= $lang['Type']; ?></label>
                        <tr><td>
                            <select id="iDatabaseType" name="type">
                                <?php foreach ($dbTypes as $type): ?>
                                <option value="<?= $this->printHtml($type); ?>"><?= $this->printHtml($type); ?>
                                <?php endforeach; ?>
                            </select>
                        <tr><td><label for="iHost"><?= $this->getHtml('Host'); ?></label>
                        <tr><td><input type="text" id="iHost" name="host" value="<?= $this->printHtml($settingData['import']['db']['host'] ?? ''); ?>">
                        <tr><td><label for="iPort"><?= $this->getHtml('Port'); ?></label>
                        <tr><td><input min="0" max="65536" type="number" id="iPort" name="port" value="<?= $settingData['import']['db']['port'] ?? ''; ?>">
                        <tr><td><label for="iDatabase"><?= $this->getHtml('Database'); ?></label>
                        <tr><td><input type="text" id="iDatabase" name="database" value="<?= $this->printHtml($settingData['import']['db']['database'] ?? ''); ?>">
                        <tr><td><label for="iLogin"><?= $this->getHtml('Login'); ?></label>
                        <tr><td><input type="text" id="iLogin" name="login" value="<?= $this->printHtml($settingData['import']['db']['login'] ?? ''); ?>">
                        <tr><td><label for="iPassword"><?= $this->getHtml('Password'); ?></label>
                        <tr><td><input type="password" id="iPassword" name="password" value="<?= $this->printHtml($settingData['import']['db']['password'] ?? ''); ?>">
                    </table>
                </div>
                <div class="portlet-foot">
                    <input type="submit" value="<?= $this->getHtml('Load', '0', '0'); ?>">
                </div>
            </form>
        </section>
    </div>

    <div class="col-xs-12 col-md-4">
        <section class="portlet">
            <form id="fImport" method="POST" action="<?= UriFactory::build('{/api}admin/exchange/import/profile?{?}&id={?id}&type=language&csrf={$CSRF}'); ?>">
                <div class="portlet-head"><?= $this->printHtml($lang['DatabaseExchanger']); ?> - <?= $this->printHtml($lang['Export']); ?></div>
                <div class="portlet-body">
                    <table class="layout wf-100">
                        <tbody>
                        <tr><td><label for="iDatabaseType"><?= $lang['Connection']; ?></label>
                        <tr><td>
                            <select id="iConnection" name="type">
                            <option value="<?= $lang['Custom']; ?>"<?= ($settingData['export']['db']['self'] ?? false) ? '' : ' selected'; ?>><?= $lang['Custom']; ?>
                                <option value="<?= $lang['Self']; ?>" <?= ($settingData['export']['db']['self'] ?? false) ? ' selected' : ''; ?>><?= $lang['Self']; ?>
                            </select>
                        <tr><td><label for="iDatabaseType"><?= $lang['Type']; ?></label>
                        <tr><td>
                            <select id="iDatabaseType" name="type">
                                <?php foreach ($dbTypes as $type): ?>
                                <option value="<?= $this->printHtml($type); ?>"><?= $this->printHtml($type); ?>
                                <?php endforeach; ?>
                            </select>
                        <tr><td><label for="iHost"><?= $this->getHtml('Host'); ?></label>
                        <tr><td><input type="text" id="iHost" name="host" value="<?= $this->printHtml($settingData['export']['db']['host'] ?? ''); ?>">
                        <tr><td><label for="iPort"><?= $this->getHtml('Port'); ?></label>
                        <tr><td><input min="0" max="65536" type="number" id="iPort" name="port" value="<?= $settingData['export']['db']['port'] ?? ''; ?>">
                        <tr><td><label for="iDatabase"><?= $this->getHtml('Database'); ?></label>
                        <tr><td><input type="text" id="iDatabase" name="database" value="<?= $this->printHtml($settingData['export']['db']['database'] ?? ''); ?>">
                        <tr><td><label for="iLogin"><?= $this->getHtml('Login'); ?></label>
                        <tr><td><input type="text" id="iLogin" name="login" value="<?= $this->printHtml($settingData['export']['db']['login'] ?? ''); ?>">
                        <tr><td><label for="iPassword"><?= $this->getHtml('Password'); ?></label>
                        <tr><td><input type="password" id="iPassword" name="password" value="<?= $this->printHtml($settingData['export']['db']['password'] ?? ''); ?>">
                    </table>
                </div>
                <div class="portlet-foot">
                    <input type="submit" value="<?= $this->getHtml('Load', '0', '0'); ?>">
                </div>
            </form>
        </section>
    </div>

    <div class="col-xs-12 col-md-4">
        <section class="portlet">
            <div class="portlet-head"><?= $lang['Settings']; ?></div>
            <div class="slider">
                <table id="appList" class="default sticky">
                    <thead>
                    <tr>
                        <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <td><?= $this->getHtml('Title'); ?>
                    <tbody>
                    <?php
                        foreach ($settings as $setting) :
                            $url = UriFactory::build('{/base}/admin/exchange/import/profile?id=' . $interface->id . '&setting=' . $setting->id);
                    ?>
                    <tr data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $setting->id; ?></a>
                        <td class="wf-100"><a href="<?= $url; ?>"><?= $setting->title; ?></a>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $lang['Import']; ?></div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="layout wf-100">
                            <tbody>
                            <tr><td><label for="iDatabaseType"><?= $lang['Table']; ?></label>
                            <tr><td>
                                <select id="iConnection" name="type">
                                <?php foreach ($importTables as $table): ?>
                                    <option><?= $table; ?>
                                <?php endforeach; ?>
                                </select>
                        </table>

                        <table id="appList" class="default sticky">
                            <thead>
                            <tr>
                                <td>
                                <td><?= $lang['Type']; ?>
                                <td><?= $lang['Field']; ?>
                            <tbody>
                            <?php
                                foreach ($currentImportTableFields as $field) :
                            ?>
                            <tr>
                                <td><label class="radio" for="import-<?= $field['COLUMN_NAME']; ?>">
                                        <input id="import-<?= $field['COLUMN_NAME']; ?>" type="radio" name="import-field" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                <td><?= $field['DATA_TYPE']; ?>
                                <td><?= $field['COLUMN_NAME']; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="layout wf-100">
                            <tbody>
                            <tr><td><label for="iDatabaseType"><?= $lang['Table']; ?></label>
                            <tr><td>
                                <select id="iConnection" name="type">
                                <?php foreach ($exportTables as $table): ?>
                                    <option><?= $table; ?>
                                <?php endforeach; ?>
                                </select>
                        </table>

                        <table id="appList" class="default sticky">
                            <thead>
                            <tr>
                                <td>
                                <td><?= $lang['Type']; ?>
                                <td><?= $lang['Field']; ?>
                            <tbody>
                            <?php
                                foreach ($currentExportTableFields as $field) :
                            ?>
                            <tr>
                                <td><label class="radio" for="export-<?= $field['COLUMN_NAME']; ?>">
                                        <input id="export-<?= $field['COLUMN_NAME']; ?>" type="radio" name="export-field" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                <td><?= $field['DATA_TYPE']; ?>
                                <td><?= $field['COLUMN_NAME']; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="portlet-foot">
                <input form="fDatabaseConnection" type="submit" value="<?= $this->getHtml('Add', '0', '0'); ?>">
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $lang['Match']; ?></div>
            <div class="slider">
                <table id="appList" class="default sticky">
                    <thead>
                    <tr>
                        <td>
                        <td><?= $lang['Table']; ?>
                        <td><?= $lang['Table']; ?>
                        <td><?= $lang['IsPrimary']; ?>
                        <td><?= $lang['Src']; ?>
                        <td><?= $lang['SrcType']; ?>
                        <td><?= $lang['IsPrimary']; ?>
                        <td><?= $lang['Dest']; ?>
                        <td><?= $lang['DestType']; ?>
                    <tbody>
                    <?php
                        foreach (($settingData['relation'] ?? []) as $table) :
                            foreach ($table['match'] as $match) :
                    ?>
                    <tr>
                        <td><i class="g-icon btn remove-form">close</i>
                        <td><?= $table['src']; ?>
                        <td><?= $table['dest']; ?>
                        <td><?= $match['src_field']['primary'] ? 'yes' : 'no'; ?>
                        <td><?= $match['src_field']['column']; ?>
                        <td><?= $match['src_field']['type']; ?>
                        <td><?= $match['dest_field']['primary'] ? 'yes' : 'no'; ?>
                        <td><?= $match['dest_field']['column']; ?>
                        <td><?= $match['dest_field']['type']; ?>
                    <?php endforeach; endforeach; ?>
                </table>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-4">
    <section class="portlet">
        <div class="portlet-body">
            <div class="form-group">
                <label for="iTitle"><?= $this->getHtml('Column1'); ?></label>
                <input type="text" id="iTitle" name="column1title" placeholder="&#xf040; <?= $this->getHtml('Title'); ?>">
            </div>

            <div class="form-group">
                <label for="iTitle"><?= $this->getHtml('Filter1'); ?></label>
                <div class="ipt-wrap">
                    <div class="ipt-first">
                        <input type="text" id="iTitle" name="title" placeholder="&#xf040; <?= $this->getHtml('Title'); ?>">
                    </div>
                    <div class="ipt-second">
                        <select nma="column1comparison">
                            <option>=
                            <option>>
                            <option>>=
                            <option><
                            <option><=
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="iTitle"><?= $this->getHtml('Column2'); ?></label>
                <input type="text" id="iTitle" name="column2title" placeholder="&#xf040; <?= $this->getHtml('Title'); ?>">
            </div>

            <div class="form-group">
                <label for="iTitle"><?= $this->getHtml('Filter2'); ?></label>
                <div class="ipt-wrap">
                    <div class="ipt-first">
                        <input type="text" id="iTitle" name="title" placeholder="&#xf040; <?= $this->getHtml('Title'); ?>">
                    </div>
                    <div class="ipt-second">
                        <select name="column2comparison">
                            <option>=
                            <option>>
                            <option>>=
                            <option><
                            <option><=
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet-foot">
            <input type="submit" value="<?= $this->getHtml('Run', '0', '0'); ?>" name="run">
        </div>
    </section>
    </div>
</div>