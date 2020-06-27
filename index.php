<?php
function getAirConditionerProducts(array $item) : array {
    if (strcmp($item['category'], 'Air Conditioners') === 0) {
        return $item;
    }
}

$client = new \GuzzleHttp\Client();
$url = 'http://wp8m3he1wt.s3-website-ap-southeast-2.amazonaws.com/api/products/1';
$hasNext = true;
$airConditionerProducts = [];

while ($hasNext) {
    $response = $client->request('GET', $url);
    $body = json_decode($response->getBody());
    if ($response->getStatusCode() == 200 && $body['next'] !== null) {
        array_filter($body, "getAirConditionerProducts");
    }
}