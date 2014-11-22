<?php
function FindUser($fromUsername)
{
    $con=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
	mysql_select_db(SAE_MYSQL_DB,$con);
	if($con){
		$flag = 0;
 		if($result = mysql_query("SELECT * FROM mobilehelper_user  WHERE ID='$fromUsername'")){
		while($row = mysql_fetch_array($result)){
			if($row['ID']==$fromUsername){
				$flag = 1;
				$retstr="Find succeed.\n";
 				$state = $row['state'];
			}
		}
		if($flag==0){
			mysql_select_db(SAE_MYSQL_DB,$con);
			mysql_query("INSERT INTO mobilehelper_user (ID , state)  VALUES ( '$fromUsername', '0')");
			$retstr="New succeed.\n";
		}
        }
    }
    mysql_close($con);
    return $state;
}

function ChangeKey($fromUsername,$var,$keyword)
{
    $con=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    mysql_select_db(SAE_MYSQL_DB,$con);
    mysql_query("UPDATE mobilehelper_user SET $var = '$keyword' WHERE ID = '$fromUsername'");
    mysql_close($con);
}

function ChangeState($fromUsername,$newstate)
{
    $con=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    mysql_select_db(SAE_MYSQL_DB,$con);
    mysql_query("UPDATE mobilehelper_user SET state = '$newstate' WHERE ID = '$fromUsername'");
    mysql_close($con);
}


?>