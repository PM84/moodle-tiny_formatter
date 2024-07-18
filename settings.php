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
 * Settings for the tiny_formatting plugin
 *
 * @package    tiny_formatting
 * @copyright  2024 ISB Bayern
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tiny_formatting\plugininfo;

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('editortiny', new admin_category('tiny_formatting', new lang_string('pluginname', 'tiny_formatting')));
$settings = new admin_settingpage('tiny_formatting_settings', new lang_string('settings', 'tiny_formatting'));

if ($ADMIN->fulltree) {
    // Group 1.
    $name = new lang_string('fonts', 'tiny_formatting');
    $desc = new lang_string('fonts_desc', 'tiny_formatting');
    $fonts = plugininfo::get_available_fonts();

    $fontids = array_keys($fonts);
    $fontfamilies = plugininfo::get_all_font_families();

    \local_debugger\performance\debugger::print_debug('test', 'availFonts', array_values($fontfamilies));

    $setting = new admin_setting_configmulticheckbox(
        'tiny_formatting/fontlist',
        $name,
        $desc,
        array_values($fontfamilies),
        $fontfamilies
    );
    $settings->add($setting);
}
