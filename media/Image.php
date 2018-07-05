<?php
/**
 * Created by lukez
 * Date: 17/7/26
 */

// TODO::特别早期版本 需要重写
namespace luzhi\media;

class Image {

	/**
	 * 创建缩略图
	 * 效果为将图片按比例缩放 并且水平或垂直居中于画布中
	 * @param  $pic 指定图片位置 应为相对于网站根目录的相对路径 比如./img/imp.jpg
	 * @param  $w 指定缩略图宽 
	 * @param  $h 指定缩略图高
	 * @return 生成缩略图路径 
	 */

	public static function mkThumb($pic,$w=200,$h=200){

		if (getimagesize(ROOT.$pic)['mime'] == 'image/gif') {
			return '不支持gif';
			exit();
		}

		$big = imagecreatefromjpeg(ROOT.$pic); //引入原图

		$small = imagecreatetruecolor($w, $h); //创建画布 

		$white = imagecolorallocate($small, 255, 255, 255); //创建颜色 白

		imagefill($small, 0, 0, $white);//为画布着色

		list($bw,$bh) = getimagesize(ROOT.$pic); //获取原图宽高

		$bfb = min($w/$bw,$h/$bh);  //缩放比例，根据画布宽高除以图片结果越小 则以它为标准缩放 

		$bsw = ($w-$bw*$bfb)/2; //获取画布原点x

		$bsh = ($h-$bh*$bfb)/2; //获取画布原点y

		imagecopyresampled($small,$big,$bsw,$bsh,0,0,$bw*$bfb,$bh*$bfb,$bw,$bh);

		// 参数解释：1指定画布 2指定载入图片 3指定画布原点x轴 4指定画布原点y轴 5设定载入图片要载入的区域x坐标
		// 6设定载入图片要载入的区域y 7指定缩放宽 8指定缩放高 9指定图片宽 10指定图片高 

		// 首先原图会根据5,6的原点作为左上角裁剪 然后通过9,10的参数再裁剪符合大小 然后缩放到7,8的大小,最后以原点
		// 为左上角与画图原点对齐
		$upload = new Upload();
		$dir = $upload->createDir();
		$name = Upload::randStrName(4);
		$filename = $dir.'/'.$name.'.'.'jpg';
		
		imagejpeg($small,$filename); //此为覆盖原图
		imagedestroy($small);
		imagedestroy($big);
		return $filename;
	}

	/**
	* 添加水印
	* @param string $pic 原始图片路径,以web根目录为起点,/upload/xxxx,而不是D:/www
	* @param string $water 水印图片
	* @param string $x 水印图片位置
	* @param string $y 水印图片位置
	* @param string $opacity 水印图片透明度
	* @return string 加水印的图片路径
	*/
	public static function water($picdir,$waterdir,$x=0,$y=0,$opacity=127){
		$allowExt = array('jpg' , 'jpeg' , 'png');
		//图片检测类型
		$type = getimagesize(ROOT.$picdir)['mime'];	
		//不符合类型则停止执行
		if (!in_array(strchr($type,'image/'))) {
			return '不支持该图片类型';
		}
		//水印检测类型
		$type = getimagesize(ROOT.$waterdir)['mime'];	
		//不符合类型则停止执行
		if (!in_array(strchr($type,'image/'))) {
			return '不支持该水印类型';
		}
		//通过图片类型判断执行语句
		$from = getimagesize(ROOT.$picdir)['mime'] == "image/jpeg" ? 'imagecreatefromjpeg':'imagecreatefrompng';
		$fromw = getimagesize(ROOT.$waterdir)['mime'] == "image/jpeg" ? 'imagecreatefromjpeg':'imagecreatefrompng';

		$pic = $from(ROOT.$picdir); //引入原图

		$water = $fromw(ROOT.$waterdir); //引入水印图

		list($ww,$wh) = getimagesize(ROOT.$waterdir); //获取水印图大小

		imagecopymerge($pic, $water, $x, $y, 0, 0, $ww, $wh, $opacity); //制作

		imagejpeg($pic,$picdir); //保存

		imagedestroy($pic); //销毁

		imagedestroy($water); //销毁

		return $picdir; //返回路径

	}
}
//iImage测试通过

?>
