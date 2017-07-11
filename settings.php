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
 * Moodle's solent2017 theme, an example of how to make a Bootstrap theme
 *
 * DO NOT MODIFY THIS THEME!
 * COPY IT FIRST, THEN RENAME THE COPY AND MODIFY IT INSTEAD.
 *
 * For full information about creating Moodle themes, see:
 * http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   theme_solent2017
 * @copyright 2013 Moodle, moodle.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Invert Navbar to dark background.
    $name = 'theme_solent2017/invert';
    $title = get_string('invert', 'theme_solent2017');
    $description = get_string('invertdesc', 'theme_solent2017');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Logo file setting.
    $name = 'theme_solent2017/logo';
    $title = get_string('logo','theme_solent2017');
    $description = get_string('logodesc', 'theme_solent2017');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Small logo file setting.
    $name = 'theme_solent2017/smalllogo';
    $title = get_string('smalllogo', 'theme_solent2017');
    $description = get_string('smalllogodesc', 'theme_solent2017');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'smalllogo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Show site name along with small logo.
    $name = 'theme_solent2017/sitename';
    $title = get_string('sitename', 'theme_solent2017');
    $description = get_string('sitenamedesc', 'theme_solent2017');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom CSS file.
    $name = 'theme_solent2017/customcss';
    $title = get_string('customcss', 'theme_solent2017');
    $description = get_string('customcssdesc', 'theme_solent2017');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Footnote setting.
    $name = 'theme_solent2017/footnote';
    $title = get_string('footnote', 'theme_solent2017');
    $description = get_string('footnotedesc', 'theme_solent2017');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
	
	// Current schools
    $name = 'theme_solent2017/schools';
    $title = get_string('schools', 'theme_solent2017');
    $description = get_string('schoolsdesc', 'theme_solent2017');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

	// Current units
    $name = 'theme_solent2017/units';
    $title = get_string('units', 'theme_solent2017');
    $description = get_string('unitsdesc', 'theme_solent2017');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
	
	// Succeed@Solent ID
    $name = 'theme_solent2017/succeed';
    $title = get_string('succeed', 'theme_solent2017');
    $description = get_string('succeeddesc', 'theme_solent2017');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

	// Teach to Succeed@Solent ID
    $name = 'theme_solent2017/teach';
    $title = get_string('teach', 'theme_solent2017');
    $description = get_string('teachdesc', 'theme_solent2017');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);	

}
