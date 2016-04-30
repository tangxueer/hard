<?php
header("Content-Type:text/html;Charset=utf-8");
include("answer.php");


/*网页授权获取wechat_id*/

$code = $_GET['code'];

if(strpos(addslashes($_SERVER['HTTP_USER_AGENT']), 'MicroMessenger') != FALSE ) 
{
	ch = curl_init();
	$appid = "wxd25012bb1da2b4cf";
	$secret = "d4624c36b6795d1d99dcf0547af5443d";
    curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$json1 = curl_exec($ch);
	curl_close($ch);

	$t = json_decode($json1,true);

	$openid = $t['openid'];

	$token = $t['access_token'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$json2 = curl_exec($ch);
	curl_close($ch);

	$t = json_decode($json2,true);

	$wechat_id = $t['openid'];
}
/**********************/


$game['step'] = "start";	//开始游戏状态码

/*初始化*/

try{
	$db = new PDO("mysql:host=localhost;dbname=game","root","",array(PDO::ATTR_PERSISTENT => true));
	$db -> query("SET NAMES 'UTF8'");
	$db -> query("SET CHARACTER SET UTF8");
	$db -> query("SET CHARACTER_SET_RESULTS=UTF8");
	$db -> setattribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
	$db -> setattribute(PDO::ATTR_EMULATE_PREPARES,false);

	$db -> beginTransaction();
	$insert = $db -> exec("insert into step (id,wechat_id,chance,score,time) values ('','$wechat_id','3','0',NOW())");
	$db -> commit();
}
catch(Exception $e)
{
	$db -> rollBack();
}
/********/

$rs = $db -> prepare("select score from step where wechat_id = ? order by time desc limit 1");
$rs -> setFetchMode(PDO::FETCH_ASSOC);
$db -> setAttribute(PDO::ATTR_CASE,PDO::CASE_NATURAL);
$rs -> bindParam(1,$wechat_id);
$rs -> execute();
$score = $rs -> fetch();

$rs = $db -> prepare("select chance from step where wechat_id = ? order by time desc limit 1");
$rs -> setFetchMode(PDO::FETCH_ASSOC);
$db -> setAttribute(PDO::ATTR_CASE,PDO::CASE_NATURAL);
$rs -> bindParam(1,$wechat_id);
$rs -> execute();
$chance = $rs->fetch();	

while(($score['score'] >= 24) || ($chance['chance'] != 0))	//超过24个答题点或答题机会为0
{		
	$q_num = rand(0,72);	//随机抽题，共73道
	$game['question'] = $q_num;	//题号传给前端

	if(($_POST['overtime'] == 0) || ($_POST['answer'] != $answer['$q_num']))	//超时或答错，前端传来超时信息与用户所选答案
	{		
		$db -> beginTransaction();
		try{
			$exec_update = $db -> exec("update step set chance = chance-1 where wechat_id = '$wechat_id'");				
			$db -> commit();
		}catch(Exception $e)
		{
			$db -> rollBack();
		}
		
		$game['step'] = "stay";	//停留在原答题点再答另一题状态码
					
	}else
	{
		$db -> beginTransaction();
		try{
			$exec_update = $db -> exec("update step set score = score+1 where wechat_id = '$wechat_id'");				
			$db -> commit();
		}catch(Exception $e)
		{
			$db -> rollBack();
		}			
				
		$game['step'] = "move";	//继续前进状态码
	}
}

$game['step'] = "over";	//游戏结束状态码
					
$game['score'] = $score['score'];		//最终成绩传给前端

$json = json_encode($game,JSON_UNESCAPED_UNICODE);
?>
