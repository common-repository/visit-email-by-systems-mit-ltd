<?php

/*
 * Plugin Name:       Visit Email by Systems Mit Ltd
 * Description:       Sends an email notification whenever someone visits your WordPress website.
 * Author:            Systems Mit Ltd
 * Author URI:        https://systemsmit.com/
 * Version:           1.0
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

add_action( 'admin_menu', 'visit_email_menu' );

function visit_email_menu() {
  add_menu_page(
    'Visit Email by Systems Mit Ltd',
    'Visit Email by Systems Mit Ltd',
    'manage_options',
    'visit-email-by-systems-mit-ltd',
    'visit_email_by_systems_mit_ltd',
  );
}

function visit_email_by_systems_mit_ltd() {
  // Including main menu file
  include 'visit-menu.php';
}

add_action('wp_head', 'visit_email_send_notification');

function visit_email_mobile() {
  return preg_match(
    "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
    $_SERVER["HTTP_USER_AGENT"]
  );
}

function visit_email_get_location() {
  // Request to the IP Geolocation API
  $response = wp_remote_get(
    sprintf('https://ipapi.co/%s/json/', $_SERVER['REMOTE_ADDR'])
  );

  // Checking if the request was successful
  if (is_wp_error($response)) {
    return array();
  }

  // JSON response
  $data = json_decode(wp_remote_retrieve_body($response), true);

  // Checking if the response contained location information
  if (!empty($data['city']) && !empty($data['country_name'])) {
    return array(
      'country' => sanitize_text_field($data['country_name']),
      'region' => sanitize_text_field($data['region']),
      'city' => sanitize_text_field($data['city'])
    );
  }

  return array();
}

function visit_email_send_notification() {

  // Email recipient
  $to = sanitize_email(get_option('admin_email'));

  // Subject of the email
  $subject = sprintf(
    '[%s] New Visit to Your Website',
    sanitize_text_field(get_bloginfo('name'))
  );

  // Message content
  $message = file_get_contents(plugin_dir_path(__FILE__) . 'visit-content.php');

  // Replacing placeholders with actual data
  
  $message = str_replace('[Your Website Name]', sanitize_text_field(get_bloginfo('name')), $message);
  $message = str_replace('[Mobile/Desktop]', visit_email_mobile() ? 'Mobile' : 'Desktop', $message);
  $message = str_replace('[Visitor IP Address]', sanitize_text_field($_SERVER['REMOTE_ADDR']), $message);

  $location = visit_email_get_location();
  if (!empty($location)) {
    $location_str = sprintf('%s, %s, %s', $location['city'], $location['region'], $location['country']);
  } else {
    $location_str = 'N/A';
  }
  $message = str_replace('[Visitor Location - City, Region, Country]', sanitize_text_field($location_str), $message);

  $date_time = new DateTime("now", new DateTimeZone(get_option('timezone_string')));
  $message = str_replace('[Date and Time of Visit]', sanitize_text_field($date_time->format('jS M, Y, h:i A')), $message);
  $message = str_replace('[Page URL]', esc_url(get_permalink()), $message);

  // Additional headers
  $headers = array();
  $headers[] = sprintf(
    'From: %s <%s>',
    sanitize_text_field(get_bloginfo('name')),
    sanitize_email(get_option('admin_email'))
  );
  $headers[] = 'Content-type: text/html; charset=utf-8';

  // Sending the email
  wp_mail($to, sanitize_text_field($subject), $message, $headers);
}

?>