<?php
class bet_ds{
  	static function dx_add($userid,$ball_sort,$point_column,$match_name,$master_guest,$match_id,$bet_info,$bet_money,$bet_point,$ben_add,$bet_win,$match_time,$match_endtime,$lose_ok,$match_showtype,$match_rgg,$match_dxgg,$match_nowscore,$match_type,$Match_HRedCard,$Match_GRedCard,$ksTime,$ip_addr,$www,$bet_reb,$et_time_now,$game_type,$bet_yx){
	
		global $mysqli;

		$sql		= 	"select money from user_list where user_id='$userid' limit 1";
		$query 		=	$mysqli->query($sql);
		$rs			=	$query->fetch_array();
		if($rs['money']){
			$assets	=	$rs['money'];
			$balance=	$assets-$bet_money;
		}else{
			return false;
		}
		if($assets <$bet_money)
		{
			return false;
		}
		$sql	=	"insert into k_bet(user_id,ball_sort,point_column,match_name,master_guest,match_id,bet_info,bet_money,bet_point,ben_add,bet_win,match_time,bet_time,bet_time_et,match_endtime,lose_ok,match_showtype,match_rgg,match_dxgg,match_nowscore,match_type,balance,assets,Match_HRedCard,Match_GRedCard,www,match_coverdate,ip,bet_reb,game_type,bet_yx) values ('$userid','$ball_sort','$point_column','$match_name','$master_guest','$match_id','$bet_info','$bet_money','$bet_point','$ben_add','$bet_win','$match_time',now(),'$et_time_now','$match_endtime','$lose_ok','$match_showtype','$match_rgg','$match_dxgg','$match_nowscore','$match_type','$balance','$assets','$Match_HRedCard','$Match_GRedCard','$www','$ksTime','$ip_addr','$bet_reb','$game_type','$bet_yx')"; //新增一个投注项
		$mysqli->query($sql);
		$q1		=	$mysqli->affected_rows;
		if($q1!=1){
            $mysqli->query("delete from k_bet where order_num is null");
			return false;
		}
		$id 	=	$mysqli->insert_id;
		$datereg=	date("YmdHis").$id;

		

		$sql	=	"update user_list set money=$balance where money>=$bet_money and $balance>=0 and user_id='$userid'";//扣钱
		$mysqli->query($sql);
		$q3		=	$mysqli->affected_rows;
		if($q3!=1){
			$sql	=	"delete from k_bet where id=$id";//操作失败，删除订单
			$mysqli->query($sql);
			return false;
		}

		$sql	=	"update `k_bet` set `order_num`='$datereg' where id='$id'"; //更新订单号
		$mysqli->query($sql);
		$q2		=	$mysqli->affected_rows;
		if($q2!=1){
			$sql	=	"delete from k_bet where id=$id";//操作失败，删除订单
			$mysqli->query($sql);
			$sql	=	"update user_list set money=money+$bet_money where user_id='$userid'";//操作失败，还原客户资金
			$mysqli->query($sql);
			return false;
		}
		
		$sql = "INSERT INTO `money_log` (`user_id`,`order_num`,`about`,`update_time`,`type`,`order_value`,`assets`,`balance`) VALUES ('$userid','$datereg','体育单式',now(),'体育下注','$bet_money','$assets','$balance');";
		$mysqli->query($sql);
		$q8		=	$mysqli->affected_rows;
		if($q8!=1){
			$sql	=	"delete from k_bet where id=$id";//操作失败，删除订单
			$mysqli->query($sql);
			$sql	=	"update user_list set money=money+$bet_money where user_id='$userid'";//操作失败，还原客户资金
			$mysqli->query($sql);
			return false;
		}
		$log_id 	=	$mysqli->insert_id;

		$tm=date("Y-m-d H:i:s",time());
		$width	=	str_leng($ball_sort.'='.$match_name.'='.$master_guest.'='.$bet_info.$match_showtype.$bet_money.'='.$tm); //宽
		$height	=	26; //高
		$im		=	imagecreate($width,$height);
		$bkg	=	imagecolorallocate($im,255,255,255); //背景色
		$font	=	imagecolorallocate($im,150,182,151); //边框色
		$sort_c	=	imagecolorallocate($im,0,0,0); //字体色 
		$name_c	=	imagecolorallocate($im,243,118,5); //字体色 
		$guest_c=	imagecolorallocate($im,34,93,156); //字体色 
		$info_c	=	imagecolorallocate($im,51,102,0); //字体色 
		$money_c=	imagecolorallocate($im,255,0,0); //字体色 
		$tm_c=	imagecolorallocate($im,0,0,0); //字体色 
		$fnt	=	"ttf/simhei.ttf";
		
		imagettftext($im,10,0,7,18,$sort_c,$fnt,$ball_sort); //赛事类型
		imagettftext($im,10,0,str_leng($ball_sort.'=='),18,$name_c,$fnt,$match_name); //联赛名称
		imagettftext($im,10,0,str_leng($ball_sort.$match_name.'==='),18,$guest_c,$fnt,$master_guest); //队伍名称
		imagettftext($im,10,0,str_leng($ball_sort.$match_name.$master_guest.'===='),18,$info_c,$fnt,$bet_info); //交易明细
		imagettftext($im,10,0,str_leng($ball_sort.$match_name.$master_guest.$bet_info.'====='),18,$info_c,$fnt,$match_showtype); //主、客让
		imagettftext($im,10,0,str_leng($ball_sort.$match_name.$master_guest.$bet_info.$match_showtype.'======'),18,$money_c,$fnt,$bet_money); //交易金额
		imagettftext($im,10,0,str_leng($ball_sort.$match_name.$master_guest.$bet_info.$match_showtype.$bet_money.'======='),18,$tm_c,$fnt,$tm); //交易时间
		imagerectangle($im,0,0,$width-1,$height-1,$font); //画边框
		$C_Patch=$_SERVER['DOCUMENT_ROOT'];
		if(!is_dir($C_Patch."/order/".substr($datereg,0,8))) mkdir($C_Patch."/order/".substr($datereg,0,8));
        imagejpeg($im,$C_Patch."/order/".substr($datereg,0,8)."/".$datereg.".jpg"); //生成图片
		imagedestroy($im);

		if(!$q4){
			$sql	=	"delete from k_bet where id=$id";//操作失败，删除订单
			$mysqli->query($sql);
			$sql	=	"update user_list set money=money+$bet_money where user_id='$userid'";//操作失败，还原客户资金
			$mysqli->query($sql);
			$sql	=	"delete from money_log where id=$log_id";//操作失败，删除订单
			$mysqli->query($sql);
			return false;
		}

		//验证一下账户金额
		$usermoney=0;
		$sql		= 	"select money from user_list where user_id='$userid' limit 1";
		$query 		=	$mysqli->query($sql);
		$rs			=	$query->fetch_array();

		$usermoney=$rs['money'];


		$sql		= 	"select balance from money_log where user_id='$userid' order by id desc limit 0,1";
		$query 		=	$mysqli->query($sql);
		$rs_l			=	$query->fetch_array();
		if(floatval($rs_l['balance'])!=floatval($usermoney)){
			$sql = "update user_list set online=0,Oid='',status='异常',remark='体育($datereg)下注后资金异常$bj_time_now' where user_id='$userid'";
			$mysqli->query($sql);
			return false;
		}

		return true;
  	}
}
?>