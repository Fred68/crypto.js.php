<?php

//$keystr = "1234567890123456";
//$ivstr =  "abcdedfghijklmno";

$keystr = "12345678901234567890123456789012";
//$ivstr =  "abcdedfghijklmno";
$ivstr =  "0123456789:;<=>?";

$txt = "Prova di scrittura 1234567890"	;
$utf8 = mb_convert_encoding($txt, "UTF-8");
$hex = bin2hex ($utf8);
$ba64 = base64_encode($hex);
$ba64s = base64_encode($utf8);
$utf81 = base64_decode($ba64s);
$sha1ks = sha1($keystr);

//	$method = "aes-128-cbc";
$method = "aes-256-cbc";

$enc = openssl_encrypt ($txt, $method, $keystr, false, $ivstr );
$dec = openssl_decrypt ($enc, $method, $keystr, false, $ivstr);


// RSA
$puk = "---";
$prk = "---";

$rsakeysize = 512;
$config = array('private_key_bits' => $rsakeysize,'private_key_type' => OPENSSL_KEYTYPE_RSA);
$res=openssl_pkey_new($config);
openssl_pkey_export($res, $prk);
$pubkey=openssl_pkey_get_details($res);
$puk=$pubkey["key"];


if(isset($_POST['key']))
 	{
 	$k = $_POST['key'];
 	$i = $_POST['iv'];
 	$e = $_POST['enc'];
 	
 	$i6 = base64_decode($i);
 	$dec = "nullo";
 	$dec = openssl_decrypt ($e, $method, $k, false, $i6);
 	echo "k=".$k."\niv64=".$i."\niv=".$i6."\nenc=".$e."\ndec=".$dec;
 	exit();
 	}
else if(isset($_POST['rsa']))
	{
	$r = $_POST['rsa'];		// Chiave pubblica in chiaro
	
	openssl_public_encrypt($txt." con aggiunta", $encrsa, $r);
	$encrsa64 = base64_encode($encrsa);
	//echo "rsa=\n".$r."\nrsaenc=\n".$encrsa64;
	echo $encrsa64;
	/*
	openssl_public_encrypt($sensitiveData, $encryptedData, $pubKey);
	openssl_private_decrypt($encryptedData, $sensitiveData, $privateKey);				// load the private key and decrypt the encrypted data
	*/
	
	exit();
	}
	

?>
<html>
<head>
	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/aes-min.js"></script> -->
	<!-- <script type="text/javascript" src="components/sha1-min.js"></script> -->
	<!-- <script type="text/javascript" src="components/aes-min.js"></script> -->
	
	
<!--
	LOCALE (USARE IN CASO DI NECESSITA`)
 	<script type="text/javascript" src="components/core-min.js"></script>
	<script type="text/javascript" src="rollups/sha1.js"></script>
	<script type="text/javascript" src="rollups/aes.js"></script>
	<script type="text/javascript" src="components/enc-base64-min.js"></script>
	<script type="text/javascript" src="components/enc-utf16-min.js"></script>
-->
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/sha1.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/enc-base64-min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/enc-utf16-min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/2.3.1/jsencrypt.min.js"></script>
	
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
		var shaks = CryptoJS.SHA1(keystr);
		var opt = { iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7 };
		
		//var enc = CryptoJS.AES.encrypt(x, key, { iv: iv });
		//var dec = CryptoJS.AES.decrypt(enc, key, { iv: iv });
		var enc = CryptoJS.AES.encrypt(x, key, opt);
		var dec = CryptoJS.AES.decrypt(enc, key, opt);

		var decstr = dec.toString(CryptoJS.enc.Utf8);
		
		var ba64 = CryptoJS.enc.Base64.stringify(wa1);			// in base64
		var ba65 = CryptoJS.enc.Base64.parse(ba64);
		var x1 = CryptoJS.enc.Utf8.stringify(ba65);
		
		document.getElementById("ini").innerHTML = x;
		document.getElementById("wa1").innerHTML = wa1;
		document.getElementById("x1").innerHTML = x1;
		document.getElementById("ba64").innerHTML = ba64;
		document.getElementById("ba65").innerHTML = ba65;

		document.getElementById("key").innerHTML = key;
		document.getElementById("keyhash").innerHTML = shaks;
		document.getElementById("iv").innerHTML = iv;
		document.getElementById("enc").innerHTML = enc;
		document.getElementById("dec").innerHTML = dec;
		document.getElementById("decstr").innerHTML = decstr;

		
		
		}
	function fpost(keystr,ivstr,txt)
		{
		var dati = new FormData();
		dati.append('key',keystr);		// Invio in chiaro, per test

		var iv = CryptoJS.enc.Utf8.parse(ivstr);			// ivstr in utf8, da usarsi per encrypt
		var iv64 = 	CryptoJS.enc.Base64.stringify(iv);		// iv in base64
		dati.append('iv',iv64);

		var key = CryptoJS.enc.Utf8.parse(keystr);
		var opt = { iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7 };
		var enc = CryptoJS.AES.encrypt(txt, key, opt);
		dati.append('enc',enc);
		
		var u = "<?php echo $_SERVER['REQUEST_URI']; ?>";
		$.ajax	({
		    		url : u,
		    		type: "POST",
		    		data: dati,
		    		contentType: false,
		    		processData: false
				})
				.done( function(data)
							{
							alert(data);
							}
				)
				.fail( function()
						{
						alert("ajax fail")
						}
				)
		}
	function rsapost()
		{
		var rsakeysize = <?php echo $rsakeysize; ?>;
		document.getElementById("rsakeysize").innerHTML = rsakeysize;

		var crypt = new JSEncrypt({ default_key_size: rsakeysize });
		crypt.getKey();
		var prk = crypt.getPrivateKey();
		var puk = crypt.getPublicKey();
		document.getElementById("puk").innerHTML = puk;
		document.getElementById("prk").innerHTML = prk;
		
		alert("puk=\n"+puk);

		var dati = new FormData();
		dati.append('rsa',puk);		// Invio chiave pubblica in chiaro

		var u = "<?php echo $_SERVER['REQUEST_URI']; ?>";
		$.ajax	({
		    		url : u,
		    		type: "POST",
		    		data: dati,
		    		contentType: false,
		    		processData: false
				})
				.done( function(data)
							{
							alert(data);
							alert(puk);
							var decrypted = crypt.decrypt(data);
							alert(decrypted);
							}
				)
				.fail( function()
						{
						alert("ajax fail")
						}
				)
		
		
		}
		
	</script>
	
	<style>
	h1 {color:blue;font-size:130%}
	td {color:black;font-size:80%}
	</style>
	
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
			<td>sha1 keystr</td>
			<td><?php echo $sha1ks; ?></td>
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
		<tr>
			<td>From base64 (from string)</td>
			<td><?php echo $utf81; ?></td>
		</tr>
		<tr>
			<td>From word array UTF8</td>
			<td>-</td>
		</tr>
		<tr>
		<td>RSA keysize</td>
		<td><?php echo $rsakeysize; ?></td>
		</tr>
		<tr>
		<td>Public key</td>
		<td><?php echo $puk; ?></td>
		</tr>
		<tr>
		<td>Private key</td>
		<td><?php echo $prk; ?></td>
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
		<td>key (utf8)</td>
		<td id="key">-</td>
		</tr>
		<tr>
		<td>keystr sha1</td>
		<td id="keyhash">-</td>
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
		<tr>
		<td>From base64</td>
		<td id="ba65">-</td>
		</tr>
		<tr>
		<td>From word array UTF8</td>
		<td id="x1">-</td>
		</tr>
		<tr>
		<td>Rsa keysize</td>
		<td id="rsakeysize">-</td>
		</tr>
		<tr>
		<td>Public key js</td>
		<td id="puk">-</td>
		</tr>
		<tr>
		<td>Private key js</td>
		<td id="prk">-</td>
		</tr>
		
		
	</table>
	<button onclick="ftest()">Test AES</button>
	<button onclick="fpost('<?php echo $keystr; ?>','<?php echo $ivstr; ?>','<?php echo $txt; ?>')">Post AES</button>
	<button onclick="rsapost()">Post RSA</button>
	<script>

	</script>
</body>
</html>