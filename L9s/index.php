<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx64e8a29cc45978ff", "12a4f78d47b3068f755465117d3e5ebf");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
 		<link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
  <title></title>
   <style type="text/css">
  	
  	body{
  		padding: 20px;
  	}
  	
  </style>
</head>
<body>
  <img src="img/fun.jpg" id="image" width="100%"/>
  <a href="javascript:;"  id="chooseImage" class="weui-btn weui-btn_primary">拍照</a>
	<a href="javascript:;"  id="previewImage" class="weui-btn weui-btn_primary">图片预览</a>
	<a href="javascript:;"  id="startRecord" class="weui-btn weui-btn_primary">开始录音</a>
	<a href="javascript:;"  id="stopRecord" class="weui-btn weui-btn_primary">停止录音</a>
	<a href="javascript:;"  id="playVoice" class="weui-btn weui-btn_primary">播放录音</a>
	<a href="javascript:;"  id="pauseVoice" class="weui-btn weui-btn_primary">暂停播放</a>
	<a href="javascript:;"  id="stopVoice" class="weui-btn weui-btn_primary">停止播放</a>
	<a href="javascript:;"  id="translateVoice" class="weui-btn weui-btn_primary">转换成文字</a>
	<a href="javascript:;"  id="getLocation" class="weui-btn weui-btn_primary">定位</a>
	<a href="javascript:;"  id="openLocation" class="weui-btn weui-btn_primary">打开地图</a>

  
  <script src="js/weui.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/zepto_1.1.3.js" type="text/javascript" charset="utf-8"></script>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  
  var localId = '';
  var latitude= 0;
  var longitude= 0;
  wx.config({
    debug: true,//若为false则退出调试模式，不会弹出提示对话框
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: ["openLocation","getLocation","startRecord","stopRecord","playVoice","pauseVoice","stopVoice","translateVoice","updateAppMessageShareData","updateTimelineShareData","chooseImage","previewImage"]
  });
  wx.ready(function () {
    // 在这里调用 API
    //分享
     wx.updateAppMessageShareData({ 
        title: '重磅消息，从今年开始春晚取消了！', // 分享标题
		        desc: '开播xx年的春晚从今年开始就取消不再开播了.....', // 分享描述
		        link: 'http://demo.niyinlong.com/demo4/test.html', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
		        imgUrl: 'http://demo.niyinlong.com/demo4/img/chunyun.jpg', // 分享图标
		        success: function (res) {
		          	// 设置成功
		          	console.log("分享成功");
		          	console.log(res);
		        }
   });
    
    //分项朋友圈
      wx.updateTimelineShareData({ 
        title: '劲爆消息！过年回家，车票全免！！', // 分享标题
        link: 'http://demo.niyinlong.com/demo4/test.html', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'http://demo.niyinlong.com/demo4/img/chunyun.jpg', // 分享图标
        success: function (res) {
          console.log("分享成功");// 设置成功
          console.log(res);
        }
});
   //拍照
    document.querySelector("#chooseImage").onclick = function(e){
    e.preventDefault();
    wx.chooseImage({
					count: 1, // 默认9
					sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
					success: function (res) {
					var localId = res.localIds[0]; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
					document.querySelector("#image").src = localId;

					}
			});
    }
    
    //图片预览
    document.querySelector("#previewImage").onclick = function(e){
			e.preventDefault();
	    wx.previewImage({
					current: 'http://demo.niyinlong.com/demo4/img/chunyun.jpg', // 当前显示图片的http链接
					urls: ["http://demo.niyinlong.com/demo4/img/chunyun.jpg","http://demo.niyinlong.com/demo4/img/fun.jpg"] // 需要预览的图片http链接列表
				});
    
    }
    
    document.querySelector("#startRecord").onclick = function(e){
    	e.preventDefault();
    	wx.startRecord();
    };
    document.querySelector("#stopRecord").onclick = function(e){
    	e.preventDefault();
    		wx.stopRecord({
					success: function (res) {
					localId = res.localId;
					}
					});
    };
    	wx.onVoiceRecordEnd({
			// 录音时间超过一分钟没有停止的时候会执行 complete 回调
			complete: function (res) {
			localId = res.localId;
			}
			});
    
    document.querySelector("#playVoice").onclick = function(e){
    	e.preventDefault();
    			wx.playVoice({
					localId: localId // 需要播放的音频的本地ID，由stopRecord接口获得
				});
    	
    };
    document.querySelector("#pauseVoice").onclick = function(e){
    		e.preventDefault();
    		wx.pauseVoice({
					localId: localId // 需要暂停的音频的本地ID，由stopRecord接口获得
					});
    		
    		
    };
    	wx.onVoicePlayEnd({
			success: function (res) {
			localId = res.localId; // 返回音频的本地ID
			wx.stopVoice({
			localId: localId // 需要停止的音频的本地ID，由stopRecord接口获得
			});
			}
			});
    	
    
    document.querySelector("#stopVoice").onclick = function(e){
    	e.preventDefault();
    	wx.stopVoice({
			localId: localId // 需要停止的音频的本地ID，由stopRecord接口获得
			});
    }
    
    document.querySelector("#translateVoice").onclick = function(e){
    	e.preventDefault();
    	wx.translateVoice({
				localId: localId, // 需要识别的音频的本地Id，由录音相关接口获得
				isShowProgressTips: 1, // 默认为1，显示进度提示
				success: function (res) {
				alert(res.translateResult); // 语音识别的结果
				}
				});
    }
    
    document.querySelector("#openLocation").onclick = function(e){
    	e.preventDefault();
    	wx.openLocation({
					latitude: latitude, // 纬度，浮点数，范围为90 ~ -90
					longitude: longitude, // 经度，浮点数，范围为180 ~ -180。
					name: '就是这儿', // 位置名
					address: '一个神秘的地方', // 地址详情说明
					scale: 14, // 地图缩放级别,整形值,范围从1~28。默认为最大
					infoUrl: 'http://www.feifanedu.com.cn' // 在查看位置界面底部显示的超链接,可点击跳转
				});
    }
    
    document.querySelector("#getLocation").onclick = function(e){
    	e.preventDefault();
    	wx.getLocation({
				type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
				success: function (res) {
				latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
				longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
				var speed = res.speed; // 速度，以米/每秒计
				var accuracy = res.accuracy; // 位置精度
				}
			});
    }
    
    
  });
</script>
</html>
