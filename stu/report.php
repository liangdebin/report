﻿<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../static/css/global.css"></link>
<script type="text/javascript" src="../static/jquery/jquery-1.8.3.js"></script>
</head>

<?php
include '../config.php';
$conn=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("连接失败:".mysql_error());
mysql_select_db(DB_NAME,$conn) or die("选择数据库失败".mysql_error());
mysql_query("SET NAMES 'UTF8'");
$today = date("Y-m-d");
//echo $today;
$stu_no = "stu2";

$action = NULL;
if (isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}
switch($action)
{
		//显示某课程的实验内容列表
	case 'report_select_item':
		{
			if (! isset ( $_REQUEST ['cor_no'] )) {
				die ( "请先选择一门课程" );
			}
			$cor_no = $_REQUEST ['cor_no'];
			
			//显示实验内容列表
			$queryStr = sprintf ( "select  *  from item where  cor_no='%s'", $cor_no );
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			
			
			echo '<br /><table>';
			echo '<tr>';
	//		echo '<td />';
			echo '<td>实验编号</td>';
			echo '<td>实验名称</td>';
			echo '<td>成绩百分比</td>';
			echo '<td />';
			echo '</tr>';
				
			if ($rel = mysql_fetch_array ( $result )) {	
				do {
					echo '<tr>';
					echo "<td><a href=./course.php?action=view_course_item&cor_no={$cor_no}&id={$rel['id']}>{$rel['item_no']}</a></td>";
					echo '<td>', $rel ["item_name"], '</td>';
					echo '<td>', $rel ["exam_rate"], '</td>';
					echo "<td><a href=./report.php?action=report_update&cor_no={$cor_no}&item_no={$rel['item_no']}>编辑报告</a></td>";
					echo '</tr>';
				} while ( $rel = mysql_fetch_array ( $result ) );
			} else {
				echo '<tr>';
				echo '<td>';
				echo "暂没有实验内容";
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
			mysql_close ();	
		};break;
		
		
		//编辑实验报告
	case 'report_update':
		{
			if (!isset($_REQUEST['item_no']))
			{
				die('请先选择实验项目');
			}
			if (!isset($_REQUEST['cor_no']))
			{
				die('请先选择实验课程');
			}
			
			$item_no = $_REQUEST['item_no'];
			$cor_no = $_REQUEST['cor_no'];
			
			//若已经填写过报告
			$queryStr = sprintf ( "select  *  from report where stu_no='%s' and cor_no='%s' and item_no='%s' ",$stu_no,$cor_no, $item_no);
			$result = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
			if($rel=mysql_fetch_array($result))
			{
				if($rel['status'] != 0)
				{
					echo '<br />已经提交，无法再修改<br />';
				}
				echo "课程号码:{$cor_no}<br />";
				
				//here
			//	echo "实验:{$rel['item_no']}  {$rel['item_name']}";
				echo "<form method='post' action='./report_deal.php?cor_no={$cor_no}&item_no={$item_no}'>";
				echo "<textarea name='body' style='width:700;height:500;'>{$rel['body']}</textarea>";
				echo '<br /><br />';
				echo "<input name=action id=action value='' type=hidden></input>";
				echo "<input type=button class=btn  value=保存 style='width:65px;background-image:url(../static/image/but_1.png)'></input>";
				echo "<input type=button class=btn  value=提交 style='width:65px;background-image:url(../static/image/but_1.png)'></input>";
				echo '</form>';	
				echo '注意:提交后无法更改';
			}
			
			//若还没有填写过报告
			else
			{
				$queryStr = sprintf("select * from item where cor_no='%s' and item_no='%s'",$cor_no,$item_no);
				$result2 = mysql_query ( $queryStr, $conn ) or die ( "查询失败:" . mysql_error () );
				if($rel2 =mysql_fetch_array($result2))
				{
					echo "课程号码:{$cor_no}<br />";
					echo "实验:{$rel2['item_no']}  {$rel2['item_name']}";
					echo "<form method='post' action='./report_deal.php?cor_no={$cor_no}&item_no={$item_no}'>";
					echo "<textarea name='body' style='width:700;height:500;'>{$rel2['body']}</textarea>";
					echo '<br /><br />';
					echo "<input name=action id=action value='' type=hidden></input>";
					echo "<input type=button class=btn  value=保存 style='width:65px;background-image:url(../static/image/but_1.png)'></input>";
					echo "<input type=button class=btn  value=提交 style='width:65px;background-image:url(../static/image/but_1.png)'></input>";
				    echo '</form>';
				    
				    echo '注意:提交后无法更改';
				}
			}
			mysql_close();	
		};break;
		
		
		//默认显示已选课程
	default:
		{
			echo '<table>';
			echo '<tr>';
			echo '<td>课号</td>';
			echo '<td>课程名称</td>';
			echo '<td>课程批次</td>';
			echo '<td>实验教师</td>';
			echo '</tr>';
				
			$queryStr = sprintf("select * from course,sel_cor,tea where sel_cor.stu_no='%s' and sel_cor.cor_no=course.cor_no and tea.tea_no=course.tea_no and sel_cor.status  in (0,1)",$stu_no);
			$result = mysql_query($queryStr,$conn) or die("查询失败:".mysql_error());
				
			//存在已选课程
			if($rel = mysql_fetch_array($result))
			{
				do
				{
					echo '<tr>';
					echo "<td><a href=./report.php?action=report_select_item&cor_no={$rel['cor_no']}>{$rel ["cor_no"]}</a></td>";
					echo '<td>', $rel ["cor_name"], '</td>';
					echo '<td>', $rel ["groups"], '</td>';
					echo '<td>', $rel ["name"], '</td>';
					echo '</tr>';
				}
				while ($rel = mysql_fetch_array($result));
			}
			else
			{
				echo '<tr><td>暂无已选实验课程</td></tr>';
			}
			echo '</table>';
			echo '<br /><br />';
			echo '请点击课号进行下一步操作';
		};break;
}

?>

<!-- 绑定导航条点击事件 -->
<script type="text/javascript">
 var submit_action = function submit_action(e)
{

    var frm1 = $('form');  
    switch(e.target.value)
    {
        case '保存':
            $('#action').attr('value',"save");break;
        case '提交':
        {
            var tips = window.confirm("提交之后无法更改，你确定要提交?");
            if(tips == false)
            {
                return;
            }              
            $('#action').attr('value',"submit");break;
        }
        default:break;
     }
     frm1.submit();     	
 }
var start = function() { $(".btn").click( submit_action );   }
$(start);
	
</script>
