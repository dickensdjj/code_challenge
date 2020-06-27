<?php
use GuzzleHttp\Client;

require_once 'vendor/autoload.php';

/**
 * Callback function to return only item's category is Air Conditioners
 * @param array $item
 * @return mixed
 */
function getAirConditionerProducts(array $item) {
    if (strcmp($item['category'], 'Air Conditioners') === 0) {
        return $item;
    }
}

// init Guzzle Client
$client = new Client();

// server configuration
$host = 'http://wp8m3he1wt.s3-website-ap-southeast-2.amazonaws.com';
$url = '/api/products/1';
$hasNext = true;

// ac product store in array
$airConditionerProducts = [];

// variables used for calculate cubic weight
$count = 0;
$totalAvgCubicWeight = 0;

while ($hasNext) {
    $response = $client->request('GET', $host . $url);
    $body = json_decode($response->getBody(), true);
    if ($response->getStatusCode() == 200 && $body['next'] !== null) {
        $filtered_result = array_filter($body['objects'], "getAirConditionerProducts");
        if (count($filtered_result) > 0) {
            $airConditionerProducts = array_merge($airConditionerProducts, $filtered_result);
        }
        $url = $body['next'];
    } else {
        $hasNext = false;
    }
}

if (count($airConditionerProducts) === 0) {
    echo 'No ac products are return, please check with the api admin';
}

foreach($airConditionerProducts as $product) {
    if (array_key_exists('size', $product)) {
        $totalAvgCubicWeight += ($product['size']['width'] / 100 * $product['size']['length'] / 100 * $product['size']['height'] /100 )* 250;
        $count += 1;
    }
}

$avgCubicWeight = $totalAvgCubicWeight / $count;

echo $avgCubicWeight;
