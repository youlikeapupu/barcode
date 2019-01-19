<?php

include('src/BarcodeGenerator.php');
include('src/BarcodeGeneratorPNG.php');

// print_r($_POST);
// if(isset($_POST)){
	// foreach ($_POST as $k => $val) {
		// echo '$k =>'. $k .' - '. $val.'<br>';
	// }
// }

// if(isset($_FILES)){
// 	foreach ($_FILES as $k2 => $val2) {
// 		echo '$k2 =>'. $k2 .'<br>';
// 		print_r($val2);
// 		echo '<br>';
// 	}
// }

$arr_items = array();
$arr_items2 = array();

if(isset($_FILES["txt_file"])){

	$uploadOk = 1;

	$txt_file = $_FILES["txt_file"];
	$tmp_name = $txt_file["tmp_name"];

	$f_size = $txt_file["size"] / 1024;

	if($f_size > 500){
		echo '<span style="color:red;">上傳檔案大小超過500KB!!</span>';
		exit;
	}

	if($txt_file["type"] != 'text/plain' && $txt_file["type"] != 'application/octet-stream'){
		echo '<span style="color:red;">上傳檔案類型有誤!!</span>';
		exit;
	}

	$check = getimagesize($tmp_name);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        // echo "File is not an image.";
        $uploadOk = 0;
    }

    // print_r($_FILES["txt_file"]['tmp_name']);

    // 讀取資料
    $file_path = $tmp_name;
	$myfile = fopen($file_path, "r");
	$arr_items = array();
	$i = 0;
	while(!feof($myfile)) {
	  	$data = fgets($myfile);
	  	$arr_items[$i] = $data;
	  	$i++;
	}
	fclose($myfile);

	$i2 = 0;
	foreach ($arr_items as $k => &$val) {
	  	if($k > 0 ){
			$items = new \stdClass();
	  		$arr = explode(',', $val);
			$items->id = $arr[0];
		    $items->model = empty($arr[1]) ? '' : $arr[1];
		    $items->name = empty($arr[2]) ? '' : $arr[2];
		  	$arr_items2[$i2] = $items;
		  	$i2++;
	    }
	}
}

// 資料長度
$len = count($arr_items2);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Barcode</title>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<style>

.warp{
	width: 100%;
	height: 100%;
	display: inline-block;
}

.items{
	<?
	/*min-width: 600px;
	width: 50%;
	min-width: 650px;
	width: 650px;
	height: 550px;*/
?>
	min-width: 472px;
	width: 472px;
	height: 399px;
	margin: 0 auto;
	float: left;
	border-bottom: dotted 1px #ccc;
    border-left: dotted 1px #ccc;
    border-top: dotted 1px #ccc;
    border-right: dotted 1px #ccc;
	cursor: pointer;
}
.items >.no{
	padding: 2px;
	cursor: pointer;
}
.model{
	<?
	/*font-size: 58px;
	font-size: 44px;*/
	?>
	font-size: 32px;
	padding: 3% 0 0 3%;
	letter-spacing: 2px;
}
.name{
	<?
	/*font-size: 50px;
	font-size: 38px;*/
	?>
	font-size: 32px;
	padding: 0 0 8% 3%;
}
.barcode{
	text-align: center;
}
.id{
	/*font-size: 66px;*/
	font-size: 46px;
	padding: 20px 0 0 0;
	letter-spacing: 1px;
	text-align: center;
}

.f_upload {
	width: 300px;
	margin: 0 auto;
	padding: 10px 0 20px 0;
}

#f1 > h3 {
	text-align: center;
}

.f_upload > [type=file]{
	display: none;
}

.f_upload > button {
	width: 100%;
}

.f_name {
	color: red;
	font-size: 18px;
}

.info {
	text-align: center;
    font-size: 16px;
    padding: 12px;
}

</style>

</head>
<body>
    <div class="warp">
    	<form name="f1" id="f1" method="post" enctype="multipart/form-data" action="barcode_v2.php">

    	<h3>Barcode 條碼產生</h3>
    	<div class="f_upload">
    		<input type="file" name="txt_file" id="txt_file">
    		<span class="f_name"></span>
    		<button type="button" class="btn btn-primary btn-lg" id="upfile">TXT 檔案上傳</button>
    		<button type="button" class="btn btn-primary btn-lg" id="down">一鍵下載</button>
    	</div>
    	<div class="info">上傳資料筆數：<span class="info_len"><?=$len?></span></div>

	    <?for ($i=0; $i < $len ; $i++) {?>
	    <?
	    	$self = $arr_items2[$i];
	    	$id = $self->id;
	    	$model = $self->model;
	    	$name = $self->name;

	    	$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
			$base64 = base64_encode($generator->getBarcode($id, $generator::TYPE_CODE_39));
			$barcode = '<img src="data:image/png;base64,' . $base64 . '" width="430" height="90">';
	    ?>
	    <div class="items" title="點選下載(<?=$id?>)圖片">
	    	<p class="no"><!-- <?=$i+1?> --></p>
			<p class="model"><?=$model?></p>
			<p class="name"><?=$name?></p>
			<div class="barcode"><?=$barcode?></div>
			<p class="id"><?=$id?></p>
		</div>
		<?}?>
		</form>
	</div>
</body>
</html>

<script>

// 檔案上傳 button
$('#upfile').click(function(e) {
	var txt_file = $('#txt_file');

	// 點選上傳檔案
	txt_file.click();

	txt_file.change("change", function(){
	    var file = this.files[0],
	        fileName = file.name,
	        fileSize = file.size / 1024;

	    if(fileSize > 500){
	    	alert('上傳檔案大小超過500KB !!');
	    }
<?
	    // console.log('file.type => ' + file.type);
	    // return false;

	    // 限制 txt 類型
	    // if(file.type === 'text/plain' || file.type === 'text/csv'){
?>
	   		$('.f_name').eq(0).text(fileName);
		    document.getElementById("f1").submit();
<?
	    // }
	    // else{
	   	// 	alert('請上傳 TXT 格式 !!');
	    // }
?>
	});
});

// 標籤點選
$('.items').click(function(e){
	var ck_i = $(this).index('.items');
	var id = $(".id").eq(ck_i).text();
	var self = document.querySelectorAll(".items")[ck_i];

	html2canvas(self).then(canvas => {
      	saveAs(canvas.toDataURL(), id+'.png');
      	<?
		// img = canvas.toDataURL("image/jpeg");
      	// saveAs(canvas.toDataURL("image/jpeg"), id+'.jpg');
      	// saveAs(canvas.toDataURL("image/bmp"), id+'.bmp');
        ?>
      	var items = $('.items');
      	items.eq(ck_i).remove();
      	// 更新資料筆數
      	$('.info_len').eq(0).text($('.items').length);
	});
});

// 另存圖片
function saveAs(uri, filename) {
    var doc = document;
    var link = doc.createElement('a');
    if (typeof link.download === 'string') {
	    link.href = uri;
	    link.download = filename;

	    doc.body.appendChild(link);
	    link.click();
	    doc.body.removeChild(link);
    }
    else {
        window.open(uri);
    }
}

// 一鍵下載
$('#down').click(function(e){
	var cit = $('.items').length;
	var items = $('.items');
	alert("圖片下載中，請勿關閉網頁視窗或進行其他操作...");
	for (var i = 0; i < cit; i++) {
		var i2 = 0;
		setTimeout(function(){
			var self = document.querySelectorAll(".items")[i2];
			html2canvas(self, i2).then(canvas => {
	 	     	var id = document.querySelectorAll(".id")[i2].innerText;
	 	     	saveAs(canvas.toDataURL(), id+'.png');
	 	     	i2++;
			});
			var cit2 = $('.items').length - 1;
			if(i2 == cit2){
	    		alert('圖片數：'+ (cit2+1) +' 已全部下載完畢');
	  		}
		}, 3000 * (i + 1));
	}
});

</script>