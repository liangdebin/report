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
	case 'update_cor':
		{
			$id = trim($_REQUEST['id']);
			$cor_no = trim($_REQUEST['cor_no']);
			$cor_name = trim($_REQUEST['cor_name']);
			$term = trim($_REQUEST['term']);
			$tea_no = trim($_REQUEST['tea_no']);
			$select_time = trim($_REQUEST['select_time']);
			$close_time = trim($_REQUEST['close_time']);
			$usual_rate = trim($_REQUEST['usual_rate']);
			$report_rate = trim($_REQUEST['report_rate']);
			$exam_rate = trim($_REQUEST['exam_rate']);
			
/**
			echo $id;
			echo '<br />';	
			echo $cor_no;
			echo '<br />';
			echo $cor_name;
			echo '<br />';			
			echo $term;
			echo '<br />';		
			echo $tea_no;
			echo '<br />';			
			echo $select_time;
			echo '<br />';			
			echo $close_time;
			echo '<br />';
			echo $usual_rate;
			echo '<br />';
			echo $report_rate;
			echo '<br />';
			echo $exam_rate;
			echo '<br />';
	*/		
			$queryStr = sprintf("update  course set cor_no='%s',term='%s',tea_no='%s',cor_name='%s',usual_rate='%s',report_rate='%s',
exam_rate='%s',select_time='%s',report_time='%s',close_time='%s' 
where id='%s'",$cor_no,$term,$tea_no,$cor_name,$usual_rate,$report_rate,$exam_rate,$select_time,$report_rate,$close_time,$id);
			
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
			
			//更新课程成功
			if($result != NULL && 1==mysql_affected_rows())
			{
				echo "修改课程成功";
			}
			else
			{
				echo '修改课程失败';
			}
			mysql_close();
		};break;
	
	default:
		echo "未知传值";
		break;
}



?>