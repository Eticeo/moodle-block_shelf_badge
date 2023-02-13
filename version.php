<?php
/**
 * Copyright (c) 2021. Eticeo - https://eticeo.com
 */

/**
 * Bibliothèque des badges d'un user
 *
 * @package    block_shelf_badge
 * @copyright  2021 Eticeo <contact@eticeo.fr>
 * @author     2021 De Chiara Antonella (http://antonella-dechiara.develop4fun.fr/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2021042801;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2017110800;        // Requires this Moodle version
$plugin->release   = '1.0';
$plugin->component = 'block_shelf_badge'; // Full name of the plugin (used for diagnostics)
$plugin->cron      = 24*3600;           // Cron interval 1 day.

$plugin->dependencies = [
	'report_indicators' => ANY_VERSION
];