<?php
/**
 * Rate this course
 *
 * @package    block
 * @subpackage shelf_badge
 * @copyright  2021 De Chiara Antonella <antonella.dechiara@eticeo.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was Rewritten for Moodle 3.X By Atar + Plus LTD for Comverse LTD.
 * @copyright &copy; 2018 Eticeo Santé
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

require_once( '../../config.php' );
require_once( $CFG->dirroot .'/lib/pagelib.php' );
// Load libraries.
require_once($CFG->dirroot.'/course/renderer.php');

global $DB, $USER, $CFG, $OUTPUT;
