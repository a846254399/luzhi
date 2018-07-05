<?php
/**
 * @author lukez 
 */
namespace luzhi\web;

//入口
class Verify{

	// 验证码位数
	public $num;

	public function show(){
		//获取验证码
		$all = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789';
		$verify = substr(str_shuffle($all),0,4);
		session_start();
		$_SESSION['verify'] = strtolower($verify);	
		//验证码画图
		$pic = imagecreatetruecolor(50,30);
		$white = imagecolorallocate($pic,255,255,255); //背景色
		$green = imagecolorallocate($pic,46,204,113); //验证码颜色
		imagefill($pic, 0, 0, $white);
		imagestring($pic, 5, 6, 7, $verify, $green);

		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);		
		header('Pragma: no-cache');
		header("content-type: image/png");

		imagepng($pic); //生成验证码
		imagedestroy($pic);
	}

}
