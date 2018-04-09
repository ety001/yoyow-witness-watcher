#!/usr/bin/php
<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$g = [];
init();

while(1) {
    entry();
    sleep(10);
}

function init() {
    global $g;
    // init data
    $g = [
        'uid' => getenv('YOYOID'),
        'url' => getenv('APIURL') ? getenv('APIURL') : 'http://172.20.99.2:9999/rpc',
        'total_missed' => 0,
        'pub_keys' => explode(',', getenv('PUBKEYS')),
        'pass' => getenv('PASS'),
    ];
    if (!$g['uid']) {
        echo "Need UID\n";
        exit();
    }
    if (!$g['pass']) {
        echo "Need Password\n";
        exit();
    }
    // init pubkey index
    $g['max_error_times'] = count($g['pub_keys']);
    if ($g['max_error_times'] === 0) {
        echo "Need PUBKEYs\n";
        exit();
    }
    $g['current_pubkey_index'] = 0;
    // get witness info
    $witness = get_witness($g['uid']);
    if (isset($witness['total_missed'])) {
        $g['total_missed'] = $witness['total_missed'];
    } else {
        echo "Get total_missed failed!\n";
        exit();
    }
}

function entry() {
    global $g;
    $witness = get_witness($g['uid']);
    if ($witness) {
        $time = date('Y-m-d H:i:s', time());
        echo "total_produced: {$witness['total_produced']}, total_missed: {$witness['total_missed']}\n";
        echo "$time\n\n\n";
        if (isset($witness['total_missed'])) {
            $total_missed = $witness['total_missed'];
            if ($total_missed > $g['total_missed']) {
                // switch node
                $next_index = $g['current_pubkey_index'] + 1;
                if ($next_index == $g['max_error_times']) {
                    offline();
                    echo 'Witness has been off line!!!!!'."\n";
                    exit();
                } else {
                    if (isset($g['pub_keys'][$next_index])) {
                        online($g['pub_keys'][$next_index]);
                        echo 'Has switched to '.$g['pub_keys'][$next_index]."!!!!\n\n";
                        $g['current_pubkey_index'] = $next_index;
                    } else {
                        offline();
                        echo 'Witness has been off line!!!!!'."\n";
                        exit();
                    }
                }

                $g['total_missed'] = $total_missed;
            }
        }
    } else {
        echo "get witness empty\n";
    }
}

function get_data($data, $url = 'http://172.20.99.2:9999/rpc') {
    try {
        $client = new Client();
        $r = $client->request('POST', $url, [
            'json' => $data
        ]);
        $body = $r->getBody();
        $result = $body->getContents();
        return json_decode($result, true);
    } catch (Exception $e) {
        echo $e->getMessage()."\n";
    }
}

function get_full_account($uid) {
    global $g;
    $data = [
        'jsonrpc' => '2.0',
        'method' => 'get_full_account',
        'params' => [
            $uid 
        ],
        'id' => 1
    ];
    return get_data($data, $g['url']);
}

function get_witness($uid) {
    $r = get_full_account($uid);
    return isset($r['result']['witness']) ? $r['result']['witness'] : null;
}

function offline() {
    global $g;
    $data = [
        'jsonrpc' => '2.0',
        'method' => 'update_witness',
        'params' => [
            $g['uid'],
            'YYW1111111111111111111111111111111114T1Anm',
            null,
            null,
            null,
            true,
        ],
        'id' => 1
    ];
    return get_data($data, $g['url']);
}

function online($public_key) {
    global $g;
    $data = [
        'jsonrpc' => '2.0',
        'method' => 'update_witness',
        'params' => [
            $g['uid'],
            $public_key,
            null,
            null,
            null,
            true,
        ],
        'id' => 1
    ];
    return get_data($data, $g['url']);
}

function unlock() {
    global $g;
    $data = [
        'jsonrpc' => '2.0',
        'method' => 'unlock',
        'params' => [
            $g['pass']
        ],
        'id' => 1
    ];
    return get_data($data, $g['url']);
}

function lock() {
    global $g;
    $data = [
        'jsonrpc' => '2.0',
        'method' => 'lock',
        'params' => [
        ],
        'id' => 1
    ];
    return get_data($data, $g['url']);
}
