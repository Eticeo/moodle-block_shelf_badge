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
 * Block shelf_badge definition
 *
 * @package    block_shelf_badge
 * @copyright  2021 Eticeo <contact@eticeo.fr>
 * @author     2021 De Chiara Antonella (http://antonella-dechiara.develop4fun.fr/)
 * @author     2021 dec Guevara gabrielle <gabrielle.guevara@eticeo.fr>
 * @author     2022 aug CARRE Jeremy <jeremy.carre@eticeo.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
define('BLOCK_SHELF_BADGE', 'block_shelf_badge');

require_once($CFG->libdir . "/badgeslib.php");

/**
 * The shelf badge block class
 *
 * @package block_shelf_badge
 * @copyright  2021 Eticeo <contact@eticeo.fr>
 * @author     2021 De Chiara Antonella (http://antonella-dechiara.develop4fun.fr/)
 * @author     2021 dec Guevara gabrielle <gabrielle.guevara@eticeo.fr>
 * @author     2022 aug CARRE Jeremy <jeremy.carre@eticeo.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_shelf_badge extends block_base {
    /**
     * Initialise the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('thetitle', BLOCK_SHELF_BADGE);
    }

    /**
     * The block is usable in all pages
     */
    public function applicable_formats() {
        global $CFG;

        return array('all' => true);
    }

    /**
     * The block can be used repeatedly in a page.
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Return true because we got config
     *
     * @return bool true
     */
    public function has_config() {
        return true;
    }

    /**
     * Return any HTML attributes that you want added to the outer <div> that
     * of the block when it is output.
     *
     * @return array
     * @throws coding_exception
     */
    public function html_attributes() {
        $attributes = array(
                'id' => 'inst' . $this->instance->id,
                'class' => 'block_' . $this->name() . ' block ' . $this->bootstrap_size(),
                'role' => $this->get_aria_role()
        );
        if ($this->hide_header()) {
            $attributes['class'] .= ' no-header';
        }
        if ($this->instance_can_be_docked() && get_user_preferences('docked_block_instance_' . $this->instance->id, 0)) {
            $attributes['class'] .= ' dock_on_load';
        }
        return $attributes;
    }

    /**
     * Build the block content.
     */
    public function get_content() {
        global $USER;

        $this->page->requires->string_for_js('select_user_role', 'block_shelf_badge');
        $this->page->requires->css("/blocks/shelf_badge/css/select2.min.css", true);
        $this->page->requires->js_call_amd('block_shelf_badge/editform', 'init', [$this->instance->id, true]);


        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        } else {
            $this->title = get_string('thetitle', BLOCK_SHELF_BADGE);
        }

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $userid = $this->get_user_to_show();

        if ($this->is_user_enabled($userid)) {
            $this->content->footer = '<div class="eticeo-indicators">' . self::eticeo_vue() . '</div>';
        } else {
            $this->title = $this->title . ' <i>' . get_string('hidden_for_user', 'block_shelf_badge') . '</i>';
        }

        return $this->content;
    }

    /**
     * Return true if the user has the right to see this block
     *
     * @param int $userid | id of the selected user
     * @return bool
     * @throws dml_exception
     */
    private function is_user_enabled($userid) {
        global $DB, $CFG;

        $hasenablerole = false;
        $userroles = isset($this->config) ? $this->config->user_role : array();
        if (!empty($userroles)) {
            // Enabled for everybody.
            if (in_array(0, $userroles)) {

                return true;
            }
            // Manager.
            if (in_array(1, $userroles)) {
                $admins = explode(',', $CFG->siteadmins);
                if (in_array($userid, $admins)) {

                    return true;
                }
            }
            // Now use placeholder to prevent SQL injection.
            [$insqluserroles, $param] = $DB->get_in_or_equal($userroles);

            $sql = 'SELECT u.id FROM {user} u
                   INNER JOIN {role_assignments} ra ON u.id = ra.userid
                   WHERE ra.roleid ' . $insqluserroles . '
                   AND userid = :userid';

            $param["userid"] = $userid;

            $hasenablerole = $DB->get_records_sql($sql, $param);
            $hasenablerole = !empty($hasenablerole);

        }

        return $hasenablerole;
    }

    /**
     * Subfunction when we can display information
     * return string for output
     *
     * @return string
     */
    public function eticeo_vue() {
        global $OUTPUT, $CFG, $DB, $USER;

        $content = '';

        $content .= '<div class="container mt80"><div class="row">';

        $buttonfoldup = $script = '';
        if ($this->config->deploy_button_available) {
            $buttonfoldup = '<i class="fa fa-chevron-down eticeoDeployBadges" onclick="eticeoDeployBadges()"></i>';
            $script = '<script>
                           function eticeoDeployBadges() {
                               if ($(".eticeo-indicators .temps-total").is(":visible")) {
                                   $(".eticeo-indicators .temps-total").hide();
                                   $(".eticeoDeployBadges").removeClass("fa-chevron-down");
                                   $(".eticeoDeployBadges").addClass("fa-chevron-up");
                               } else {
                                   $(".eticeo-indicators .temps-total").show();
                                   $(".eticeoDeployBadges").removeClass("fa-chevron-up");
                                   $(".eticeoDeployBadges").addClass("fa-chevron-down");
                               }
                           }
                       </script>';
        }
        // BLOC 1.
        $content .= $buttonfoldup . self::simple_block();

        $content .= '</div></div>' . $script; // End row.

        return $content;
    }

    /**
     * return the id of the user or the id of the userReplace if the user is a manager and can see the view for other user
     *
     * @return bool
     */
    private function get_user_to_show() {
        global $USER;

        // USER.
        $userreplace = optional_param('userReplace', null, PARAM_INT);
        if (is_siteadmin($USER->id) && $userreplace) {
            $userid = $userreplace;
        } else {
            $userid = $USER->id;
        }

        return $userid;
    }

    /**
     * Return
     *
     * @return string
     */
    public function simple_block() {
        global $CFG;

        $content = '';
        // BADGES.
        $badges = "";

        // Number of badges to display.
        $numberofbadges = !isset($this->config->numberofbadges) ? 10 : $this->config->numberofbadges;

        $userid = $this->get_user_to_show();

        if (empty($CFG->enablebadges)) {
            $badges .= get_string('badgesdisabled', 'badges');
        }

        $courseid = $this->page->course->id;
        $courseid = $courseid == SITEID ? null : $courseid;

        $class = 'eticeo-label';
        $content .= '<div class="col-12">';
        $badgesoutput = $this->page->get_renderer('core', 'badges');
        // Now, we won't iterate over all courses.
        $userbadges = badges_get_user_badges($userid);

        // If the param sort_by_courses is true, we display by courses.
        // But now we also need to sort our array.
        if ($userbadges && $this->config->sort_by_courses) {
            // First, add class because style will change.
            $class .= ' eticeo-sort-by-course';
            $userbadgesbycourses = array();

            // We need to do a list of badges by courses ( array[courseid][badgesid] )
            // ... because $badgesoutput->print_badges_list take list in param for our display.
            foreach ($userbadges as $badgeid => $badge) {
                // We ignore system badge because of the config parameter.
                if ($badge->courseid) {
                    $userbadgesbycourses[$badge->courseid][] = $badge;
                }
            }

            // Now we iterate over userBadgesByCourses to sort by course.
            foreach ($userbadgesbycourses as $courseid => $listofbadges) {
                // Get the course info for output.
                $course = get_course($courseid);

                // Get the number of badges in this courses.
                $numbadges = badges_get_badges(BADGE_TYPE_COURSE, $courseid, '', '', 0, BADGE_PERPAGE, $userid);
                $numbadges = count($numbadges);

                // Get the number obtainable by user.
                $numbadgesobtained = count($listofbadges);

                // Get the output for this list of badge.
                $courseline = $badgesoutput->print_badges_list($listofbadges, $userid, true);

                // Agregate the output.
                $badges .= '<div class="courses-badges">
                                        <div class="eticeoTitleCourse">' . $course->fullname . '
                                            <i class="badges-obtained-number" hidden> ' . $numbadgesobtained . ' ' .
                        get_string('obtained-badges-number', BLOCK_SHELF_BADGE) . ' ' .
                        $numbadges . ' ' . get_string('possibles-badges-number', BLOCK_SHELF_BADGE) . '</i>
                                            <i class="badges-obtained-number"> ' . $numbadgesobtained . '/' .
                        $numbadges . '</i>

                                         </div>' .
                        $courseline .
                        '</div>';
            }
        } else if ($userbadges) {
            $badges = $badgesoutput->print_badges_list($userbadges, $userid, true);
        } else {
            $badges .= get_string('nothingtodisplay', 'block_badges');
        }

        $content .= '<div class="text-left temps-total">
                         <span class="' . $class . '">' . $badges . '</span>
                     </div> <!-- text-center -->';

        $content .= '</div>'; // END col-4.

        return $content;
    }

    /**
     * Usefull to translate bootstrap_size
     *
     * @return string
     */
    public function bootstrap_size() {
        $space = !empty($this->config->space) ? $this->config->space : 7;

        return "col-" . $space . " col-md-" . $space . " col-sm-12";
    }

    /**
     * Hide or display the header
     *
     * @return boolean
     */
    public function hide_header() {
        return false;
    }

}
