<?php
if (!(defined('IN_IA')))
{
    exit('Access Denied');
}
/**
 * 首页
 */
require_once IA_ROOT . '/addons/nets_haojk/function/phpqrcode.php';

class Poster
{
	public $dirurl;//图片目录地址
	public $url;//图片URL地址
	
    public function __construct()
    {
		global $_W;
		$this->dirurl=IA_ROOT."/addons/nets_haojk/cache/";
		$this->url=$_W['siteroot']."addons/nets_haojk/cache/";
	}
	
	/*
	* 获取会员推广海报
	* $uid 会员ID
	*/
	public function getQrPoster($uid,$iswxapp=0,$qrpath=''){
		global $_W;
		$uri=url('entry',array('m'=>'nets_haojk','do'=>'index',"from_uid"=>$uid));
		$value=$_W['siteroot'].substr($uri,1,strlen($uri));
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = 8;//生成图片大小 
		//生成推广二维码图片 
		$filename=$uid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$uid."_qrcode.jpg";
		if($iswxapp==1){//小程序的文件名和公众号的区分下
			$filename=$uid."_appqrcode.jpg";
			$qrcode=$this->dirurl.'qr_'.$uid.'.jpg';
		}
		
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(file_exists($ex_qr)){
			//return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
		}
		//小程序的这里不需要生成二维码
		if($iswxapp==1){
			
		}else{//公众号的重新生成二维码
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}
		if(!empty($qrpath)){
			$qrcode=$qrpath;
		}
		
		
		$bg = $this->dirurl.'bg.png';//准备好的背景图片 
		$uniac_bg=$this->dirurl.$_W['uniacid'].'_bg.png';//公众号设置的背景图片
		if(file_exists($uniac_bg)){  
			$bg=$uniac_bg;
		}
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		
		$logo_qr_width = $QR_width; 
		$scale = $bg_width/$logo_qr_width; 
		$logo_qr_height = $QR_height; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-60; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-40; 
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//输出图片 
		imagejpeg($bg, $ex_qr,9); 
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}
	
	/*
	* 获取商品推广海报
	* $itemid 淘宝商品标识ID
	* $title 商品标题
	* $remark 商品描述
	* $url 商品购买链接
	* $picture 商品图片
	* $price 商品原价
	* $couponedprice 券后价券后价
	* $couponprice 优惠券金额
	*/
	public function getGoodPoster($itemid,$title,$remark,$url,$picture,$price,$couponedprice,$couponprice,$size=7,$qrpath=""){
		//保存图片到本地
		/*
		$content = file_get_contents($picture);
		
		$pictureurl=$this->dirurl.$filename;
		file_put_contents($pictureurl, $content);
		*/
		if(empty($url)){
			$url="商品出错";
		}
		if(empty($couponprice)){
			$couponprice=0.00;
		}
		if($couponprice=="null"){
			$couponprice=0.00;
		}
		$filename=time().".jpg";
		$pictureurl=$this->dirurl.$filename;
		$this->getImage($picture,$this->dirurl,$filename,0);
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = $size;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$itemid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$itemid."_qrcode.jpg";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(empty($qrpath)){
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}else{
			$qrcode=$qrpath;
		}
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'goods_bg.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		$picture = imagecreatefromstring(file_get_contents($pictureurl));
		$picture_width = imagesx($picture);//二维码图片宽度 
		$picture_height = imagesy($picture);//二维码图片高度 
		$logo_picture_width = 640;//$picture_width; 
		$logo_picture_height = 656;//$picture_height; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_picture_width)-60; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_picture_height)-40; 
		imagecopyresampled($bg, $picture, 28, 65, 0, 0, $logo_picture_width, 
		$logo_picture_height, $picture_width, $picture_height); 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-22; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-65; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 18;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 26;      //左边距
		$top = $bg_height-192;       //顶边距
		$title1=mb_substr($title , 0 , 18, 'utf-8');
		
		$title2=mb_substr(str_replace($title1,"",$title) , 0 , 18, 'utf-8');//str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title1);
		
		$top = $bg_height-162;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		
		$black = imagecolorallocate($bg, 190, 190, 190);//字体颜色 RGB
		$fontSize = 14;   //字体大小
		$left = 100;      //左边距
		$top = $bg_height-130;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $price);
		
		$black = imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 20;   //字体大小
		$left = 100;      //左边距
		$top = $bg_height-80;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponedprice);
		
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 15;   //字体大小
		$left = 150;      //左边距
		$top = $bg_height-33;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponprice);
		imagejpeg($bg, $ex_qr,75); 
		/*
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		*/
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}

	/*
	* 获取商品推广海报
	* $itemid 淘宝商品标识ID
	* $title 商品标题
	* $remark 商品描述
	* $url 商品购买链接
	* $picture 商品图片
	* $price 商品原价
	* $couponedprice 券后价券后价
	* $couponprice 优惠券金额
	*/
	public function getGoodPoster2($itemid,$title,$remark,$url,$picture,$price,$couponedprice,$couponprice,$size=7,$qrpath=""){
		//保存图片到本地
		/*
		$content = file_get_contents($picture);
		
		$pictureurl=$this->dirurl.$filename;
		file_put_contents($pictureurl, $content);
		*/
		if(empty($url)){
			$url="商品出错"; 
		}
		if(empty($couponprice)){
			$couponprice=0.00;
		}
		if($couponprice=="null"){
			$couponprice=0.00;
		}
		$filename=time().".jpg";
		$pictureurl=$this->dirurl.$filename;
		$this->getImage($picture,$this->dirurl,$filename,0);
		//$this->resize_image($pictureurl, $pictureurl, 400, 400);
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = $size;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$itemid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$itemid."_qrcode.jpg";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(empty($qrpath)){
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}else{
			$qrcode=$qrpath;
		}
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'goods_bg2.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		$picture = imagecreatefromstring(file_get_contents($pictureurl));
		$picture_width = imagesx($picture);//二维码图片宽度 
		$picture_height = imagesy($picture);//二维码图片高度 
		$logo_picture_width = 520;//$picture_width; 
		$logo_picture_height = 520;//$picture_height; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_picture_width)-60; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_picture_height)-40; 
		imagecopyresampled($bg, $picture, 60, 30, 0, 0, $logo_picture_width, 
		$logo_picture_height, $picture_width, $picture_height); 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-35; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-35; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 20;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 36;      //左边距
		$top = $bg_height-342;       //顶边距
		$title1=mb_substr($title , 0 , 18, 'utf-8');
		
		$title2=mb_substr(str_replace($title1,"",$title) , 0 , 18, 'utf-8');//str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title1);
		
		$top = $bg_height-300;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		
		$black = imagecolorallocate($bg, 190, 190, 190);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 300;      //左边距
		$top2 = $bg_height-200;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top2, $black, $font, $price);
		
		$black = imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 70;      //左边距
		$top = $bg_height-200;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponedprice);
		
		$black = imagecolorallocate($bg, 190, 190, 190);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 490;      //左边距
		//$top = $bg_height-33;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponprice);
		imagejpeg($bg, $ex_qr,75); 
		/*
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		*/
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}

	/*
	* 获取商品推广海报
	* $itemid 淘宝商品标识ID
	* $title 商品标题
	* $remark 商品描述
	* $url 商品购买链接
	* $picture 商品图片
	* $price 商品原价
	* $couponedprice 券后价券后价
	* $couponprice 优惠券金额
	*/
	public function getPddGoodPoster($itemid,$title,$remark,$url,$picture,$price,$couponedprice,$couponprice,$size=7,$qrpath=""){
		//保存图片到本地
		/*
		$content = file_get_contents($picture);
		
		$pictureurl=$this->dirurl.$filename;
		file_put_contents($pictureurl, $content);
		*/
		if(empty($url)){
			$url="商品出错";
		}
		if(empty($couponprice)){
			$couponprice=0.00;
		}
		if($couponprice=="null"){
			$couponprice=0.00;
		}
		$filename=time().".jpg";
		$pictureurl=$this->dirurl.$filename;
		$this->getImage($picture,$this->dirurl,$filename,0);
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = $size;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$itemid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$itemid."_qrcode.jpg";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(empty($qrpath)){
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}else{
			$qrcode=$qrpath;
		}
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'goods_pdd_bg.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		$picture = imagecreatefromstring(file_get_contents($pictureurl));
		$picture_width = imagesx($picture);//二维码图片宽度 
		$picture_height = imagesy($picture);//二维码图片高度 
		$logo_picture_width = 640;//$picture_width; 
		$logo_picture_height = 656;//$picture_height; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_picture_width)-60; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_picture_height)-40; 
		imagecopyresampled($bg, $picture, 28, 65, 0, 0, $logo_picture_width, 
		$logo_picture_height, $picture_width, $picture_height); 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-22; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-65; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 18;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 26;      //左边距
		$top = $bg_height-192;       //顶边距
		$title1=mb_substr($title , 0 , 18, 'utf-8');
		
		$title2=mb_substr(str_replace($title1,"",$title) , 0 , 18, 'utf-8');//str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title1);
		
		$top = $bg_height-162;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		
		$black = imagecolorallocate($bg, 190, 190, 190);//字体颜色 RGB
		$fontSize = 14;   //字体大小
		$left = 100;      //左边距
		$top = $bg_height-130;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $price);
		
		$black = imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 20;   //字体大小
		$left = 100;      //左边距
		$top = $bg_height-80;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponedprice);
		
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 15;   //字体大小
		$left = 150;      //左边距
		$top = $bg_height-33;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponprice);
		imagejpeg($bg, $ex_qr,75); 
		/*
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		*/
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}

	/*
	* 获取商品推广海报
	* $itemid 淘宝商品标识ID
	* $title 商品标题
	* $remark 商品描述
	* $url 商品购买链接
	* $picture 商品图片
	* $price 商品原价
	* $couponedprice 券后价券后价
	* $couponprice 优惠券金额
	*/
	public function getPddGoodPoster2($itemid,$title,$remark,$url,$picture,$price,$couponedprice,$couponprice,$size=7,$qrpath=""){
		//保存图片到本地
		/*
		$content = file_get_contents($picture);
		
		$pictureurl=$this->dirurl.$filename;
		file_put_contents($pictureurl, $content);
		*/
		if(empty($url)){
			$url="商品出错"; 
		}
		if(empty($couponprice)){
			$couponprice=0.00;
		}
		if($couponprice=="null"){
			$couponprice=0.00;
		}
		$filename=time().".jpg";
		$pictureurl=$this->dirurl.$filename;
		$this->getImage($picture,$this->dirurl,$filename,0);
		//$this->resize_image($pictureurl, $pictureurl, 400, 400);
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = $size;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$itemid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$itemid."_qrcode.jpg";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(empty($qrpath)){
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}else{
			$qrcode=$qrpath;
		}
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'goods_pdd_bg2.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		$picture = imagecreatefromstring(file_get_contents($pictureurl));
		$picture_width = imagesx($picture);//二维码图片宽度 
		$picture_height = imagesy($picture);//二维码图片高度 
		$logo_picture_width = 520;//$picture_width; 
		$logo_picture_height = 520;//$picture_height; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_picture_width)-60; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_picture_height)-40; 
		imagecopyresampled($bg, $picture, 60, 30, 0, 0, $logo_picture_width, 
		$logo_picture_height, $picture_width, $picture_height); 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-35; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-35; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 20;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 36;      //左边距
		$top = $bg_height-342;       //顶边距
		$title1=mb_substr($title , 0 , 18, 'utf-8');
		
		$title2=mb_substr(str_replace($title1,"",$title) , 0 , 18, 'utf-8');//str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title1);
		
		$top = $bg_height-300;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		
		$black =  imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 300;      //左边距
		$top2 = $bg_height-200;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top2, $black, $font, $price);
		
		$black = imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 70;      //左边距
		$top = $bg_height-200;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponedprice);
		
		$black = imagecolorallocate($bg, 190, 190, 190);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 490;      //左边距
		//$top = $bg_height-33;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponprice);
		imagejpeg($bg, $ex_qr,75); 
		/*
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		*/
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}


    /*
	* 获取商品推广海报
	* $itemid 淘宝商品标识ID
	* $title 商品标题
	* $remark 商品描述
	* $url 商品购买链接
	* $picture 商品图片
	* $price 商品原价
	* $couponedprice 券后价券后价
	* $couponprice 优惠券金额
	*/
	public function getMgjGoodPoster($itemid,$title,$remark,$url,$picture,$price,$couponedprice,$couponprice,$size=7,$qrpath=""){
		//保存图片到本地
		/*
		$content = file_get_contents($picture);
		
		$pictureurl=$this->dirurl.$filename;
		file_put_contents($pictureurl, $content);
		*/
		if(empty($url)){
			$url="商品出错";
		}
		if(empty($couponprice)){
			$couponprice=0.00;
		}
		if($couponprice=="null"){
			$couponprice=0.00;
		}
		$filename=time().".jpg";
		$pictureurl=$this->dirurl.$filename;
		$this->getImage($picture,$this->dirurl,$filename,0);
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = $size;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$itemid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$itemid."_qrcode.jpg";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(empty($qrpath)){
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}else{
			$qrcode=$qrpath;
		}
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'goods_bg.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		$picture = imagecreatefromstring(file_get_contents($pictureurl));
		$picture_width = imagesx($picture);//二维码图片宽度 
		$picture_height = imagesy($picture);//二维码图片高度 
		$logo_picture_width = 640;//$picture_width; 
		$logo_picture_height = 656;//$picture_height; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_picture_width)-60; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_picture_height)-40; 
		imagecopyresampled($bg, $picture, 28, 65, 0, 0, $logo_picture_width, 
		$logo_picture_height, $picture_width, $picture_height); 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-22; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-65; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 18;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 26;      //左边距
		$top = $bg_height-192;       //顶边距
		$title1=mb_substr($title , 0 , 18, 'utf-8');
		
		$title2=mb_substr(str_replace($title1,"",$title) , 0 , 18, 'utf-8');//str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title1);
		
		$top = $bg_height-162;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		
		$black = imagecolorallocate($bg, 190, 190, 190);//字体颜色 RGB
		$fontSize = 14;   //字体大小
		$left = 100;      //左边距
		$top = $bg_height-130;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $price);
		
		$black = imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 20;   //字体大小
		$left = 100;      //左边距
		$top = $bg_height-80;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponedprice);
		
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 15;   //字体大小
		$left = 150;      //左边距
		$top = $bg_height-33;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponprice);
		imagejpeg($bg, $ex_qr,75); 
		/*
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		*/
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}

	/*
	* 获取商品推广海报
	* $itemid 淘宝商品标识ID
	* $title 商品标题
	* $remark 商品描述
	* $url 商品购买链接
	* $picture 商品图片
	* $price 商品原价
	* $couponedprice 券后价券后价
	* $couponprice 优惠券金额
	*/
	public function getMgjGoodPoster2($itemid,$title,$remark,$url,$picture,$price,$couponedprice,$couponprice,$size=7,$qrpath=""){
		//保存图片到本地
		/*
		$content = file_get_contents($picture);
		
		$pictureurl=$this->dirurl.$filename;
		file_put_contents($pictureurl, $content);
		*/
		if(empty($url)){
			$url="商品出错"; 
		}
		if(empty($couponprice)){
			$couponprice=0.00;
		}
		if($couponprice=="null"){
			$couponprice=0.00;
		}
		$filename=time().".jpg";
		$pictureurl=$this->dirurl.$filename;
		$this->getImage($picture,$this->dirurl,$filename,0);
		//$this->resize_image($pictureurl, $pictureurl, 400, 400);
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = $size;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$itemid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$itemid."_qrcode.jpg";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(empty($qrpath)){
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}else{
			$qrcode=$qrpath;
		}
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'goods_bg3.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		$picture = imagecreatefromstring(file_get_contents($pictureurl));
		$picture_width = imagesx($picture);//二维码图片宽度 
		$picture_height = imagesy($picture);//二维码图片高度 
		$logo_picture_width = 520;//$picture_width; 
		$logo_picture_height = 520;//$picture_height; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_picture_width)-60; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_picture_height)-40; 
		imagecopyresampled($bg, $picture, 60, 30, 0, 0, $logo_picture_width, 
		$logo_picture_height, $picture_width, $picture_height); 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-35; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-35; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 20;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 36;      //左边距
		$top = $bg_height-342;       //顶边距
		$title1=mb_substr($title , 0 , 18, 'utf-8');
		
		$title2=mb_substr(str_replace($title1,"",$title) , 0 , 18, 'utf-8');//str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title1);
		
		$top = $bg_height-300;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		
		$black =  imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 300;      //左边距
		$top2 = $bg_height-200;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top2, $black, $font, $price);
		
		$black = imagecolorallocate($bg, 178, 34, 34);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 70;      //左边距
		$top = $bg_height-200;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponedprice);
		
		$black = imagecolorallocate($bg, 190, 190, 190);//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 490;      //左边距
		//$top = $bg_height-33;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $couponprice);
		imagejpeg($bg, $ex_qr,75); 
		/*
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		*/
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}


	/*
	* 获取活动推广海报
	* $title 商品标题
	* $time 开奖时间
	* $url 页面访问链接
	* $picture 商品图片
	* $qrpath 二维码地址，若无则生成新的
	*/
	public function getHelpSetPoster($uid,$title,$time,$url,$picture,$size=7,$qrpath=""){
		//保存图片到本地
		/*
		$content = file_get_contents($picture);
		
		$pictureurl=$this->dirurl.$filename;
		file_put_contents($pictureurl, $content);
		*/
		if(empty($url)){
			$url="生成出错"; 
		}
		if(empty($couponprice)){
			$couponprice=0.00;
		}
		if($couponprice=="null"){
			$couponprice=0.00;
		}
		$filename=time().".jpg";
		$pictureurl=$this->dirurl.$filename;
		$this->getImage($picture,$this->dirurl,$filename,0);
		//$this->resize_image($pictureurl, $pictureurl, 400, 400);
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = $size;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$uid."_helpset_exqrcode.jpg";
		$qrcode=$this->dirurl.$uid."_helpset_qrcode.jpg";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		if(empty($qrpath)){
			QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		}else{
			$qrcode=$qrpath;
		}
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'helpset_bg.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		$picture = imagecreatefromstring(file_get_contents($pictureurl));
		$picture_width = imagesx($picture);//二维码图片宽度 
		$picture_height = imagesy($picture);//二维码图片高度 
		$logo_picture_width = 900;//$picture_width; 
		$logo_picture_height = 520;//$picture_height; 
		//载入的图片区域x坐标
		$src_x = 119; 
		//载入的图片区域y坐标
		$src_y = 65; 
		imagecopyresampled($bg, $picture, $src_x, $src_y, 0, 0, $logo_picture_width, 
		$logo_picture_height, $picture_width, $picture_height); 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-385; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-485; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 245  , 245  , 245 );//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 130;      //左边距
		$top = 628;       //顶边距
		$title1=mb_substr($title , 0 , 18, 'utf-8');
		$newtitle1=$title1."         自动开奖";
		$title2=mb_substr(str_replace($title1,"",$title) , 0 , 18, 'utf-8');//str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $newtitle1);
		$top = 680;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		
		$black =  imagecolorallocate($bg, 224 , 245 , 245 );//字体颜色 RGB
		$fontSize = 30;   //字体大小
		$left = 870;      //左边距
		$top2 = 680;       //顶边距
		$time="".$time;
		imagefttext($bg, $fontSize, $circleSize, $left, $top2, $black, $font, $time);
		
		
		imagejpeg($bg, $ex_qr,75); 
		/*
		//重新组合图片并调整大小 
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x ,
		//int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		//$dst_image：新建的图片
		//$src_image：需要载入的图片
		//$dst_x：设定需要载入的图片在新图中的x坐标
		//$dst_y：设定需要载入的图片在新图中的y坐标
		//$src_x：设定载入图片要载入的区域x坐标
		//$src_y：设定载入图片要载入的区域y坐标
		//$dst_w：设定载入的原图的宽度（在此设置缩放）
		//$dst_h：设定载入的原图的高度（在此设置缩放）
		//$src_w：原图要载入的宽度
		//$src_h：原图要载入的高度
		*/
		$url.="?v=".time();
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}

	/*
	* 获取文章海报
	* $title 标题
	* $remark 描述
	* $url 链接
	* $picture 图片
	*/
	public function getArticlePoster($title,$remark,$url,$picture){
		$article_content=$this->getWxContent($url);
		//$content=iconv("gb2312","utf-8",$content);
		//echo($content);exit;
		//保存图片到本地
		//$content = file_get_contents($picture);
		//$filename="article_img.jpg";
		//$pictureurl=$this->dirurl.$filename;
		//file_put_contents($pictureurl, $content);
		
		$itemid=time();
		$value=$url;
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = 3;//生成图片大小 
		//0.1生成推广二维码图片 
		$filename=$itemid."_exqrcode.jpg";
		$qrcode=$this->dirurl.$itemid."_qrcode.png";
		$ex_qr=$this->dirurl.$filename;
		$url=$this->url.$filename;
		QRcode::png($value, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2); 
		
		
		//0.2载入商品图二维码到背景图
		$bg = $this->dirurl.'article_bg.jpg';//准备好的背景图片 
		$bg = imagecreatefromstring(file_get_contents($bg)); 
		$bg_width = imagesx($bg);//bg图片宽度 
		$bg_height = imagesy($bg);//bg图片高度 
		
		//0.3载入二维码
		$QR = imagecreatefromstring(file_get_contents($qrcode));
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_qr_width = $QR_width-70; 
		$logo_qr_height = $QR_height-70; 
		//载入的图片区域x坐标
		$src_x = ($bg_width - $logo_qr_width)-22; 
		//载入的图片区域y坐标
		$src_y = ($bg_height - $logo_qr_height)-65; 
		imagecopyresampled($bg, $QR, $src_x, $src_y, 0, 0, $logo_qr_width, 
		$logo_qr_height, $QR_width, $QR_height); 
		
		//0.4载入文字
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 18;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 26;      //左边距
		$top = $bg_height-192;       //顶边距
		$title1=substr($title , 0 , 60);
		$title2=str_replace($title1,"",$title);
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title1);
		$top = $bg_height-162;       //顶边距
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $title2);
		//0.5载入内容
		$font = $this->dirurl.'msyh.ttf';//字体
		$black = imagecolorallocate($bg, 28, 28, 28);//字体颜色 RGB
		$fontSize = 12;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 26;      //左边距
		$top = 200;       //顶边距
		
		imagefttext($bg, $fontSize, $circleSize, $left, $top, $black, $font, $article_content);
		
		
		
		
		imagepng($bg, $ex_qr); 
		return array("code"=>"200","msg"=>"操作成功!","res"=>$url);	
	}
	
	//获取微信内容
	function getWxContent($url){
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		curl_setopt($ch,CURLOPT_NOBODY,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_USERAGENT, 'Sogouspider');
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11');
		$html=curl_exec($ch);
		$mark='<div class="rich_media_content " id="js_content">';
		$content=mb_substr($html,strpos($html,$mark)+strlen($mark));
		$content=strip_tags(trim(mb_substr($content,0,strpos($content,'<script type="text/javascript">')-50)));
		$content=mb_substr($content , 0 , 600,"utf-8");
		
		$content1 = "";
		 // 将字符串拆分成一个个单字 保存到数组 letter 中
		 for ($i=0;$i<mb_strlen($content);$i++) {
			$letter[] = mb_substr($content, $i, 1,"utf-8");
		 }
		 $j=0;
		 foreach ($letter as $l) {
			 $j++;
			  $teststr = $content."".$l;
			  // 判断拼接后的字符串是否超过预设的宽度
			  if (($j%40==0) && ($content !== "")) {
				$content1 .= "\n";
			  }
			  $content1 .= $l;
		 }
		 $content1=str_replace("&nbsp;","",$content1);
		 $content1=str_replace("&nbsp;","",$content1);
		 $content1=str_replace(" & nbsp;","",$content1);
		 $content1.="（长按识别二维码阅读更多）";
		 //echo $content1;
		curl_close($ch);
		return $content1;
	}
	/* 
	*功能：php完美实现下载远程图片保存到本地 
	*参数：文件url,保存文件目录,保存文件名称，使用的下载方式 
	*当保存文件名称为空时则使用远程文件原来的名称 
	*/ 
	function getImage($url,$save_dir='',$filename='',$type=0){ 
		if(trim($url)==''){ 
			return array('file_name'=>'','save_path'=>'','error'=>1); 
		} 
		if(trim($save_dir)==''){ 
			$save_dir='./'; 
		} 
		if(trim($filename)==''){//保存文件名 
			$ext=strrchr($url,'.'); 
			if($ext!='.gif'&&$ext!='.jpg'){ 
				return array('file_name'=>'','save_path'=>'','error'=>3); 
			} 
			$filename=time().$ext; 
		} 
		if(0!==strrpos($save_dir,'/')){ 
			$save_dir.='/'; 
		} 
		file_put_contents($save_dir."log.txt", time(),FILE_APPEND);
		//创建保存目录 
		if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){ 
			return array('file_name'=>'','save_path'=>'','error'=>5); 
		} 
		//获取远程文件所采用的方法  
		if($type){ 
			file_put_contents($save_dir."log.txt",time().":".$url."\n" ,FILE_APPEND);
			$ch=curl_init(); 
			$timeout=0; 
			curl_setopt($ch,CURLOPT_URL,$url); 
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
			curl_setopt($ch, CURLOPT_TIMEOUT,60);   //只需要设置一个秒的数量就可以  

			$img=curl_exec($ch); 
			
			curl_close($ch); 
		}else{ 
			ob_start();  
			readfile($url); 
			$img=ob_get_contents();  
			ob_end_clean();  
		} 
		//$size=strlen($img); 
		//文件大小  
		$fp2=@fopen($save_dir.$filename,'a'); 
		fwrite($fp2,$img); 
		fclose($fp2); 
		unset($img,$url); 
		return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0); 
	} 

	/** 
	 * 改变图片的宽高 
	 *  
	 * @param string $img_src 原图片的存放地址或url  
	 * @param string $new_img_path  新图片的存放地址  
	 * @param int $new_width  新图片的宽度  
	 * @param int $new_height 新图片的高度 
	 * @return bool  成功true, 失败false 
	 */  
	function resize_image($img_src, $new_img_path, $new_width, $new_height)  
	{  
		$img_info = @getimagesize($img_src);  
		if (!$img_info || $new_width < 1 || $new_height < 1 || empty($new_img_path)) {  
			return false;  
		}  
		if (strpos($img_info['mime'], 'jpeg') !== false) {  
			$pic_obj = imagecreatefromjpeg($img_src);  
		} else if (strpos($img_info['mime'], 'gif') !== false) {  
			$pic_obj = imagecreatefromgif($img_src);  
		} else if (strpos($img_info['mime'], 'png') !== false) {  
			$pic_obj = imagecreatefrompng($img_src);  
		} else {  
			return false;  
		}  
		$pic_width = imagesx($pic_obj);  
		$pic_height = imagesy($pic_obj);  
		if (function_exists("imagecopyresampled")) {  
			$new_img = imagecreatetruecolor($new_width,$new_height);  
			imagecopyresampled($new_img, $pic_obj, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);  
		} else {  
			$new_img = imagecreate($new_width, $new_height);  
			imagecopyresized($new_img, $pic_obj, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);  
		}  
		if (preg_match('~.([^.]+)$~', $new_img_path, $match)) {  
			$new_type = strtolower($match[1]);  
			switch ($new_type) {  
				case 'jpg':  
					imagejpeg($new_img, $new_img_path);  
					break;  
				case 'gif':  
					imagegif($new_img, $new_img_path);  
					break;  
				case 'png':  
					imagepng($new_img, $new_img_path);  
					break;  
				default:  
					imagejpeg($new_img, $new_img_path);  
			}  
		} else {  
			imagejpeg($new_img, $new_img_path);  
		}  
		imagedestroy($pic_obj);  
		imagedestroy($new_img);  
		return true;  
	}  
}
?>