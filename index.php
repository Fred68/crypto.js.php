<?php

$keystr = "1234567890123456";
$ivstr =  "abcdedfghijklmno";

$txt = "Prova di scrittura 1234567890"	;
$utf8 = mb_convert_encoding($txt, "UTF-8");
$hex = bin2hex ($utf8);
$ba64 = base64_encode($hex);
$ba64s = base64_encode($utf8);
$utf81 = base64_decode($ba64s);

$method = "aes-128-cbc";

$enc = openssl_encrypt ($txt, $method, $keystr, false, $ivstr );
$dec = openssl_decrypt ($enc, $method, $keystr, false, $ivstr);

?>
<html>
<head>
	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/aes-min.js"></script> -->
	<script type="text/javascript" src="components/core-min.js"></script>
	<script type="text/javascript" src="rollups/sha1.js"></script>
	<script type="text/javascript" src="rollups/aes.js"></script>
	<script type="text/javascript" src="components/enc-base64-min.js"></script>
	<script type="text/javascript" src="components/enc-utf16-min.js"></script>
</head>
<body>
	<h1>Keys</h1>
	<table>
		<tr>
			<td>key</td>
			<td id="keystr"><?php echo $keystr; ?></td>
		</tr>
		<tr>
			<td>iv</td>
			<td id="ivstr"><?php echo $ivstr; ?></td>
		</tr>
		<tr>
	</table>
	<h1>PHP</h1>
	<table>
		<tr>
			<td>--------------------------------------</td>
			<td>--------------------------------------</td>
		</tr>
		<tr>
			<td>txt</td>
			<td><?php echo $txt; ?></td>
		</tr>
		<tr>
			<td>txt to utf8</td>
			<td><?php echo $txt; ?></td>
		</tr>

		<tr>
			<td><b>txt enc</b></td>
			<td><?php echo $enc; ?></td>
		</tr>
		<tr>
			<td><b>txt dec</b></td>
			<td><?php echo $dec; ?></td>
		</tr>




		<tr>
			<td>utf8 hex to string</td>
			<td><?php echo $hex; ?></td>
		</tr>
		<tr>
			<td>utf8 string to base64 [not used]</td>
			<td><?php echo $ba64; ?></td>
		</tr>
		<tr>
			<td>string to base64</td>
			<td><?php echo $ba64s; ?></td>
		</tr>
			<td>From base64 (from string)</td>
			<td><?php echo $utf81; ?></td>
		</tr>
		<tr>
			<td>From word array UTF8</td>
			<td>-</td>
		</tr>
	</table>
	<h1>JS</h1>
	<table>
		<tr>
			<td>--------------------------------------</td>
			<td>--------------------------------------</td>
		</tr>
		<tr>
		<td>Testo iniziale </td>
		<td id="ini">-</td>
		</tr>
		<tr>
		<td>To word array UTF8</td>
		<td id="wa1">-</td>
		</tr>
		<tr>
		<td>key</td>
		<td id="key">-</td>
		</tr>
		<tr>
		<td>iv</td>
		<td id="iv">-</td>
		</tr>
		<tr>
		<td><b>txt enc</b></td>
		<td id="enc">-</td>
		</tr>
		<tr>
		<td><b>txt dec</b></td>
		<td id="dec">-</td>
		</tr>
		<tr>
		<td>dec to utf8 str</td>
		<td id="decstr">-</td>
		</tr>		
		
		
		
		<tr>
		<td>To base64</td>
		<td id="ba64">-</td>
		</tr>
		<td>From base64</td>
		<td id="ba65">-</td>
		</tr>
		<tr>
		<td>From word array UTF8</td>
		<td id="x1">-</td>
		</tr>
	</table>
	<button onclick="ftest()">Test</button>
	<script>
	function ftest()
		{
		var x = "<?php echo $txt; ?>";
		var keystr = "<?php echo $keystr; ?>";
		var ivstr = "<?php echo $ivstr; ?>";
		// alert(x);
		var wa1 = CryptoJS.enc.Utf8.parse(x);		// Converte in byte, rappresentazione esadecimale
		
		//var key = CryptoJS.lib.WordArray.random(16);
		//var iv  = CryptoJS.lib.WordArray.random(16);
		
		var key = CryptoJS.enc.Utf8.parse(keystr);
		var iv = CryptoJS.enc.Utf8.parse(ivstr);

		var opt = { iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7 };
		
		//var enc = CryptoJS.AES.encrypt(x, key, { iv: iv });
		//var dec = CryptoJS.AES.decrypt(enc, key, { iv: iv });
		var enc = CryptoJS.AES.encrypt(x, key, opt);
		var dec = CryptoJS.AES.decrypt(enc, key, opt);

		var decstr = dec.toString(CryptoJS.enc.Utf8);
		
		var ba64 = CryptoJS.enc.Base64.stringify(wa1);
		var ba65 = CryptoJS.enc.Base64.parse(ba64);
		var x1 = CryptoJS.enc.Utf8.stringify(ba65);
		document.getElementById("ini").innerHTML = x;
		document.getElementById("wa1").innerHTML = wa1;
		document.getElementById("x1").innerHTML = x1;
		document.getElementById("ba64").innerHTML = ba64;
		document.getElementById("ba65").innerHTML = ba65;

		document.getElementById("key").innerHTML = key;
		document.getElementById("iv").innerHTML = iv;
		document.getElementById("enc").innerHTML = enc;
		document.getElementById("dec").innerHTML = dec;
		document.getElementById("decstr").innerHTML = decstr;
		


		}
	</script>
</body>
</html>