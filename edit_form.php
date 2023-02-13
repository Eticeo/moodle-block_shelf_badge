<?php
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
 * Form for editing Eticeo Indicators block instances.
 *
 * @package   block_shelf_badge
 * @copyright 2021 De Chiara Antonella <antonella.dechiara@eticeo.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing Blog tags block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_shelf_badge_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $CFG;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('thetitle_desc', BLOCK_SHELF_BADGE));
        $mform->setDefault('config_title', get_string('thetitle', BLOCK_SHELF_BADGE));
        $mform->setType('config_title', PARAM_TEXT);

        // Bullet icon (before the title)
        if (is_file($CFG->dirroot . '/theme/edumy/ccn/font_handler/ccn_font_select.php')) {
            $ccnFontList = include($CFG->dirroot . '/theme/edumy/ccn/font_handler/ccn_font_select.php');
            $select = $mform->addElement('select', 'config_bullet_icon', get_string('config_bullet_icon', BLOCK_SHELF_BADGE), $ccnFontList, array('class' => 'ccn_icon_class'));
            $select->setSelected(get_string('config_bullet_icon_default', BLOCK_SHELF_BADGE));
        } else {
            $mform->addElement('text', 'config_bullet_icon', get_string('config_bullet_icon', BLOCK_SHELF_BADGE));
            $mform->setDefault('config_bullet_icon', get_string('config_bullet_icon_default', BLOCK_SHELF_BADGE));
            $mform->setType('config_bullet_icon', PARAM_TEXT);
        }
        $options = range(1,12); // 0 => 1, 1 => 2 ...
        $options = array_combine($options,$options);// 1 => 1, 2 => 2 ...

        $mform->addElement('select', 'config_space', get_string('configspace_desc', BLOCK_SHELF_BADGE), $options);
        $mform->setDefault('config_space', 5);

        // sort by course
        $mform->addElement('selectyesno', 'config_sort_by_courses', get_string('config_sort_by_courses', BLOCK_SHELF_BADGE));
        $mform->setDefault('config_sort_by_courses', 0);

        //display button to deploy/fold up the course
        $mform->addElement('selectyesno', 'config_deploy_button_available', get_string('config_deploy_button_available', BLOCK_SHELF_BADGE));
        $mform->setDefault('config_deploy_button_available', 0);

    }
}
