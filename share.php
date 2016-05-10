<?php
include("game.php");//获取score

require "jssdk.php";
$jssdk = new JSSDK("wxd25012bb1da2b4cf", "d4624c36b6795d1d99dcf0547af5443d");//appid 与 appesecret
$signPackage = $jssdk->GetSignPackage();

if($score['score'] >=0 && $score['score'] <= 4)
{
	$news = array("Title" =>"毕业之旅", 
	"Description"=>"本宝宝不服，竟然才华工幼儿园毕业？戳链接来毕业",
	"PicUrl" =>'', //图片?
	"Url" =>'');  //游戏入口地址?	
}
if($score['score'] >= 5 && $score['score'] <= 8)
{
	$news = array("Title" =>"", 
	"Description"=>"聪明才智的我才是个华工附小生？戳链接来毕业",
	"PicUrl" =>'', //图片?
	"Url" =>'');  //游戏入口地址?	
}
if($score['score'] >= 9 && $score['score'] <= 10)
{
	$news = array("Title" =>"", 
	"Description"=>"学富五车的我还是嫩嫩的华工高中生！戳链接来毕业",
	"PicUrl" =>'', //图片?
	"Url" =>'');  //游戏入口地址?	
}
if($score['score'] >= 11 && $score['score'] <= 12)
{
	$news = array("Title" =>"", 
	"Description"=>"本宝宝可是名正言顺从华工毕业！戳链接来毕业",
	"PicUrl" =>'', //图片?
	"Url" =>'');  //游戏入口地址?	
} 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>毕业之旅分享页</title>
</head>
<body>
	<!--前端进行页面美化-->
    <button class="btn btn_primary" id="onMenuShare">分享</button>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"> </script>
<script>
	wx.config
	({
		debug: false,//调试选true
		appId: '<?php echo $signPackage["appId"];?>',
		timestamp: <?php echo $signPackage["timestamp"];?>,
		nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		signature: '<?php echo $signPackage["signature"];?>',
		jsApiList:[
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ',
			'onMenuShareWeibo',
		]
    });
  
	wx.ready(function () {
	document.querySelector('#onMenuShare').onclick = function () {
		alert("赶快点击右上角...按钮来分享吧！");
	};		
	//分享给朋友
    wx.onMenuShareAppMessage({
        title: '<?php echo $news['Title'];?>',
        desc: '<?php echo $news['Description'];?>',
        link: '<?php echo $news['Url'];?>',
        imgUrl: '<?php echo $news['PicUrl'];?>',
      trigger: function (res) {
        //alert('用户点击发送给朋友');
      },
      success: function (res) {
        //alert('已分享');
      },
      cancel: function (res) {
        //alert('已取消');
      },
      fail: function (res) {
        //alert(JSON.stringify(res));
		alert('分享失败。。。');
      }
    });
 
	//分享到朋友圈
    wx.onMenuShareTimeline({
      title: '<?php echo $news['Title'];?>',
      link: '<?php echo $news['Url'];?>',
      imgUrl: '<?php echo $news['PicUrl'];?>',
      trigger: function (res) {
        //alert('用户点击分享到朋友圈');
      },
      success: function (res) {
        //alert('已分享');
      },
      cancel: function (res) {
        //alert('已取消');
      },
      fail: function (res) {
        //alert(JSON.stringify(res));
		alert('分享失败。。。');
      }
    });

	//分享到QQ
    wx.onMenuShareQQ({
      title: '<?php echo $news['Title'];?>',
      desc: '<?php echo $news['Description'];?>',
      link: '<?php echo $news['Url'];?>',
      imgUrl: '<?php echo $news['PicUrl'];?>',
      trigger: function (res) {
        //alert('用户点击分享到QQ');
      },
      complete: function (res) {
        //alert(JSON.stringify(res));
      },
      success: function (res) {
        //alert('已分享');
      },
      cancel: function (res) {
        //alert('已取消');
      },
      fail: function (res) {
        //alert(JSON.stringify(res));
		alert('分享失败。。。');
      }
    });
  
  // “分享到微博”
    wx.onMenuShareWeibo({
      title: '<?php echo $news['Title'];?>',
      desc: '<?php echo $news['Description'];?>',
      link: '<?php echo $news['Url'];?>',
      imgUrl: '<?php echo $news['PicUrl'];?>',
      trigger: function (res) {
        //alert('用户点击分享到微博');
      },
      complete: function (res) {
        //alert(JSON.stringify(res));
      },
      success: function (res) {
        //alert('已分享');
      },
      cancel: function (res) {
        //alert('已取消');
      },
      fail: function (res) {
        //alert(JSON.stringify(res));
		alert('分享失败。。。');
      }
    }); 

});

//wx.error(function (res) {
//  alert(res.errMsg);
//});
</script>
<script src="http://demo.open.weixin.qq.com/jssdk/js/api-6.1.js?ts=1420774989"> </script>
</html>
