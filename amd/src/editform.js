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
 * Javascript to initialise the shelf badge edit_form block.
 *
 * @module block_shelf_badge/editform
 * @copyright   2023 eticeo <contact@eticeo.fr>
 * @copyright   2023 Jeremy Carre <jeremy.carre@eticeo.fr>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Import jquery from moodle src.
import $ from 'jquery';

// Import our third-party libs.
import 'block_shelf_badge/select2';

/**
 * Function call in php.
 * @param instid
 * @param waitModal bool : true if we need to wait modal to call js. False if not (ex: load param is tab)
 */
export const init = (instid, waitModal) => {
    if (waitModal) {
        // Call specific function.
        block_shelf_badge_waitmodal(instid);
    } else {
        // Go on.
        block_shelf_badge_on_open();
    }
};

function block_shelf_badge_on_open() {
    $('[name="config_user_role[]"]').select2({placeholder: M.util.get_string('select_user_role', 'block_shelf_badge')});
}

/**
 * Function wait specifique element are NOT here to continue.
 * @param selector string: the specifique element selector
 * @returns {Promise}
 */
function block_shelf_badge_waitForNotElm(selector) {
    return new Promise(resolve => {
        if (!document.querySelector(selector)) {
            return resolve(document.querySelector(selector));
        }

        const observer = new MutationObserver(mutations => {
            if (!document.querySelector(selector)) {
                resolve(document.querySelector(selector));
                observer.disconnect();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

/**
 * Function wait specifique element are here to continue.
 * @param selector string: the specifique element selector
 * @returns {Promise}
 */
function block_shelf_badge_waitForElm(selector) {
    return new Promise(resolve => {
        if (document.querySelector(selector)) {
            return resolve(document.querySelector(selector));
        }

        const observer = new MutationObserver(mutations => {
            if (document.querySelector(selector)) {
                resolve(document.querySelector(selector));
                observer.disconnect();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

function block_shelf_badge_waitmodal(instid) {
    // We need to wait specifique element (which is in modal) to run init.
    block_shelf_badge_waitForElm("input[name='blockid'][value='" + instid + "']").then((elm) => {
        // Element is her, run init.
        block_shelf_badge_on_open();

        // And now wait modal closed to rerun the waitmodal.
        block_shelf_badge_waitdestroymodal(instid);
    });
}

/**
 * Needed in case we open, close then reopen modal.
 */
function block_shelf_badge_waitdestroymodal(instid) {
    block_shelf_badge_waitForNotElm("input[name='blockid'][value='" + instid + "']").then((elm) => {
        block_shelf_badge_on_open();
        block_shelf_badge_waitmodal(instid);
    });
}
