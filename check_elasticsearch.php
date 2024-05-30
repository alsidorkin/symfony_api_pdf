<?php
$url = 'http://localhost:9200';

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Ошибка запроса: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);

    if($data) {
        echo 'Elasticsearch работает!' . PHP_EOL;
        echo 'Информация о кластере:' . PHP_EOL;
        echo 'Имя узла: ' . $data['name'] . PHP_EOL;
        echo 'Имя кластера: ' . $data['cluster_name'] . PHP_EOL;
        echo 'UUID кластера: ' . $data['cluster_uuid'] . PHP_EOL;
        echo 'Версия Elasticsearch: ' . $data['version']['number'] . PHP_EOL;
        echo 'Слоган: ' . $data['tagline'] . PHP_EOL;
    } else {
        echo 'Не удалось получить данные от Elasticsearch.';
    }
}

curl_close($ch);
