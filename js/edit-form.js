// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block shelf_badge edit form js
 *
 * @package    block_shelf_badge
 * @copyright  2021 Eticeo <contact@eticeo.fr>
 * @author     2021 De Chiara Antonella (http://antonella-dechiara.develop4fun.fr/)
 * @author     2021 dec Guevara gabrielle <gabrielle.guevara@eticeo.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
$(function() {
    $('[name="config_user_role[]"]').select2({placeholder: "Select a user role"});
});