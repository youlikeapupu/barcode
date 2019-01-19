<?php

include('src/BarcodeGenerator.php');
include('src/BarcodeGeneratorPNG.php');
include('src/BarcodeGeneratorSVG.php');
include('src/BarcodeGeneratorJPG.php');
include('src/BarcodeGeneratorHTML.php');

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
file_put_contents('tests/verified-files/081231723897-ean13.svg', $generatorSVG->getBarcode('081231723897', $generatorSVG::TYPE_EAN_13));

$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
file_put_contents('tests/verified-files/081231723897-code128.html', $generatorHTML->getBarcode('081231723897', $generatorHTML::TYPE_CODE_128));

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
file_put_contents('tests/verified-files/0049000004632-ean13.svg', $generatorSVG->getBarcode('0049000004632', $generatorSVG::TYPE_EAN_13));

$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
// echo $generator->getBarcode('D01-A010006', $generator::TYPE_CODE_39);

$id ='D01-A010001';
$model ='HTC U11+(抗衝)';
$name = '雙魚座';

$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
$barcode = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($id, $generator::TYPE_CODE_39)) . '" width="350" height="90">';

?>

<div>
	<p><?=$model?></p>
	<p><?=$name?></p>
	<div><?=$barcode?></div>
</div>
