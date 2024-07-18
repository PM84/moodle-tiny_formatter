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

namespace tiny_formatting;

/**
 * Tiny formatting plugin for Moodle
 *
 * Documentation: {@link https://moodledev.io/docs/apis/plugintypes/tiny}
 *
 * @copyright   2024, ISB Bayern
 * @author      Dr. Peter Mayer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\context;
use editor_tiny\editor;
use editor_tiny\plugin;
use editor_tiny\plugin_with_configuration;



class plugininfo extends plugin implements plugin_with_configuration {
    /**
     * Delivers the configuration to the TinyMCE editor.
     * @param \context $context The context in which the editor is used.
     * @param array $options The options for the editor.
     * @param array $fpoptions The options for the filepicker.
     * @param \editor_tiny\editor|null $editor The editor object.
     * @return array The configuration for the editor.
     */
    public static function get_plugin_configuration_for_context(context $context, array $options, array $fpoptions,
        ?editor $editor = null): array {

        $available_fonts = self::get_available_fonts();
        $fontlist = get_config('tiny_formatting', 'fontlist');
        $fonts = '';

        foreach ($fontlist as $font) {
            $fonts .= $font . '=' . $available_fonts[$font] . ';';
        }

        return [
            'fonts' => $fonts,
        ];
    }

    /**
     * Returns the available fonts for the editor as an array.
     * @return array The available fonts.
     */
    public static function get_available_fonts(): array {
        return [
            'Schulhandschrift' => 'Schulhandschrift',
            'Handschrift' => 'Handschrift',
            'Atkinson Hyperlegible' => 'Atkinson Hyperlegible',
            'Lexend' => 'Lexend',
            'Arial' => 'Arial, Helvetica, sans-serif',
            'Times' => 'Times New Roman, Times, serif',
            'Courier' => 'Courier New, Courier, mono',
            'Georgia' => 'Georgia, Times New Roman, Times, serif',
            'Verdana' => 'Verdana, Geneva, sans-serif',
            'Trebuchet' => 'Trebuchet MS, Helvetica, sans-serif',
            'Amaranth' => 'amaranth',
            'Schulausgangsschrift' => 'bienchen_a',
            'Schulausgangsschrift Unverbunden' => 'bienchen_b',
            'Schulbuch Bayern' => 'SchulbuchBayern',
            'Vereinfachte Ausgangsschrift Liniert' => 'VA2',
            'Vereinfachte Ausgangsschrift' => 'VAu30k',
            'Open Dyslexia' => 'OpenDyslexic',
        ];
    }
}
