﻿<?php
$var=$_POST['value'];
$se=$_POST['see'];
$url =("https://hl.ac.10086.cn/login");
//$url =("test.php");
$url2=("https://hl.ac.10086.cn/SSO/img?rand");
$url3=("http://www.hl.10086.cn/my/index.html");
$url4=("http://www.hl.10086.cn/service/fee/f_phone/querymonth/queryBillList.do");
$url5 = ("http://www.hl.10086.cn/sso/ssoresponse.jsp");
$url7 = ("http://www.hl.10086.cn/service/fee/f_phone/querymonth/queryBillListBeFor.do?busiType=201410&integ_=55");
//$url7= ("http://www.hl.10086.cn/my/index.html");
//$url5=$url5+"?timeStamp=" + new Date().getTime();
$cookie_file =dirname(__FILE__).'/cookie.txt';
$data = array(
							'service'=>'ecare',
							'continue'=>'',
							'style'=>'portal',
							'submitMode'=>'login',
							'goto'=>'',
							'fromCode'=>'sso',
							'rememberNum'=>'false',
					  	'getReadtime'=>'30',
							'username'=>'',
							'passwordType'=>'2',
							'password'=>'',
							'smsRandomCode'=>'',
							'validateCode'=>$var,
							'verifyno'=>'',
							'on'=>'',
							'loginbutton' =>'鐧� 褰�'
							);
$data1 = array(
								'RelayState'=>'',
								'SAMLart'=>'',
								'PasswordType'=>'0'
							);
$data2 = array(
								'artifact'=>'',
								'RelayState'=>''
							);
$data3 = array(
								'busiType'=>'201407',
								'flag'=>''
							);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_HEADER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
$result = curl_exec($ch);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
$tp = @fopen('aiaia0.txt','w');
fwrite($tp,$result);
fclose($tp);

preg_match('/<input type="hidden" name="SAMLart"
				value=(.*)\/>/',$result, $arr);
echo $arr[1];

$data1['SAMLart']=trim($arr[1],'" "');
$url5=$url5.""."?timeStamp="."".time();


curl_setopt($ch, CURLOPT_URL,$url5);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_HEADER,1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data1));
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
$result = curl_exec($ch);
/*$result = preg_replace('/"\/sso\/ssouserinfo.do"/', "http://www.hl.10086.cn/sso/ssouserinfo.do", $result);
echo $result;*/
$tp = @fopen('aiaia1.txt','w');
fwrite($tp,$result);
fclose($tp);


preg_match('/<input type="hidden" name="artifact" value=(.*) \/>/',$result, $arr);
echo $arr[1];
$data2['artifact']=trim($arr[1],'" "');
$url6='http://www.hl.10086.cn/sso/ssouserinfo.do';
curl_setopt($ch, CURLOPT_URL,$url6);
curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie_file);
curl_setopt($ch, CURLOPT_HEADER,1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data2));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);

$result = curl_exec($ch);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
$tp = @fopen('aiaia2.txt','w');
fwrite($tp,$result);
fclose($tp);


//echo $result;
curl_setopt($ch, CURLOPT_URL,$url7);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
$result = curl_exec($ch);
$tp = @fopen('aiaia3.txt','w');
fwrite($tp,$result);
fclose($tp);
preg_match('/<td height="30">(.*)<\/td>/',$result, $arr);
//preg_match('/<td height="30">(.*)元<\/td>/',$result, $arr);
//print_r($arr);
echo $arr[1];
?>