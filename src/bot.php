#!/usr/bin/php
<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$testSwitch = false;

if ($testSwitch) {
    $testMissed = 3;
}
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
        echo "Need YOYOID\n";
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
    var_dump('global value in init');
    var_dump($g['pub_keys'], $g['uid'], $g['url'], $g['total_missed']);
}

function entry() {
    global $g;
    $witness = get_witness($g['uid']);
    echo 'witness_info: ['.json_encode($witness)."]\n";
    if ($witness) {
        $time = date('Y-m-d H:i:s', time());
        echo "total_produced: {$witness['total_produced']}, total_missed: {$witness['total_missed']}\n";
        echo "$time\n";
        if (isset($witness['total_missed'])) {
            $total_missed = $witness['total_missed'];
            if ($total_missed > $g['total_missed']) {
                // switch node
                echo 'start switch node'."\n";
                $next_index = $g['current_pubkey_index'] + 1;
                if ($next_index == $g['max_error_times']) {
                    echo 'all nodes have down'."\n";
                    unlock();
                    offline();
                    lock();
                    $msg = 'Witness has been off line!!!!!';
                    echo $msg."\n";
                    notify($msg);
                    exit();
                } else {
                    if (isset($g['pub_keys'][$next_index])) {
                        echo 'switching to '.$g['pub_keys'][$next_index]."\n";
                        unlock();
                        online($g['pub_keys'][$next_index]);
                        lock();
                        $msg = 'Has switched to '.$g['pub_keys'][$next_index];
                        echo $msg . "!!!!\n";
                        notify($msg);
                        $g['current_pubkey_index'] = $next_index;
                    } else {
                        echo 'next_index error'."\n";
                        unlock();
                        offline();
                        lock();
                        $msg = 'Witness has been off line!!!!! next_index_error';
                        echo $msg . "\n";
                        exit();
                    }
                }
                echo "\n\n";
                $g['total_missed'] = $total_missed;
            }
        }
    } else {
        echo "get witness empty\n\n\n";
    }
}

function get_data($data, $url = 'http://172.20.99.2:9999/rpc') {
    global $g;
    try {
        $client = new Client();
        $r = $client->request('POST', $url, [
            'json' => $data
        ]);
        $body = $r->getBody();
        $result = $body->getContents();
        return json_decode($result, true);
    } catch (Exception $e) {
        var_dump($e->getMessage());
        var_dump('data', $data);
        var_dump($g['pub_keys'], $g['uid'], $g['url'], $g['total_missed']);
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
    global $testSwitch;
    if ($testSwitch) {
        global $testMissed;
        $test = testdata($testMissed);
        $testMissed += 1;
        return $test;
    }
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

function notify($msg) {
    // todo
    return;
}
function testdata($missed=3) {
    $test = array (
      'id' => '1.5.118',
      'account' => 485699321,
      'name' => 'yoyo485699321',
      'sequence' => 1,
      'is_valid' => true,
      'signing_key' => 'YYW7TSRLZ9EXZpZqk25atoL2s37Kt31qa7qi78ZR368kCN969rFiT',
      'pledge' => '4800000000',
      'pledge_last_update' => '2018-04-09T16:27:36',
      'average_pledge' => '4769633426',
      'average_pledge_last_update' => '2018-04-10T08:29:33',
      'average_pledge_next_update_block' => 6207738,
      'total_votes' => '1012502907669',
      'by_pledge_position' => '98757650066558543166652951073387559156',
      'by_pledge_position_last_update' => '13192656611235436406615014290468948',
      'by_pledge_scheduled_time' => '13192707249239499182972333861404678',
      'by_vote_position' => '0',
      'by_vote_position_last_update' => '8327363728159655234190077117543737',
      'by_vote_scheduled_time' => '8327364064240040133040155758498251',
      'last_confirmed_block_num' => 6207144,
      'last_aslot' => 6219398,
      'total_produced' => 1137,
      'total_missed' => 3,
      'url' => 'https://github.com/ety001',
    );
    $test['total_missed'] = $missed;
    return $test;
}
