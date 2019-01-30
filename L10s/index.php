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
  	<link rel="stylesheet" type="text/css" href="css/lightlivestyle.css"/>
  	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"/>
  <title></title>
</head>
<body>
  
  
  <div class="live-room">
			<header>
				<div class="box1">
				<div class="head">
					<i class="fa fa-home"></i>直播间主页
				</div>
				<div class="invite pull-right">
					<i class="fa fa-share-square-o"></i>
					<span style="display: block;">邀请卡</span>
				</div>
				</div>
				<div class="box2">
					<div class="logo">
					<img src="img/logo.jpg">
				</div>
				<div class="live-name">
					<p class="school-name">上海非凡进修学院</p>
					<p class="live-status">100人次 <span>● 进行中</span> </p>
				</div>
				<div class="share">
					
					<span class="fa fa-share-square-o"></span>
					<div>邀请卡</div>
				</div>
				
			</header>
			<div class="chat-list">
				<ul>
				</ul>
			</div>
			<footer>
				<div class="tab-bar">
					<div class="tab-item item-1 active">
						<span class="fa fa-microphone"></span>
						<p>语音</p>
					</div>
					<div class="tab-item item-2">
						<span class="fa fa-pencil"></span>
						<p>文字</p>
					</div>
					<div class="tab-item item-3">
						<span class="fa fa-th-large"></span>
						<p>互动</p>
					</div>
					<div class="tab-item item-4">
						<span class="fa fa-book"></span>
						<p>备课</p>
					</div>
					<div class="tab-item item-5">
						<span class="fa fa-th-list"></span>
						<p>操作</p>
					</div>
				</div>
				<div class="tab-panel">
					<div class="record">
						<p>点击开始录音</p>
						<span id="record" class="fa fa-circle "></span>
					</div>
					<div class="text"></div>
				</div>
			</footer>
			
		</div>
  
		<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
  <script src="js/zepto_1.1.3.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/weui.min.js" type="text/javascript" charset="utf-8"></script>
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
  var recordFlag = false;
  var localId="";
  wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: ["startRecord","onVoiceRecordEnd","stopRecord","playVoice","chooseImage","previewImage"]// 所有要调用的 API 都要加到这个列表中
  });
  wx.ready(function () {// 在这里调用 API
    $("#record").click(function(){
    	if(recordFlag){
    		 $(".record p").text("点击开始录音"); 
							  	  wx.stopRecord({
										success: function (res) {
												localId = res.localId;
												addMsg('voice',localId);
											}
										});
    	}else {
    		$(".record p").text("正在录音中...."); 
							  	  wx.startRecord();
    	}
    	recordFlag=!recordFlag;
    });
    
    // 录音时间超过一分钟没有停止的时候会执行 complete 回调
						wx.onVoiceRecordEnd({
								complete: function (res) {
									localId = res.localId;
									recordFlag = false;
									$(".record p").text("点击开始录音"); 
									addMsg('voice',localId);
								}
						});
		  });
		  
		  
		  $(document).ready(function(){
  	
			var item=$("footer .tab-item");
			item.click(function(){
				console.log($(this).index())
				var num=$(this).index()
				$(this).addClass("active").siblings("div").removeClass("active");
				$(".tab-panel>div").hide();
				if(num==0){
					$(".tab-panel .record").show();
				}else if(num==2){
					wx.chooseImage({
								count: 1, // 默认9
								sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
								sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
								success: function (res) {
									var localId = res.localIds[0]; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
									addMsg("image",localId);	
								}
							});
				}
				
			})
			})
			
			function addMsg(type,localId='',time){
				var li = $("");
				if(type=="voice"){
					 li=$("<li>\
						<div class='avatar'>\
							<img src='img/fun.jpg'/>\
							<span class='shang'>赏</span>\
						</div>\
						<div class='msg-box'>\
							<div class='nick-name'>李中义<span>主持人</span></div>\
							<div class='msg voice'>\
								<div class='player clear'>\
									<span class='fa fa-play-circle-o pull-left' data-local='"+localId+"'></span>\
									<span class='progress pull-left'>播放中</span>\
									<span class='pull-right'>\
									5″\
									</span>\
								</div>\
							</div>\
							<div class='clear'>\
								<span class='pull-left fa fa-thumbs-up zan'></span>\
								<span class='pull-right return'>撤销</span>\
							</div>\
						</div>\
					</li>");
									}else if(type=="image"){
										li=$("<li>\
						<div class='avatar'>\
							<img src='img/fun.jpg'/>\
							<span class='shang'>赏</span>\
						</div>\
						<div class='msg-box'>\
							<div class='nick-name'>李中义<span>主持人</span></div>\
							<div class='msg image'>\
								<div class=''>\
											<img class='image-data' src='"+localId+"' data-local='"+localId+"'>\
										</div>\
							</div>\
							<div class='clear'>\
								<span class='pull-left fa fa-thumbs-up zan'></span>\
								<span class='pull-right return'>撤销</span>\
							</div>\
						</div>\
					</li>");
									}
				$("ul").append(li);

			}
			
			$("ul").click(function(e){
				e.preventDefault();
				var span = $(e.target);
				console.log(span);
				if(span.hasClass("fa-play-circle-o")){
					 var id = span.data("local");
						 console.log(id);
				 		 	wx.playVoice({
								localId: id 
						  });
				}else if(span.hasClass("image-data")){
					var image = span.data("local");
					wx.previewImage({
							current: image, 
							urls: [image]
						});
				}
				
			})
			
			
		  
    
</script>
</body>
</html>
