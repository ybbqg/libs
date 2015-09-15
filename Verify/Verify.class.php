<?php

/**
 * Created by User: fegrace
 * Date: 2015/9/6 12:35
 * 使用静态方法实现验证码
 */

//Verify::$width = 130;
//Verify::$height = 28;
//Verify::$len = 5;
//Verify::$font_size = 16;
//Verify::$font = './msyh.ttf';
//Verify::$session_name = 'verify';
Verify::show();

class Verify
{
    public static $width = 120;
    public static $height = 30;
    public static $len = 4;
    public static $font_size = 18;
    public static $font = './msyh.ttf';
    public static $session_name = 'verify';
    private static $img;
    private static $seed = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';

    public static function show()
    {
        //声明头部
        header('Content-Type: image/jpeg');
        //创建画布
        self::$img = imagecreatetruecolor(self::$width, self::$height);
        $bg = imagecolorallocate(self::$img, 255, 255, 255);
        imagefill(self::$img, 0, 0, $bg);

        //画线，点，文字
        self::line();
        self::point();
        self::text();

        //输出图像
        imagejpeg(self::$img);
        //销毁图像
        imagedestroy(self::$img);
    }

    /*画线*/
    private static function line()
    {
        for ($i = 0; $i < 10; $i++) {
            $color = imagecolorallocate(self::$img, mt_rand(120, 255), mt_rand(120, 255), mt_rand(120, 255));
            imageline(self::$img, mt_rand(0, self::$width), mt_rand(0, self::$height), mt_rand(0, self::$width), mt_rand(0, self::$height), $color);
        }
    }

    /*画点*/
    private static function point()
    {
        for ($i = 0; $i < 50; $i++) {
            $color = imagecolorallocate(self::$img, mt_rand(120, 255), mt_rand(120, 255), mt_rand(120, 255));
            imagesetpixel(self::$img, mt_rand(0, self::$width),mt_rand(0, self::$height),$color);
        }
    }

    /*验证码文本内容*/
    private static function text()
    {
        $seed_num = strlen(self::$seed) -1;
        $step = self::$width/self::$len;
        $y = self::$height/2  + 8;
        $verify_code = '';
        for ($i = 0; $i < self::$len; $i++) {
            $color = imagecolorallocate(self::$img, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
            $text =  self::$seed[mt_rand(0, $seed_num)];
            imagettftext(self::$img, self::$font_size, mt_rand(-15,15), $i * $step + 10, $y, $color, self::$font, $text);
            $verify_code .= $text;
        }
        self::setSession($verify_code);
    }

    /*设置SESSION*/
    private static function setSession($verify_code){
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION[self::$session_name] = $verify_code;
    }
}