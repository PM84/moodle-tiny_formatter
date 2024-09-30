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
 * Tiny formatting plugin for Moodle
 *
 * Documentation: {@link https://moodledev.io/docs/apis/plugintypes/tiny}
 *
 * @copyright   2024, ISB Bayern
 * @author      Dr. Peter Mayer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package tiny_formatting
 */

namespace tiny_formatting;

use core\context;
use editor_tiny\editor;
use editor_tiny\plugin;
use editor_tiny\plugin_with_configuration;


/**
 * Tiny formatting plugin for Moodle
 *
 * Documentation: {@link https://moodledev.io/docs/apis/plugintypes/tiny}
 *
 * @copyright   2024, ISB Bayern
 * @author      Dr. Peter Mayer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package tiny_formatting
 */
class plugininfo extends plugin implements plugin_with_configuration {
    /**
     * Delivers the configuration to the TinyMCE editor.
     * @param \context $context The context in which the editor is used.
     * @param array $options The options for the editor.
     * @param array $fpoptions The options for the filepicker.
     * @param \editor_tiny\editor|null $editor The editor object.
     * @return array The configuration for the editor.
     */
    public static function get_plugin_configuration_for_context(
        context $context,
        array $options,
        array $fpoptions,
        ?editor $editor = null
    ): array {

        $fontids = explode(',', get_config('tiny_formatting', 'fontlist'));
        $allowedfonts = array_intersect_key(self::get_all_font_families(), array_flip($fontids));
        $fallbacks = self::get_all_font_fallbacks();

        $fonts = [];
        foreach ($allowedfonts as $fontid => $fontfamily) {
            $fonts[$fontid] = $fontfamily . "=" . $fallbacks[$fontid];
        }

        // Get fonts from theme (currently boost_union only).
        $themenames = array_keys(\core_component::get_plugin_list('theme'));
        if (in_array('boost_union', $themenames)) {
            $customfonts = self::get_custom_fonts_from_theme_boost_union();
            $fonts = array_merge($customfonts, $fonts);
        }
        asort($fonts);

        return [
            'fonts' => join(';', array_values($fonts)),
        ];
    }

    /**
     * Get the custom fonts uploaded in theme_boost_union.
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function get_custom_fonts_from_theme_boost_union() {
        $customfonts = [];
        if(function_exists('theme_boost_union_get_customfonts_templatecontext')) {
            $filesforcontext = theme_boost_union_get_customfonts_templatecontext();
            // Get fontname from filename.
            foreach ($filesforcontext as $entry) {
                $customfonts[] = pathinfo($entry['filename'], PATHINFO_FILENAME);
            }
        }
        return $customfonts;
    }

    /**
     * Returns the available fonts for the editor as an array.
     * @return array The available fonts.
     */
    public static function get_available_fonts(): array {
        return [
            1 => [
                'fontfamily' => 'Schulhandschrift',
                'fallback' => 'Schulhandschrift',
            ],
            2 => [
                'fontfamily' => 'Handschrift',
                'fallback' => 'Handschrift',
            ],
            3 => [
                'fontfamily' => 'Atkinson Hyperlegible',
                'fallback' => 'Atkinson Hyperlegible',
            ],
            4 => [
                'fontfamily' => 'Lexend',
                'fallback' => 'Lexend',
            ],
            5 => [
                'fontfamily' => 'Arial',
                'fallback' => 'Arial, Helvetica, sans-serif',
            ],
            6 => [
                'fontfamily' => 'Times',
                'fallback' => 'Times New Roman, Times, serif',
            ],
            7 => [
                'fontfamily' => 'Courier',
                'fallback' => 'Courier New, Courier, mono',
            ],
            8 => [
                'fontfamily' => 'Georgia',
                'fallback' => 'Georgia, Times New Roman, Times, serif',
            ],
            9 => [
                'fontfamily' => 'Verdana',
                'fallback' => 'Verdana, Geneva, sans-serif',
            ],
            10 => [
                'fontfamily' => 'Trebuchet',
                'fallback' => 'Trebuchet MS, Helvetica, sans-serif',
            ],
            11 => [
                'fontfamily' => 'Amaranth',
                'fallback' => 'amaranth',
            ],
            12 => [
                'fontfamily' => 'Schulausgangsschrift',
                'fallback' => 'bienchen_a',
            ],
            13 => [
                'fontfamily' => 'Schulausgangsschrift Unverbunden',
                'fallback' => 'bienchen_b',
            ],
            14 => [
                'fontfamily' => 'Schulbuch Bayern',
                'fallback' => 'SchulbuchBayern',
            ],
            15 => [
                'fontfamily' => 'Vereinfachte Ausgangsschrift Liniert',
                'fallback' => 'VA2',
            ],
            16 => [
                'fontfamily' => 'Vereinfachte Ausgangsschrift',
                'fallback' => 'VAu30k',
            ],
            17 => [
                'fontfamily' => 'Open Dyslexia',
                'fallback' => 'OpenDyslexic',
            ],
        ];
    }

    /**
     * Get the fontfamilies
     * @return array
     */
    public static function get_all_font_families(): array {

        $fontids = array_keys(self::get_available_fonts());
        $fontfamilies = array_map(fn ($x) => $x['fontfamily'], self::get_available_fonts());

        return array_combine($fontids, $fontfamilies);
    }

    /**
     * Get the font fallbacks
     * @return array
     */
    public static function get_all_font_fallbacks(): array {

        $fontids = array_keys(self::get_available_fonts());
        $fontfallbacks = array_map(fn ($x) => $x['fallback'], self::get_available_fonts());

        return array_combine($fontids, $fontfallbacks);
    }
}
