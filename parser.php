<?php
require('simple_html_dom.php');

$table = array();

$html = file_get_html('http://banki.tomsk.ru/pages/41/');

$cbr_usd = $html->find('table.cbr-kurs tr td', 1)->plaintext;
$cbr_eur = $html->find('table.cbr-kurs tr td', 2)->plaintext;

$cbr_usd = explode(' ', $cbr_usd);
$cbr_usd = $cbr_usd[0];

$cbr_eur = explode(' ', $cbr_eur);
$cbr_eur = $cbr_eur[0];

$trs = $html->find('table.tomskcur tr');

$result = array();

$result['cbr_usd'] = $cbr_usd;
$result['cbr_eur'] = $cbr_eur;

foreach($trs as $row) {
	$bestPrices = $row->find('td.curplus');
	if (!empty($bestPrices)) {
		foreach ($bestPrices as $bestPrice) {
			$bestPrice = $bestPrice->plaintext;
			$usdBuyPrice = $row->find('td', 1)->plaintext;
			$usdSellPrice = $row->find('td', 2)->plaintext;
			$eurBuyPrice = $row->find('td', 3)->plaintext;
			$eurSellPrice = $row->find('td', 4)->plaintext;

			$bank = $row->find('td', 0);

			if (strcmp($bestPrice, $usdBuyPrice) === 0) {
				// echo 'best usd buy price ' . $bank->plaintext, ' ', $bestPrice.PHP_EOL;
				$result['usd_buy'] = array($bank->plaintext,$bestPrice);
			}

			if (strcmp($bestPrice, $usdSellPrice) === 0) {
				// echo 'best usd sell price ' . $bank->plaintext, ' ', $bestPrice.PHP_EOL;
				$result['usd_sell'] = array($bank->plaintext,$bestPrice);
			}

			if (strcmp($bestPrice, $eurBuyPrice) === 0) {
				// echo 'best eur buy price ' . $bank->plaintext, ' ', $bestPrice.PHP_EOL;
				$result['eur_buy'] = array($bank->plaintext,$bestPrice);
			}

			if (strcmp($bestPrice, $eurSellPrice) === 0) {
				// echo 'best eur sell price ' . $bank->plaintext, ' ', $bestPrice.PHP_EOL;
				$result['eur_sell'] = array($bank->plaintext,$bestPrice);
			}
		}
	}
}

file_put_contents('data.dat',json_encode($result));

// чтение из файла
// $re = json_decode((file_get_contents('data.dat')), true);
// print_r($re);