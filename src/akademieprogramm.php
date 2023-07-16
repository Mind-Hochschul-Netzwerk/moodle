<?php

// const REFERENTENTOOL_API_URL = 'http://referenten:8080/api/ma.php';

const REFERENTENTOOL_API_URL = 'https://referenten.mind-hochschul-netzwerk.de/api/ma.php';
if (empty($_REQUEST['event'])) {
    http_response_code(400);
    die("400 Bad Request");
}

$_REQUEST['event'] = (string)$_REQUEST['event'];

$curl = curl_init(REFERENTENTOOL_API_URL);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['kennzeichen' => $_REQUEST['event']]));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

if ($response === false) {
    echo json_encode([
        'success' => false,
        'meldung' => 'Fehler beim Abfragen der Daten vom dem Referententool ("' . curl_error($curl) . '")!',
    ]);
} else {
    curl_close($curl);
    header('Content-Type: application/json');
    echo json_encode(array_map(function ($programmpunkt) {
        $time = $programmpunkt['programm_beginn'];
        $timeObj = null;
        if (is_array($time)) {
            $timeObj = new \DateTime($time['date'], new \DateTimeZone($time['timezone']));
        } elseif ($time) {
            $timeObj = new \DateTime($time['date']);
        }

        return [
            'titel' => $programmpunkt['vTitel'],
            'abstract' => $programmpunkt['abstract'],
            'beitragsform' => match($programmpunkt['beitragsform']) {
                's' => 'Sonstiges',
                'w' => 'Workshop',
                'r' => 'Rahmenprogramm',
                default => 'Vortrag',
            },
            'beginn' => !$timeObj ? null : $timeObj->format('d.m., H:i'),
            'raum' => $programmpunkt['programm_raum'],
            'referenten' => $programmpunkt['referenten'],
        ];
    }, json_decode($response, true)['data']));
}
