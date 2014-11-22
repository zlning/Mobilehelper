<?php
function CheckIn_Yidong($keyword,$fromUsername,$datedata)
{
    $url =("https://hl.ac.10086.cn/login");
    //$cookie_file =dirname(__FILE__).'/cookie.txt';
	$data = array(
							'service'=>'ecare',
							'continue'=>'',
							'style'=>'portal',
							'submitMode'=>'sendsms',
							'goto'=>'',
							'fromCode'=>'sso',
							'rememberNum'=>'false',
					  	    'getReadtime'=>'30',
							'username'=>$keyword,
							'passwordType'=>'0',
							'password'=>'',
							'smsRandomCode'=>'',
							'validateCode'=>'',
							'verifyno'=>'',
							'on'=>''
							);

	$url2=("https://hl.ac.10086.cn/SSO/img?rand");
	$ch = curl_init();
    //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
    $result = curl_exec($ch);
                        
    //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($ch, CURLOPT_URL,$url2);
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
    
	$storage = new SaeStorage();
	$domain = 'test';
	$destFileName = $fromUsername.$datedata.'.jpg';
	$content = $result;
	$attr = array('encoding'=>'gzip');
    if($storage->fileExists($domain,$destFileName))
        $storage->delete($domain,$destFileName);
	$result = $storage->write($domain,$destFileName, $content, -1, $attr, true);
    
}
?>