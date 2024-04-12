<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Exchange
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

$lang = $this->data['lang'];

/** @var \phpOMS\Views\View $this */
echo $this->data['nav']->render();

$interface = $this->data['interface'];

include $interface->source->getAbsolutePath() . '/import.tpl.php';
