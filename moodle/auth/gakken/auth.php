<?php


/**
 * @author Endy Hardy
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package Gakken SSO (gakken)
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->libdir.'/authlib.php');

class auth_plugin_gakken extends auth_plugin_base {
	
	/**
     * Constructor
     */
    function auth_plugin_gakken() {
        $this->authtype = 'gakken';
    }

    /**
     * Returns true if the username and password work or don't exist and false
     * if the user exists and the password is wrong.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login($username) {
        global $CFG, $DB;
        if (!$DB->get_record('user', array('username' => $username, 'mnethostid'=>$CFG->mnet_localhost_id)))
            return false;
        return true;
    }

    /**
     * Updates the user's password.
     *
     * Called when the user password is updated.
     *
     * @param  object  $user        User table object
     * @param  string  $newpassword Plaintext password
     * @return boolean result
     */
    function user_update_password($user, $newpassword) {
        global $DB;
        $user = get_complete_user_data('id', $user->id);
        return $DB->set_field('user', 'password',  $newpassword, array('id'=>$user->id));
    }

    function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return true;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return false;
    }

    function postlogout_hook() {
        redirect('https://accounts.sejawat.co.id/logout');
    }
	
}
