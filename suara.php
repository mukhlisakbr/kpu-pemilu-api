<?php

require __DIR__ . '/vendor/autoload.php';
use \Curl\Curl;
$curl = new Curl();
$curl->get('https://pemilu2019.kpu.go.id');
$curl->setCookieJar('cookies.txt');
$curl->setCookieFile('cookies.txt');
$curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
$curl->get('https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json');

$result = json_decode(json_encode($curl->response), true);

function hitung_suara($out,$data){
    $satu = $data['chart']['21'];
    $dua = $data['chart']['22'];
    if ($out === 'satu') {
        return ($satu/($satu+$dua))*100;
    } else {
        return ($dua/($satu+$dua))*100;
    }
}

$suara_01 = hitung_suara('satu',$result)."%";
$suara_02 = hitung_suara('dua',$result)."%";
$progress_tps = $result['progress']['proses']."/".$result['progress']['total'];

$data = array(
    'suara' => array(
        '01' => array(
            'nama' => "Ir. H. JOKO WIDODO - Prof. Dr. (H.C) KH. MA'RUF AMIN",
            'perolehan' => $suara_01
        ),
        '02' => array(
            'nama' => "H. PRABOWO SUBIANTO - H. SANDIAGA SALAHUDIN UNO",
            'perolehan' => $suara_02
        )
    ),
    'progress' => array(
        $progress_tps
    )
);

header('Content-Type: application/json');
print_r(json_encode($data));

?>