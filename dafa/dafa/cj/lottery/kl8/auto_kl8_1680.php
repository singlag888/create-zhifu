<?php
header('Content-Type:text/html; charset=utf-8');
include_once("./common_new_1680.php");
$type='kl8';
$code=10014;//目标采集网站1680210彩种id
getData($type,$code);
$jstime=substr($time,0,10);
if(strlen($qishu)>0){
	$sql="select id from lottery_result_bjkn where qishu='".$qishu."'";
	$tquery = $mysqli->query($sql);
	$tcou	= $mysqli->affected_rows;
	$hms=explode(',',$hm);
	if($tcou==0){
		$sql = "insert into lottery_result_bjkn(qishu,create_time,datetime,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10,ball_11,ball_12,ball_13,ball_14,ball_15,ball_16,ball_17,ball_18,ball_19,ball_20) values('".$qishu."','".$c_time."','".$time."','".$hms[0]."','".$hms[1]."','".$hms[2]."','".$hms[3]."','".$hms[4]."','".$hms[5]."','".$hms[6]."','".$hms[7]."','".$hms[8]."','".$hms[9]."','".$hms[10]."','".$hms[11]."','".$hms[12]."','".$hms[13]."','".$hms[14]."','".$hms[15]."','".$hms[16]."','".$hms[17]."','".$hms[18]."','".$hms[19]."')";
		$mysqli->query($sql);
	}
}
?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<style type="text/css">
<!--
body,td,th {
    font-size: 12px;
}
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
-->
</style></head>
<body>
<script> 
var limit="23" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+"秒后自动获取!" 
	else 
		curtime=cursec+"秒后自动获取!" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 
</script>
<table width="100%"border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left">
      <input type=button name=button value="刷新" onClick="window.location.reload()">
      北京快乐8(<?=$qishu?>期:<?="$hms[0],$hms[1],$hms[2],$hms[3],$hms[4],$hms[5],$hms[6],$hms[7],$hms[8],$hms[9],$hms[10],$hms[11],$hms[12],$hms[13],$hms[14],$hms[15],$hms[16],$hms[17],$hms[18],$hms[19]"?>):
      <span id="timeinfo"></span>
      </td>
  </tr>
</table>
<iframe src="./js/js_kl8.php?qi=<?=$qishu?>&jsType=0&s_time=<?=$s_time?>" frameborder="0" scrolling="no" height="0" width="0"></iframe>
</body>
</html>