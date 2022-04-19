<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

$lang = $this->getData('lang');

/** @var \phpOMS\Views\View $this */
echo $this->getData('nav')->render();

$interface = $this->getData('interface');

include $interface->source->getAbsolutePath()
    . $interface->source->name . '/'
    . 'import.tpl.php';
