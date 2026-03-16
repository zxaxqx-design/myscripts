<?php

$url = "https://www.kuvalda.ru/catalog/1968-bytovye-benzinovye-generatory/product-127694/";

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36",
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_ENCODING => "", // поддержка gzip
]);

$html = curl_exec($ch);

if ($html === false) {
    echo "cURL error: " . curl_error($ch);
} else {
    file_put_contents("page.html", $html);
    echo "HTML сохранён";
}

curl_close($ch);