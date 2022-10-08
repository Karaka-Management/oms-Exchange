<div class="row">
    <div class="col-xs-12 col-md-6">
        <section class="portlet">
            <form id="fImport" method="POST" action="<?= \phpOMS\Uri\UriFactory::build('{/api}admin/exchange/import/profile?{?}&exchange=GSD&csrf={$CSRF}'); ?>">
                <div class="portlet-head"><?= $this->getHtml('Import'); ?> - GSD</div>
                <div class="portlet-body">
                    <table class="layout wf-100" style="table-layout: fixed">
                        <tbody>
                        <tr><td><label for="iHost"><?= $this->getHtml('Host'); ?></label>
                        <tr><td><input type="text" id="iHost" name="host" placeholder="&#xf040; <?= $this->getHtml('Host'); ?>" required><input type="hidden" id="iDb" name="db" value="<?= \phpOMS\DataStorage\Database\DatabaseType::SQLSRV; ?>" required>
                        <tr><td><label for="iPort"><?= $this->getHtml('Port'); ?></label>
                        <tr><td><input type="text" id="iPort" name="port" value="1433" required>
                        <tr><td><label for="iDatabase"><?= $this->getHtml('Database'); ?></label>
                        <tr><td><input type="text" id="iDatabase" name="database" placeholder="&#xf040; <?= $this->getHtml('Database'); ?>" required>
                        <tr><td><label for="iLogin"><?= $this->getHtml('Login'); ?></label>
                        <tr><td><input type="text" id="iLogin" name="login" placeholder="&#xf040; <?= $this->getHtml('Login'); ?>" required>
                        <tr><td><label for="iPassword"><?= $this->getHtml('Password'); ?></label>
                        <tr><td><input type="password" id="iPassword" name="password" placeholder="&#xf040; <?= $this->getHtml('Password'); ?>" required>
                        <tr><td><label for="iStart"><?= $this->getHtml('Start'); ?></label>
                        <tr><td><input type="datetime-local" id="iStart" name="start" value="<?= $this->printHtml((new \DateTime('NOW'))->format('Y-m-d\TH:i:s')); ?>">
                        <tr><td><label for="iEnd"><?= $this->getHtml('End'); ?></label>
                        <tr><td><input type="datetime-local" id="iEnd" name="end" value="<?= $this->printHtml((new \DateTime('NOW'))->format('Y-m-d\TH:i:s')); ?>">
                        <tr><td><?= $this->getHtml('Options'); ?>
                        <tr><td>
                            <table class="layout wf-100"><tr><td>
                                <label class="checkbox" for="iCustomers">
                                    <input id="iCustomers" type="checkbox" name="customers" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Customers']; ?>
                                </label>
                            <td>
                                <label class="checkbox" for="iInvoices">
                                    <input id="iInvoices" type="checkbox" name="invoices" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Invoices']; ?>
                                </label>
                        <tr><td>
                                <label class="checkbox" for="iSuppliers">
                                    <input id="iSuppliers" type="checkbox" name="suppliers" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Suppliers']; ?>
                                </label>
                            <td>
                                <label class="checkbox" for="iStocks">
                                    <input id="iStocks" type="checkbox" name="stocks" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Stocks']; ?>
                                </label>
                        <tr><td>
                                <label class="checkbox" for="iAccounts">
                                    <input id="iAccounts" type="checkbox" name="accounts" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Accounts']; ?>
                                </label>
                            <td>
                                <label class="checkbox" for="iAssets">
                                    <input id="iAssets" type="checkbox" name="assets" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Assets']; ?>
                                </label>
                        <tr><td>
                                <label class="checkbox" for="iCostCenters">
                                    <input id="iCostCenters" type="checkbox" name="costcenters" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['CostCenters']; ?>
                                </label>
                            <td>
                                <label class="checkbox" for="iPostings">
                                    <input id="iPostings" type="checkbox" name="postings" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Postings']; ?>
                                </label>
                        <tr><td>
                                <label class="checkbox" for="iCostObjects">
                                    <input id="iCostObjects" type="checkbox" name="costobjects" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['CostObjects']; ?>
                                </label>
                            <td>
                                <label class="checkbox" for="iArticles">
                                    <input id="iArticles" type="checkbox" name="articles" value="1">
                                    <span class="checkmark"></span>
                                    <?= $lang['Articles']; ?>
                                </label>
                        </table>
                    </table>
                </div>
                <div class="portlet-foot">
                    <input type="submit" value="<?= $this->getHtml('Import'); ?>" name="import">
                </div>
            </form>
        </section>
    </div>
</div>