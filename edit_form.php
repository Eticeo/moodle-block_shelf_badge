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
 * Block shelf_badge configuration form definition
 *
 * @package    block_shelf_badge
 * @copyright  2021 Eticeo <contact@eticeo.fr>
 * @author     2021 De Chiara Antonella (http://antonella-dechiara.develop4fun.fr/)
 * @author     2021 dec Guevara gabrielle <gabrielle.guevara@eticeo.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_shelf_badge_edit_form extends block_edit_form {

    /**
     * The class form used by blocks/edit.php to edit block instance configuration.
     *
     * @param object $mform
     * @return void
     * @throws coding_exception
     */
    protected function specific_definition($mform) {
        global $CFG, $PAGE;

        $this->page->requires->string_for_js('select_user_role', 'block_shelf_badge');
        // Moodle 4.2 simplification.
        $PAGE->requires->css("/blocks/eticeo_categories_completion/css/select2.min.css", true);
        $PAGE->requires->js_call_amd('block_eticeo_categories_completion/editform','init',[$this->block->instance->id, false]);


        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('thetitle_desc', BLOCK_SHELF_BADGE));
        $mform->setDefault('config_title', get_string('thetitle', BLOCK_SHELF_BADGE));
        $mform->setType('config_title', PARAM_TEXT);

        // Bullet icon (before the title).
        if (is_file($CFG->dirroot . '/theme/edumy/ccn/font_handler/ccn_font_select.php')) {
            $ccnfontlist = include($CFG->dirroot . '/theme/edumy/ccn/font_handler/ccn_font_select.php');
            $select = $mform->addElement('select', 'config_bullet_icon', get_string('config_bullet_icon', BLOCK_SHELF_BADGE),
                    $ccnfontlist, array('class' => 'ccn_icon_class'));
            $select->setSelected(get_string('config_bullet_icon_default', BLOCK_SHELF_BADGE));
        } else {
            $mform->addElement('text', 'config_bullet_icon', get_string('config_bullet_icon', BLOCK_SHELF_BADGE));
            $mform->setDefault('config_bullet_icon', get_string('config_bullet_icon_default', BLOCK_SHELF_BADGE));
            $mform->setType('config_bullet_icon', PARAM_TEXT);
        }
        $options = range(1, 12);
        $options = array_combine($options, $options);

        $mform->addElement('select', 'config_space', get_string('configspace_desc', BLOCK_SHELF_BADGE), $options);
        $mform->setDefault('config_space', 5);

        // Sort by course.
        $mform->addElement('selectyesno', 'config_sort_by_courses', get_string('config_sort_by_courses', BLOCK_SHELF_BADGE));
        $mform->setDefault('config_sort_by_courses', 0);

        // Display button to deploy/fold up the course.
        $mform->addElement('selectyesno', 'config_deploy_button_available',
                get_string('config_deploy_button_available', BLOCK_SHELF_BADGE));
        $mform->setDefault('config_deploy_button_available', 0);

        // Restricted access by role.
        $userrolelist = array(0 => get_string('everybody', BLOCK_SHELF_BADGE));
        $userroles = get_all_roles();
        foreach ($userroles as $role) {
            if ($role->shortname != '') {
                $userrolelist[$role->id] = $role->shortname . ($role->name != '' ? ' (' . $role->name . ')' : '');
            }
        }

        $select =
                $mform->addElement('select', 'config_user_role', get_string('config_user_role', BLOCK_SHELF_BADGE), $userrolelist);
        $select->setSelected(0);
        $select->setMultiple(true);
    }
}
