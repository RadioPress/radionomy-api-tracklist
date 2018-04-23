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
    'type' => 'json' // Type of return | json, xml or string | By default this tool use json
];

/** Don't edit after this line
*** Ne pas modifier après cette ligne
*** =================================== **/

$cacheFile = './cache.txt'; // Path of cache file
$expire = time() - (5.1 * 60); // Cache expire each 5min and 6sec

// Check if cache is not expired
if (file_exists($cacheFile) && filemtime($cacheFile) > $expire) {
    echo file_get_contents($cacheFile); // Return cache file
} else {
    // Create cache file if not exist
    if (!file_exists($cacheFile)) {
        if (!touch($cacheFile)) return http_response_code(500);
    }

    // Validate $options
    if (empty($options['radiouid']) || empty($options['apikey']) || !is_bool($options['cover']) ||
        !is_int($options['amount']) || !in_array($options['type'], ['xml', 'string', 'json'])) {
        http_response_code(500);
        die('ERR_OPTIONS_NOT_VALID');
    }

    // Define url of radionomy API with options
    $coverUrl = ($options['cover']) ? 'yes' : 'no';
    $typeUrl = ($options['type'] == 'json') ? 'xml' : $options['type'];

    $url = 'http://api.radionomy.com/tracklist.cfm?radiouid='.$options['radiouid'].'&apikey='.$options['apikey'];
    $url .= '&amount='.$options['amount'];
    $url .= '&cover='. $coverUrl;
    $url .= '&type='.$typeUrl;

    // die($url);

    // Get content from radionomy
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
    ));
    $content = curl_exec($curl);

    // Test if the file was written
    if ($content === false) {
        $err = curl_error($curl);
        http_response_code(500);
        die('ERR_GET_CONTENT : '.$err);
    }
    curl_close($curl);

    if ($options['type'] == 'json') {
        $xml = simplexml_load_string($content);
        $content = json_encode($xml);
    }

    // Write content in cache file and display the content
    if (file_put_contents($cacheFile, $content) !== false) {
        echo $content;
    } else {
        http_response_code(500);
        die('ERR_WRITE_CACHE_FILE');
    }

}
