

<?php
$url =("https://hl.ac.10086.cn/login");
$url2=("https://hl.ac.10086.cn/SSO/img?rand");
$cookie_file =dirname(__FILE__).'/cookie.txt';
$data = array(
							'service'=>'ecare',
							'continue'=>'',
							'style'=>'portal',
							'submitMode'=>'sendsms',
							'goto'=>'',
							'fromCode'=>'sso',
							'rememberNum'=>'false',
					  	'getReadtime'=>'30',
							'username'=>'',
							'passwordType'=>'0',
							'password'=>'',
							'smsRandomCode'=>'',
							'validateCode'=>'',
							'verifyno'=>'',
							'on'=>''
							);
				

$ch = curl_init();
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
//curl_setopt($ch, CURLOPT_URL,$url);
/*curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
$result = curl_exec($ch);*/
//echo $result;
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
//$cookie;
//curl_setopt ($ch, CURLOPT_COOKIE , $cookie );
curl_setopt($ch, CURLOPT_URL,$url2);
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
$result = curl_exec($ch);
/*echo $result;
$tp=fopen($cookie_file,'r');
$ss=fread($tp,400);
echo $ss;*/


$tp = @fopen("qqqq.jpg",'w');
fwrite($tp,$result);
fclose($tp);
curl_close($ch);




/*curl_setopt($ch, CURLOPT_URL,$url2);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);*/
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // ʹԃؔ¶¯͸ת
//curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:8.8.8.8', 'CLIENT-IP:8.8.8.8'));  //¹¹լIP  
//curl_setopt($ch, CURLOPT_REFERER, "https://cas.hit.edu.cn/login?service=https%3A%2F%2Fcms.hit.edu.cn%2Flogin%2Findex.php%3FauthCAS%3DCAS");
//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.117 Safari/537.36');
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
//curl_setopt($ch, CURLOPT_POST, 1); 
//curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);

//echo $ch;
//echo $cookie_file;
//curl_close($ch); 
//print_r($result);

//</script>
/*function zc(){
	echo "sddd";

}*/
//********************************************************

?>

<form name="xinxin" id="xinxin" action="second.php" method="post">
		<input type="text" id="see" name="see" value="" />
    <input type="text" id="value" name="value" value=""/>
    <input type="submit" value="send" id="button" name="button">
</form>					
</body>
</html>