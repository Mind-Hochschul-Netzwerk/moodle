<?php

$cacheDir = 'docker/build-cache';
$downloadDir = '/tmp/build';
$jsonFile = __DIR__ . '/dependencies.json';
$lockFile = __DIR__ . '/dependencies.lock.json';

if (is_dir($cacheDir)) {
    $downloadDir = $cacheDir;
}

if (isset($argv[1]) && is_dir($argv[1])) {
    $downloadDir = $argv[1];
}

if (!is_dir($downloadDir)) {
    mkdir($downloadDir, recursive: true);
}

if (isset($argv[2]) && is_file($argv[2])) {
    $jsonFile = $argv[2];
}

if (!is_file($jsonFile)) {
    error_log('missing file: ' . $jsonFile);
    exit(1);
}

$list = json_decode(file_get_contents($jsonFile), true);
$lock = is_file($lockFile) ? json_decode(file_get_contents($lockFile), true) : [];
$updateLockfile = false;
$newLock = [];

foreach ($list as $name => $entry) {
    echo "$name: ";
    $filename = basename($entry['url']);
    $savePath = "$downloadDir/$filename";

    if (!file_exists($savePath)) {
        "downloading $entry[url]... ";

        $ch = curl_init($entry['url']);
        $fp = fopen($savePath, 'w');

        curl_setopt_array($ch, [
            CURLOPT_FILE => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_USERAGENT => 'Wget/1.21.1 (linux-gnu)', // mimic wget
            CURLOPT_REFERER => $entry['url'],
            CURLOPT_HTTPHEADER => [
                'Accept: */*',
                'Connection: keep-alive'
            ],
        ]);
        if (!curl_exec($ch)) {
            error_log("cURL error: " . curl_error($ch));
            error_log("url was: " . $entry['url']);
            unlink($savePath);
            exit(1);
        }
        curl_close($ch);
        fclose($fp);
        echo "done... ";
    } else {
        echo "cached... ";
    }

    $checksum = hash_file('sha256', $savePath);

    if (!isset($lock[$filename])) {
        echo "updating checksum\n";
        $updateLockfile = true;
    } elseif ($lock[$filename] === $checksum) {
        echo "checksum okay\n";
    } else {
        error_log("unexpected checksum!");
        echo "expected: " . $lock[$filename] . "\n";
        echo "found: " . $checksum . "\n";
        echo "downloaded file starts with: " . substr(file_get_contents($savePath), 300);
        echo "\n";
        exit(1);
    }

    $newLock[$filename] = $checksum;
}

if ($updateLockfile) {
    file_put_contents($lockFile, json_encode($newLock, JSON_PRETTY_PRINT));
}

if (in_array('--install', $argv)) {
    foreach ($list as $name => $entry) {
        echo "Installing $name: ";
        $filename = basename($entry['url']);
        $run = $entry['run'];
        if (!is_array($run)) {
            $run = [$run];
        }
        $savePath = "$downloadDir/$filename";

        foreach ($run as $command) {
            $command = str_replace('$file', $savePath, $command);
            echo "$command; ";
            exec($command, $output, $code);
            if ($code !== 0) {
                error_log("command $command has exit code: $code\nOutput: $output\n");
                exit(1);
            }
        }

        echo "done\n";
    }
}
