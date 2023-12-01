<?php
error_reporting(0);
define("hijau", "\033[0;32m");
define("reset", "\e[0m");
define("biru", "\033[0;34m");
define("merah", "\033[0;31m");
define("merahbek", "\033[4;31m");
function Curl($url, $header = 0, $post = 0) {
	while(true){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_COOKIE,TRUE);
		curl_setopt($ch, CURLOPT_COOKIEFILE,"cookie.txt");
		curl_setopt($ch, CURLOPT_COOKIEJAR,"cookie.txt");
		if($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_HEADER, true);
		$r = curl_exec($ch);
		$c = curl_getinfo($ch);
		if(!$c) return "Curl Error : ".curl_error($ch); else{
			$hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			curl_close($ch);
			//jika body html tidak ada respon
			if(!$bd){
				print "Check your Connection!";
				sleep(2);
				print "\r                    \r";
				continue;
			}
			return array($hd,$bd);
		}
	}
}
function get($url){
	return curl($url,head())[1];
}
function post($url,$data){
	return curl($url,head(),$data)[1];
}

function head(){
	return [
	"Host: ltcpayu.xyz",
	//"Cookie: popcashpu=1; _gcl_au=1.1.1649459208.1686645657; dom3ic8zudi28v8lr6fgphwffqoz0j6c=0cdf5eeb-2184-49e0-ac72-9b7c1f004fa0%3A2%3A1; ppu_main_a159453ae073cf13ffbdb3e4baebaddf=1; ci_session=hqo9c1v04ob1qts79rken1mjjb86f4r4; csrf_cookie_name=2f839eeb2e9c01b6d8389a29d9949185; ppu_sub_a159453ae073cf13ffbdb3e4baebaddf=2",
	"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36"
	];
}
system("clear");
/*Login */
cek:
$dashboard = "https://ltcpayu.xyz/";
$r = get($dashboard);

$logout = explode("Logout",$r)[1];
if(!$logout){
	$email = readline("Isi email Faucetpay: ");
	$csrf_token_name = explode('"',explode('name="csrf_token_name" id="token" value="',$r)[1])[0];//117dd2ff187d40e0eb58df9eb0b8089e"
	$data = "wallet=".urlencode($email)."&csrf_token_name=$csrf_token_name";
	$login = "https://ltcpayu.xyz/auth/login";
	$r = post($login,$data);
	goto cek;
}
print "Login Sukses";
sleep(3);
print "\r            \r";

system("clear");

echo "\n";
echo "\t" . merah . "================================" . reset . "\n";
echo "\t" . biru . "𝑺𝒄𝒓𝒊𝒑𝒕 𝒊𝒏𝒊 𝒅𝒊 𝒃𝒖𝒂𝒕 𝒐𝒍𝒆𝒉 𝑲𝒂𝒏𝒈 𝑨𝒍𝒊" . reset . "\n";
echo "\t" . merah . "================================" . reset . "\n";
echo "ltcpayu\n";
echo merah . "----------------------" . reset . "\n";
while(true){
	$faucet = "https://ltcpayu.xyz/faucet/currency/ltc";
	$r = get($faucet);

	$timer = explode(',',explode('let timer = ',$r)[1])[0];//100,
	$auto_faucet_token = explode('"',explode('name="auto_faucet_token" value="',$r)[1])[0];//8Jy1DM5XvcUpiqO6YVSZ
	$csrf_token_name = explode('"',explode('name="csrf_token_name" id="token" value="',$r)[1])[0];//1db847d198946928fab50023b1036261
	$token = explode('"',explode('name="token" value="',$r)[1])[0];//BdqUj6I7SLv1FJEyczQ5holsRGKYm4
	$left = explode('</p>',explode('<p class="lh-1 mb-1 font-weight-bold">',$r)[3])[0];
	if(explode("/",$left)[0] <= null)print("Daily claim limit for this coin reached,\nplease comeback again tomorrow\n");
	
	for($i=$timer;$i>0;$i--){
		print biru . "Tunggu $i detik";
		sleep(1);
		print "\r                                    \r";
	}
	
	$data = "auto_faucet_token=$auto_faucet_token&csrf_token_name=$csrf_token_name&token=$token";
	$claim = "https://ltcpayu.xyz/faucet/verify/ltc";
	$r = post($claim,$data);
	$sukses = explode("account!',",explode("html: '0.",$r)[1])[0];//00001660 MATIC has been sent to your FaucetPay account!',
	if($sukses){
		print merahbek . "[".$left."] \n".$sukses. reset . "\n";
	}
}