<?php

/* 
 * Plugin Name: iT-Guy Smart Banner.
 * Description: a plugin to create a random smart banners on the web site.
 * Version: 0.0.4
 * Author: Tim
 * License: GPL2
 * Author URI: http://www.it-guy.com/
 */

/*  Copyright 2018  Tim Mitra  (email : tim@it-guy.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function itg_smart_banner() {
    $records = get_option('itg_smart_banner_records', array());

    // Extract all app IDs from records
    $app_ids = array();
    foreach ($records as $record) {
        $app_ids[] = $record['app_id'];
    }

    // If no app IDs are found, return empty meta tag
    if (empty($app_ids)) {
        echo "<meta name='apple-itunes-app' content='app_id=398472363, affiliate-data=10l662'>\n";
        return;
    }

    // Randomly select an app ID
    $rand_key = array_rand($app_ids);
    $selected_app_id = $app_ids[$rand_key];

    // Output the meta tag
    echo "<meta name='apple-itunes-app' content='app-id=$selected_app_id, affiliate-data=10l662'>\n";
}

add_action('wp_head', 'itg_smart_banner');

// Define and display the settings page
function itg_smart_banner_settings_page() {
    ?>
    <div class="wrap">
        <h1>ITG Smart Banner Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('itg_smart_banner_settings'); ?>
            <?php do_settings_sections('itg_smart_banner_settings'); ?>
            <table class="form-table" id="itg_smart_banner_records_table">
                <tr valign="top">
                    <th scope="row">Records:</th>
                    <td>
                        <?php
                        $records = get_option('itg_smart_banner_records', array());
                        if (!empty($records)) {
                            foreach ($records as $index => $record) {
                                echo '<div>';
                                echo '<input type="text" name="itg_smart_banner_records['.$index.'][app_name]" value="'.esc_attr($record['app_name']).'" placeholder="App Name" />';
                                echo '<input type="text" name="itg_smart_banner_records['.$index.'][app_id]" value="'.esc_attr($record['app_id']).'" placeholder="App ID" />';
                                echo '</div>';
                            }
                        } else {
                            echo '<div>';
                            echo '<input type="text" name="itg_smart_banner_records[0][app_name]" value="" placeholder="App Name" />';
                            echo '<input type="text" name="itg_smart_banner_records[0][app_id]" value="" placeholder="App ID" />';
                            echo '</div>';
                        }
                        ?>
                        <button type="button" id="itg_smart_banner_add_record">Add Record</button>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('itg_smart_banner_add_record').addEventListener('click', function() {
            var table = document.getElementById('itg_smart_banner_records_table');
            var index = table.querySelectorAll('div').length;
            var newRow = document.createElement('div');
            newRow.innerHTML = '<input type="text" name="itg_smart_banner_records['+index+'][app_name]" value="" placeholder="App Name" />'
                + '<input type="text" name="itg_smart_banner_records['+index+'][app_id]" value="" placeholder="App ID" />';
            table.appendChild(newRow);
        });
    });
    </script>
    <?php
}

// Register and initialize settings
function itg_smart_banner_initialize_settings() {
    register_setting('itg_smart_banner_settings', 'itg_smart_banner_records');
}

// Add settings page to admin menu
function itg_smart_banner_add_settings_page() {
    add_options_page('ITG Smart Banner Settings', 'ITG Smart Banner', 'manage_options', 'itg_smart_banner_settings', 'itg_smart_banner_settings_page');
}

// Hook functions into WordPress
add_action('admin_init', 'itg_smart_banner_initialize_settings');
add_action('admin_menu', 'itg_smart_banner_add_settings_page');
