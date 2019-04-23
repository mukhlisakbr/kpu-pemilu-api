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
        return round(($satu/($satu+$dua))*100, 2);
    } else {
        return round(($dua/($satu+$dua))*100, 2);
    }
}

function progress_angka($data){
    return $data['progress']['proses']."/".$data['progress']['total'];
}

function progress_persen($data){
    $hasil = ($data['progress']['proses'] / $data['progress']['total'])*100;
    return round($hasil, 2);
}

$suara_01 = hitung_suara('satu',$result).'%';
$suara_02 = hitung_suara('dua',$result).'%';
$progress_angka = progress_angka($result);
$progress_persen = progress_persen($result).'%';

$data = array(
    'suara' => array(
        array(
            'nama' => "Ir. H. JOKO WIDODO - Prof. Dr. (H.C) KH. MA'RUF AMIN",
            'perolehan' => $suara_01
        ),
        array(
            'nama' => "H. PRABOWO SUBIANTO - H. SANDIAGA SALAHUDIN UNO",
            'perolehan' => $suara_02
        )
    ),
    'progress' => array(
        'angka' => $progress_angka,
        'persen' => $progress_persen
    )
);

header('Content-Type: application/json');
print_r(json_encode($data));

?>