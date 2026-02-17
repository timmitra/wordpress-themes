<?php
/**
 * Plugin Name: Open Tickets Form
 * Description: Simple form to create osTicket tickets via JSON API.
 * Version: 0.0.12
 * Author: Tim Mitra
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the API URL from settings.
 * Converts to localhost if "use local endpoint" is enabled.
 */
function open_tickets_get_api_url() {
    $api_url = get_option('open_tickets_api_url', 'http://api.example.com/tickets/api/tickets.json');
    $use_local = get_option('open_tickets_use_local', false);
    
    // If using local endpoint and on same server, convert to use server hostname
    if ($use_local) {
        // Extract the path from the URL
        $parsed = parse_url($api_url);
        if ($parsed && isset($parsed['path'])) {
            // Use server hostname (osTicket will see server's IP)
            $host = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
            
            // Use localhost/hostname instead of the domain
            $api_url = 'http://' . $host . $parsed['path'];
            if (isset($parsed['query'])) {
                $api_url .= '?' . $parsed['query'];
            }
        }
    }
    
    return $api_url;
}

/**
 * Get the API key from settings.
 */
function open_tickets_get_api_key() {
    return get_option('open_tickets_api_key', '');
}

/**
 * Get the server IP address (for API key validation).
 * Tries to detect the actual server IP, accounting for Cloudflare.
 */
function open_tickets_get_server_ip() {
    // Check if manually set
    $manual_ip = get_option('open_tickets_server_ip', '');
    if (!empty($manual_ip)) {
        return $manual_ip;
    }

    // Try to detect server IP
    // Check Cloudflare headers first (if behind Cloudflare)
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        // This is the visitor IP, not server IP - not what we need
    }

    // Try SERVER_ADDR (server's own IP)
    if (!empty($_SERVER['SERVER_ADDR'])) {
        return $_SERVER['SERVER_ADDR'];
    }

    // Try to get server IP via external service (fallback)
    // Note: This would require an external request, so we'll skip it for now
    // and rely on manual configuration

    return '';
}


/**
 * Render the ticket form and handle submission.
 */
function open_tickets_render_form() {
    $output = '';

    // Handle form submission
    if (!empty($_POST['open_tickets_submit'])) {

        // Check nonce
        if (!isset($_POST['open_tickets_nonce']) || !wp_verify_nonce($_POST['open_tickets_nonce'], 'open_tickets_submit')) {
            $output .= '<div class="open-tickets-error">Security check failed. Please try again.</div>';
        } else {
            // Honeypot check - bots will fill this field, humans won't see it
            if (!empty($_POST['open_tickets_website'])) {
                $output .= '<div class="open-tickets-error">Spam detected. Please try again.</div>';
            } else {
                // Sanitize fields
                $name    = sanitize_text_field($_POST['open_tickets_name'] ?? '');
                $email   = sanitize_email($_POST['open_tickets_email'] ?? '');
                $phone   = sanitize_text_field($_POST['open_tickets_phone'] ?? '');
                $subject = sanitize_text_field($_POST['open_tickets_subject'] ?? '');
                $message = wp_kses_post($_POST['open_tickets_message'] ?? '');

            // Build the JSON payload (based on your example)
            $payload = [
                'alert'       => true,
                'autorespond' => true,
                'source'      => 'API',
                'name'        => $name ?: 'Anonymous User',
                'email'       => $email ?: 'no-reply@example.com',
                'phone'       => $phone,
                'subject'     => $subject ?: 'No subject',
                'ip'          => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                // osTicket API accepts plain text in data URL format
                'message'     => 'data:text/html,' . $message,
            ];

            // Get settings
            $api_url = open_tickets_get_api_url();
            $api_key = open_tickets_get_api_key();
            $server_ip = open_tickets_get_server_ip();

            if (empty($api_url) || empty($api_key)) {
                $output .= '<div class="open-tickets-error">Plugin is not configured. Please contact the administrator.</div>';
            } else {
                // Prepare request headers
                $headers = [
                    'Content-Type' => 'application/json',
                    'X-API-Key'    => $api_key,
                ];

                // Add server IP if configured (some APIs require this)
                if (!empty($server_ip)) {
                    $headers['X-Forwarded-For'] = $server_ip;
                    $headers['X-Real-IP'] = $server_ip;
                }

                $args = [
                    'headers' => $headers,
                    'body'    => wp_json_encode($payload),
                    'timeout' => 15,
                ];

                $response = wp_remote_post($api_url, $args);

                if (is_wp_error($response)) {
                    $output .= '<div class="open-tickets-error">Error contacting ticket system: ' .
                               esc_html($response->get_error_message()) . '</div>';
                } else {
                    $code = wp_remote_retrieve_response_code($response);
                    $body = wp_remote_retrieve_body($response);

                    if ($code >= 200 && $code < 300) {
                        $output .= '<div class="open-tickets-success">Your ticket has been submitted successfully.</div>';
                    } else {
                        $error_msg = 'Ticket API returned an error (HTTP ' . esc_html($code) . '): ' . esc_html($body);
                        
                        // Add helpful message for 401 errors
                        if ($code === 401) {
                            $detected_ip = open_tickets_get_server_ip();
                            $error_msg .= '<br><small>API key validation failed. ';
                            if (empty($detected_ip)) {
                                $error_msg .= 'Server IP not detected. ';
                            } else {
                                $error_msg .= 'Server IP: ' . esc_html($detected_ip) . '. ';
                            }
                            $error_msg .= 'Make sure your osTicket API key is configured for your server\'s IP address. ';
                            $error_msg .= 'If using a DNS-only API subdomain (bypassing Cloudflare), the API key should be configured for ' . esc_html($detected_ip) . '.</small>';
                        }
                        
                        // Add helpful message for 404 errors
                        if ($code === 404) {
                            $error_msg .= '<br><small><strong>Troubleshooting 404 Error:</strong><br>';
                            $error_msg .= 'The API endpoint URL may be incorrect. Try these formats:<br>';
                            $error_msg .= '• <code>' . esc_html($api_url) . '</code> (current)<br>';
                            
                            // Suggest http.php format if not already using it
                            if (strpos($api_url, 'http.php') === false) {
                                $suggested_url = str_replace('/api/tickets.json', '/api/http.php/tickets.json', $api_url);
                                $error_msg .= '• <code>' . esc_html($suggested_url) . '</code> (try this - some osTicket versions require http.php)<br>';
                            }
                            
                            $error_msg .= '<br>If using local endpoint, the server may need the actual hostname instead of 127.0.0.1. ';
                            $error_msg .= 'Try disabling "Use Local Endpoint" or check your server configuration.</small>';
                        }
                        
                        $output .= '<div class="open-tickets-error">' . $error_msg . '</div>';
                        $output .= '<div class="open-tickets-error" style="font-size: 11px; margin-top: 5px;">URL attempted: <code>' . esc_html($api_url) . '</code></div>';
                    }
                }
            }
            } // end honeypot else
        }
    }

    // Render the form
    ob_start();
    ?>

    <form method="post" class="open-tickets-form">
        <?php wp_nonce_field('open_tickets_submit', 'open_tickets_nonce'); ?>

        <!-- Honeypot field - hidden from humans, bots will fill it in -->
        <p style="display:none !important; visibility:hidden; position:absolute; left:-9999px;" aria-hidden="true">
            <label for="open_tickets_website">Leave this field empty</label>
            <input type="text" id="open_tickets_website" name="open_tickets_website" value="" tabindex="-1" autocomplete="off">
        </p>

        <p>
            <label for="open_tickets_name">Name</label><br>
            <input type="text" id="open_tickets_name" name="open_tickets_name" required>
        </p>

        <p>
            <label for="open_tickets_email">Email</label><br>
            <input type="email" id="open_tickets_email" name="open_tickets_email" required>
        </p>

        <p>
            <label for="open_tickets_phone">Phone</label><br>
            <input type="text" id="open_tickets_phone" name="open_tickets_phone">
        </p>

        <p>
            <label for="open_tickets_subject">Subject</label><br>
            <input type="text" id="open_tickets_subject" name="open_tickets_subject" required>
        </p>

        <p>
            <label for="open_tickets_message">Message (HTML allowed)</label><br>
            <textarea id="open_tickets_message" name="open_tickets_message" rows="6" required></textarea>
        </p>

        <p>
            <button type="submit" name="open_tickets_submit" value="1">Submit Ticket</button>
        </p>
    </form>

    <?php
    $output .= ob_get_clean();

    return $output;
}

/**
 * Register the shortcode.
 */
function open_tickets_register_shortcode() {
    add_shortcode('open_ticket_form', 'open_tickets_render_form');
}
add_action('init', 'open_tickets_register_shortcode');

/**
 * Add admin menu item.
 */
function open_tickets_add_admin_menu() {
    add_options_page(
        'Open Tickets Settings',
        'Open Tickets',
        'manage_options',
        'open-tickets',
        'open_tickets_settings_page'
    );
}
add_action('admin_menu', 'open_tickets_add_admin_menu');

/**
 * Register settings.
 */
function open_tickets_register_settings() {
    register_setting('open_tickets_settings', 'open_tickets_api_url', [
        'type'              => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default'           => 'http://api.example.com/tickets/api/tickets.json',
    ]);

    register_setting('open_tickets_settings', 'open_tickets_api_key', [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
    ]);

    register_setting('open_tickets_settings', 'open_tickets_server_ip', [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
    ]);

    register_setting('open_tickets_settings', 'open_tickets_use_local', [
        'type'              => 'boolean',
        'sanitize_callback' => function($value) {
            // Checkboxes send '1' when checked, nothing when unchecked
            return isset($_POST['open_tickets_use_local']);
        },
        'default'           => false,
    ]);

    register_setting('open_tickets_settings', 'open_tickets_test_email', [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_email',
        'default'           => 'test@example.com',
    ]);
}
add_action('admin_init', 'open_tickets_register_settings');

/**
 * Handle checkbox saving (WordPress doesn't save unchecked checkboxes by default).
 */
function open_tickets_save_checkbox($value, $option) {
    if ($option === 'open_tickets_use_local') {
        // If checkbox is not in POST, it was unchecked, so save as false
        if (!isset($_POST['open_tickets_use_local'])) {
            return false;
        }
        return true;
    }
    return $value;
}
add_filter('pre_update_option_open_tickets_use_local', 'open_tickets_save_checkbox', 10, 2);

/**
 * Render the settings page.
 */
function open_tickets_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Show success message if settings were saved
    if (isset($_GET['settings-updated'])) {
        add_settings_error('open_tickets_messages', 'open_tickets_message', 'Settings saved successfully.', 'updated');
    }

    settings_errors('open_tickets_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('open_tickets_settings');
            do_settings_sections('open_tickets_settings');
            ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="open_tickets_api_url">API URL</label>
                    </th>
                    <td>
                        <input type="url" 
                               id="open_tickets_api_url" 
                               name="open_tickets_api_url" 
                               value="<?php echo esc_attr(get_option('open_tickets_api_url', 'http://api.example.com/tickets/api/tickets.json')); ?>" 
                               class="regular-text" 
                               required>
                        <p class="description">
                            The full URL to your osTicket API endpoint.
                            <br><strong>Recommended setup (bypasses Cloudflare):</strong>
                            <ul style="margin-left: 20px; margin-top: 5px;">
                                <li><code>http://api.example.com/tickets/api/tickets.json</code> (uses DNS-only subdomain)</li>
                            </ul>
                            <strong>Alternative formats:</strong>
                            <ul style="margin-left: 20px; margin-top: 5px;">
                                <li><code>https://www.example.com/tickets/api/tickets.json</code> (goes through Cloudflare)</li>
                                <li><code>http://api.example.com/tickets/api/http.php/tickets.json</code> (if http.php is required)</li>
                            </ul>
                            <strong>Note:</strong> Using a DNS-only subdomain (e.g., <code>api.example.com</code>) bypasses Cloudflare and connects directly to your server, which is faster and allows proper API key IP validation.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="open_tickets_use_local">Use Local Endpoint</label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   id="open_tickets_use_local" 
                                   name="open_tickets_use_local" 
                                   value="1" 
                                   <?php checked(get_option('open_tickets_use_local', false), true); ?>>
                            Connect via localhost instead of using the API URL as-is
                        </label>
                        <p class="description">
                            <strong>⚠️ If using a DNS-only API subdomain (e.g., api.example.com), you don't need this option.</strong><br>
                            A DNS-only subdomain already bypasses Cloudflare and connects directly to your server.
                            <br><br>
                            <strong>When to enable this:</strong><br>
                            Only enable if you're using the main domain (www.example.com) and both WordPress and osTicket are on the same server.
                            When enabled, the plugin converts the URL to use localhost/hostname instead of the public domain.
                            <br><br>
                            <strong>How it works:</strong><br>
                            • Converts <code>https://www.example.com/tickets/api/tickets.json</code><br>
                            • To <code>http://<?php echo esc_html($_SERVER['SERVER_NAME'] ?? 'your-hostname'); ?>/tickets/api/tickets.json</code><br>
                            • Connection stays internal to your server<br>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="open_tickets_api_key">API Key</label>
                    </th>
                    <td>
                        <input type="text" 
                               id="open_tickets_api_key" 
                               name="open_tickets_api_key" 
                               value="<?php echo esc_attr(open_tickets_get_api_key()); ?>" 
                               class="regular-text" 
                               required>
                        <p class="description">Your osTicket API key (found in osTicket Admin → Settings → API Keys)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="open_tickets_test_email">Test Email Address</label>
                    </th>
                    <td>
                        <input type="email" 
                               id="open_tickets_test_email" 
                               name="open_tickets_test_email" 
                               value="<?php echo esc_attr(get_option('open_tickets_test_email', 'test@example.com')); ?>" 
                               class="regular-text" 
                               required>
                        <p class="description">
                            Email address to use when testing the API connection. Must be an authorized email in your osTicket system.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="open_tickets_server_ip">Server IP Address</label>
                    </th>
                    <td>
                        <input type="text" 
                               id="open_tickets_server_ip" 
                               name="open_tickets_server_ip" 
                               value="<?php echo esc_attr(get_option('open_tickets_server_ip', '')); ?>" 
                               class="regular-text" 
                               placeholder="Auto-detect">
                        <p class="description">
                            <strong>Note:</strong> This field is for reference/notes only. 
                            <?php
                            $detected = open_tickets_get_server_ip();
                            $server_addr = $_SERVER['SERVER_ADDR'] ?? 'Not detected';
                            echo '<br><strong>Your server IP (SERVER_ADDR): ' . esc_html($server_addr) . '</strong>';
                            ?>
                            <br><br>
                            <strong>If using "Use Local Endpoint" above:</strong> osTicket will see 127.0.0.1 or your server's IP. 
                            Configure your API key for 127.0.0.1 or your server's IP address in osTicket.
                            <br><br>
                            <strong>If NOT using local endpoint and behind Cloudflare:</strong> osTicket will see Cloudflare's IP address. 
                            You must use the API key configured for Cloudflare's IP in osTicket.
                        </p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
        
        <hr>
        <h2>Test Connection</h2>
        <p>Click the button below to test your API connection. This will help identify if the API key and IP address are configured correctly.</p>
        <form method="post" action="">
            <?php wp_nonce_field('open_tickets_test', 'open_tickets_test_nonce'); ?>
            <input type="hidden" name="open_tickets_test" value="1">
            <?php submit_button('Test API Connection', 'secondary', 'test_connection', false); ?>
        </form>
        
        <?php
        // Handle test connection
        if (isset($_POST['open_tickets_test']) && isset($_POST['open_tickets_test_nonce']) && 
            wp_verify_nonce($_POST['open_tickets_test_nonce'], 'open_tickets_test')) {
            
            $api_url = open_tickets_get_api_url();
            $api_key = open_tickets_get_api_key();
            
            if (empty($api_url) || empty($api_key)) {
                echo '<div class="notice notice-error"><p><strong>Error:</strong> API URL and API Key must be configured first.</p></div>';
            } else {
                // Get test email from settings
                $test_email = get_option('open_tickets_test_email', 'test@example.com');
                
                // Create a minimal test payload
                $test_payload = [
                    'alert'       => false,
                    'autorespond' => false,
                    'source'      => 'API',
                    'name'        => 'API Test User',
                    'email'       => $test_email,
                    'subject'     => 'API Connection Test',
                    'ip'          => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                    'message'     => 'data:text/html,This is a test message to verify API connectivity.',
                    'attachments' => [],
                ];
                
                $headers = [
                    'Content-Type' => 'application/json',
                    'X-API-Key'    => $api_key,
                ];
                
                $args = [
                    'headers' => $headers,
                    'body'    => wp_json_encode($test_payload),
                    'timeout' => 15,
                ];
                
                $use_local = get_option('open_tickets_use_local', false);
                $original_url = get_option('open_tickets_api_url', '');
                $parsed = parse_url($api_url);
                $host_used = $parsed['host'] ?? 'unknown';
                $server_ip = $_SERVER['SERVER_ADDR'] ?? 'Not detected';
                
                echo '<div class="notice notice-info">';
                echo '<p><strong>Testing connection to:</strong> <code>' . esc_html($api_url) . '</code></p>';
                if ($use_local) {
                    echo '<p><strong>Mode:</strong> Local endpoint</p>';
                    echo '<p><strong>Host used:</strong> <code>' . esc_html($host_used) . '</code></p>';
                    echo '<p><strong>Original URL:</strong> <code>' . esc_html($original_url) . '</code></p>';
                    echo '<p><strong style="color: #2271b1;">✓ Using hostname method</strong></p>';
                    echo '<p><strong style="color: #d63638;">⚠️ osTicket will see: Your server\'s IP address (<code>' . esc_html($server_ip) . '</code>)</strong></p>';
                    echo '<p>Connecting via hostname (<code>' . esc_html($host_used) . '</code>), but osTicket sees the connection coming from your server\'s IP.</p>';
                    echo '<p>Make sure your API key is configured for <code>' . esc_html($server_ip) . '</code> in osTicket Admin → Settings → API Keys.</p>';
                } else {
                    echo '<p><strong>Mode:</strong> Direct endpoint (using URL as-is)</p>';
                    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                        echo '<p><em>Cloudflare detected - osTicket will see Cloudflare\'s IP for API key validation.</em></p>';
                    } else {
                        echo '<p><em>osTicket will see your server\'s IP: <code>' . esc_html($server_ip) . '</code></em></p>';
                    }
                }
                echo '</div>';
                
                $response = wp_remote_post($api_url, $args);
                
                if (is_wp_error($response)) {
                    echo '<div class="notice notice-error"><p><strong>Connection Error:</strong> ' . esc_html($response->get_error_message()) . '</p></div>';
                } else {
                    $code = wp_remote_retrieve_response_code($response);
                    $body = wp_remote_retrieve_body($response);
                    $response_headers = wp_remote_retrieve_headers($response);
                    
                    if ($code >= 200 && $code < 300) {
                        echo '<div class="notice notice-success"><p><strong>Success!</strong> API connection is working. Response code: ' . esc_html($code) . '</p>';
                        if (!empty($body)) {
                            echo '<p><strong>Response:</strong> <pre>' . esc_html($body) . '</pre></p>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="notice notice-error">';
                        echo '<p><strong>API Error (HTTP ' . esc_html($code) . '):</strong></p>';
                        echo '<p>' . esc_html($body) . '</p>';
                        
                        if ($code === 401) {
                            echo '<p><strong>Troubleshooting 401 Error:</strong></p>';
                            echo '<ul>';
                            echo '<li>The API key may be incorrect</li>';
                            echo '<li>The API key may not be configured for the IP address that osTicket sees</li>';
                            if ($use_local) {
                                echo '<li>Using local endpoint - osTicket should see ' . esc_html($host_used ?? 'localhost') . ' or your server IP</li>';
                            } else {
                                echo '<li>If using Cloudflare, osTicket sees Cloudflare\'s IP, not your server IP</li>';
                            }
                            echo '<li>Check your osTicket Admin → Settings → API Keys to verify the IP address restriction</li>';
                            if (!$use_local) {
                                echo '<li>You may need to create a new API key with the correct IP (Cloudflare\'s IP) or enable "Use Local Endpoint"</li>';
                            }
                            echo '</ul>';
                        }
                        
                        if ($code === 404) {
                            echo '<p><strong>Troubleshooting 404 Error:</strong></p>';
                            echo '<ul>';
                            echo '<li>The API endpoint URL may be incorrect</li>';
                            echo '<li>Some osTicket installations require <code>/api/http.php/tickets.json</code> instead of <code>/api/tickets.json</code></li>';
                            if ($use_local) {
                                echo '<li>Verify both WordPress and osTicket are on the same server and account</li>';
                                echo '<li>Check that osTicket path is correct (e.g., /tickets/ or /support/)</li>';
                            }
                            echo '<li>Verify the osTicket API is enabled and accessible</li>';
                            echo '</ul>';
                            echo '<p><strong>Try these URL formats:</strong></p>';
                            echo '<ul>';
                            echo '<li><code>' . esc_html($api_url) . '</code> (current)</li>';
                            if (strpos($api_url, 'http.php') === false) {
                                $suggested = str_replace('/api/tickets.json', '/api/http.php/tickets.json', $api_url);
                                echo '<li><code>' . esc_html($suggested) . '</code> (try adding http.php)</li>';
                            }
                            if ($use_local && $original_url) {
                                echo '<li><code>' . esc_html($original_url) . '</code> (try public URL instead)</li>';
                            }
                            echo '</ul>';
                        }
                        echo '</div>';
                    }
                }
            }
        }
        ?>
        
        <hr>
        <h2>Setup Guide</h2>
        <div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 15px; margin: 15px 0;">
            <h3 style="margin-top: 0;">Recommended Configuration</h3>
            <ol style="margin-left: 20px;">
                <li><strong>API URL:</strong> <code>http://api.example.com/tickets/api/tickets.json</code><br>
                    <small>Use a DNS-only subdomain (gray cloud in Cloudflare) that bypasses Cloudflare for direct server connection.</small>
                </li>
                <li><strong>Use Local Endpoint:</strong> Leave <strong>unchecked</strong> (not needed when using DNS-only subdomain)</li>
                <li><strong>Test Email:</strong> Set to an authorized email address in your osTicket system</li>
                <li><strong>API Key:</strong> Configure in osTicket for your server IP (<code><?php echo esc_html($_SERVER['SERVER_ADDR'] ?? 'not detected'); ?></code>)</li>
            </ol>
            <p><strong>Why this setup?</strong> A DNS-only subdomain (e.g., <code>api.example.com</code>) connects directly to your server without going through Cloudflare, which allows proper API key IP validation and faster response times.</p>
        </div>
        
        <h2>Usage</h2>
        <p>Add the shortcode <code>[open_ticket_form]</code> to any page or post to display the ticket submission form.</p>
    </div>
    <?php
}