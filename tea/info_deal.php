﻿<?php
include '../config.php';
$conn=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("连接失败:".mysql_error());
mysql_select_db(DB_NAME,$conn) or die("选择数据库失败".mysql_error());
mysql_query("SET NAMES 'UTF8'");


$action = $_REQUEST['action'];
//echo $action;


/**  根据参数 action 的值进行不同的后台处理：update_info 更新个人信息，
 * update_psw 更新密码
 * 
 */
switch($action)
{
	case 'update_info':
		{
			$tea_no = trim($_REQUEST['tea_no']);
			$name = trim($_REQUEST['name']);
			$mail = trim($_REQUEST['mail']);
			$mobile = trim($_REQUEST['mobile']);
			
			$queryStr = sprintf("update  tea set name='%s',mail='%s',mobile='%s' where tea_no='%s'",$name,$mail,$mobile,$tea_no);
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
			if($result == TRUE && 1==mysql_affected_rows())
			{
				echo "修改成功";
			}
			else
			{
				echo '修改失败';
			}
			mysql_close();
			
		};break;
	case 'update_psw':
		{
			$tea_no = trim($_REQUEST['tea_no']);
			$old_psw = strtolower(trim($_REQUEST['old_psw']));
			$new_psw1 = strtolower(trim($_REQUEST['new_psw1']));
			$new_psw2= strtolower(trim($_REQUEST['new_psw2']));		
			if($new_psw1 != $new_psw2)
			{
				die("修改失败：两次输入密码不一致");
			}
			if($old_psw === $new_psw1)
			{
				die("修改失败：原密码与新密码一样");
			}
			$old_psw = md5(base64_encode($old_psw));
			$new_psw1 = md5(base64_encode($new_psw1));
			
			$queryStr = sprintf("update  tea set psw='%s' where tea_no='%s' and psw='%s'",$new_psw1,$tea_no,$old_psw);
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
			
			if($result!=NULL && 1==mysql_affected_rows())
			{
				echo "修改成功";
			}
			else
			{
				echo "修改失败";
			}
	
			mysql_close();
			
			
		}break;
	default:
		echo "未知传值";
		break;
}



?>