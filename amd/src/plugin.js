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
 * Tiny formatting for Moodle
 *
 * @module      tiny_formatting/plugin
 * @copyright   2024, ISB Bayern
 * @author      Dr. Peter Mayer
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {component, pluginName} from 'tiny_formatting/common';
import {getTinyMCE} from 'editor_tiny/loader';
import {getPluginMetadata} from 'editor_tiny/utils';
import {getPluginOptionName} from 'editor_tiny/options';

const availableFontsName = getPluginOptionName(pluginName, 'fonts');

export const getAvailableFonts = (editor) => editor.options.get(availableFontsName);

// Setup the tiny_formatting Plugin.
const configureMenu = (menu) => {
    if (menu.format.items.match(/\bblocks\b/)) {
        menu.format.items = menu.format.items.replace(
            /(\bblocks\b)/,
            ' styles $1 fontfamily fontsize',
        );
    } else {
        menu.format.items = `${menu.format.items} | fontfamily fontsize`;
    }

    if (menu.format.items.match(/\blanguage\b/)) {
        menu.format.items = menu.format.items.replace(
            /(\blanguage\b)/,
            ' forecolor backcolor | $1',
        );
    } else {
        menu.format.items = `${menu.format.items} | forecolor backcolor`;
    }
    return menu;
};

// eslint-disable-next-line no-async-promise-executor
export default new Promise(async (resolve) => {
    // Note: The PluginManager.add function does not support asynchronous configuration.
    // Perform any asynchronous configuration here, and then call the PluginManager.add function.
    const [
        tinyMCE,
        pluginMetadata,
    ] = await Promise.all([
        getTinyMCE(),
        getPluginMetadata(component, pluginName),
    ]);

    // Reminder: Any asynchronous code must be run before this point.
    tinyMCE.PluginManager.add(pluginName, () => {
        return pluginMetadata;
    });

    resolve([pluginName, {
        configure: (instanceConfig, options) => {
            let styleelements = document.querySelectorAll('head link[href*="styles.php"]');
            styleelements.forEach(el => {
                instanceConfig.content_css.push(el.href);
            });

            return {
                menu: configureMenu(instanceConfig.menu),
                font_family_formats: options.plugins['tiny_formatting/plugin'].config.fonts,
            };
        }
    }]);
});
