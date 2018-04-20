<?php

/**
 * PHP file for retrive tracklist of webradio on Radionomy
 *
 * @author      Dezodev <dezodev@gmail.com>
 * @license     GNU General Public License v3.0
 * @version     0.0.1
 * @link        https://github.com/RadioPress/radionomy-api-tracklist
 */

/** Variables
*** =========== **/

// Defines API options
$options = [
    'radiouid' => '', // Your radioUID
    'apikey' => '', // Your api key
    'cover' => true, // Get song cover | true = yes, false = no
    'amount' => 20, // Number of songs
    'type' => 'xml' // Type of return | xml or string
];

/** Don't after this line
*** Ne pas modifier après cette ligne
*** =================================== **/

$cacheFile = './cache.txt'; // Path of cache file
$expire = time() - (5.1 * 60); // Cache expire each 5min and 6sec

// Create cache file if not exist
if (!file_exists($cacheFile)) {
    if (!touch($cacheFile)) return http_response_code(500);
}

// Check if cache is not expired
if (filemtime($cacheFile) > $expire) {
    echo file_get_contents($cache); // Return cache file
} else {
    // Validate $options
    if (empty($options['radiouid']) || empty($options['apikey']) || !is_bool($options['cover']) ||
        !is_int($options['amount']) || !in_array($options['type'], ['xml', 'string'])) {
        http_response_code(500);
        die('ERR_OPTIONS_NOT_VALID');
    }

//  http://api.radionomy.com/tracklist.cfm?radiouid=xxx&apikey=xxx&amount=50&type=xml&cover=yes
    $url = 'http://api.radionomy.com/tracklist.cfm?radiouid='.$options['radiouid'].'&apikey='.$apikey;
    if (isset($options['amount'])) $url .= '&amount='.$options['amount'];
    if (isset($options['cover'])) $url .= '&cover='.$options['cover'];
    if (isset($options['type'])) $url .= '&type='.$options['type'];

    echo $url;
}
