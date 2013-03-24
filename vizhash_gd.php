<?php
// VizHash_GD 0.0.4 beta
// Visual Hash implementation in php4+GD
// See: http://sebsauvage.net/wiki/doku.php?id=php:vizhash_gd
// This is free software under the zlib/libpng licence
// http://www.opensource.org/licenses/zlib-license.php
date_default_timezone_set('Europe/Paris');
$VERSION = '0.0.4 beta';
error_reporting(0);

if (!isset($_GET['t'])) // Nothing to hash provided: We display the form
{ 
    header('Content-type: text/html'); 
    echo '<div style="font-family:Verdana,sans-serif; font-size:10pt;">VizHash_GD '.$VERSION.' - Visual Hash implementation in php4+GD.<br>';
    echo 'See <a href="http://sebsauvage.net/wiki/doku.php?id=php:vizhash_gd">homepage</a> for more details.';
    echo 'This is free software under the <a href="http://www.opensource.org/licenses/zlib-license.php">zlib/libpng licence</a><br>';
    echo '<form action="'.$_SERVER['PHP_SELF'].'">Enter string to hash: <input name="t" id="t" type="String to hash" size="100" value="" />';
    echo '<input id="ok" type="submit" value="Hash" /></form>';
    echo '<br>VizHash_GD accepts the following parameters:<br>';
    echo '<blockquote><b>t</b> : string to hash<br><b>width</b>: width of image (in pixels) (default:80, max:256)<br><b>height</b>: height of image (in pixels) (default:80, max:256)</blockquote><br>';
    echo 'Examples: "hi"';
    $example_url=$_SERVER['PHP_SELF'].'?t=hi';
    echo '<br>12x12:<br><img src="'.$example_url.'&width=12&height=12">';
    echo '<br>16x16:<br><img src="'.$example_url.'&width=16&height=16">';
    echo '<br>32x32:<br><img src="'.$example_url.'&width=32&height=32">';
    echo '<br>64x64:<br><img src="'.$example_url.'&width=64&height=64">';
    echo '<br>128x32:<br><img src="'.$example_url.'&width=128&height=32">';
    echo '<br>256x256:<br><img src="'.$example_url.'&width=256&height=256">';
    echo '</div>';
    exit;
 }
$text = $_GET['t'];

// Size of vizshash in pixels (default:80x80)
$width=80; if (isset($_GET['width'])) { $width=$_GET['width']; }
$height=80; if (isset($_GET['height'])) { $height=$_GET['height']; }
if ($width>256) die("width is too big.");
if ($height>256) die("height is too big.");

// We hash the input string. (We don't use hash() to stay compatible with php4.)
$hash=sha1($text).md5($text);
$hash=$hash.strrev($hash);  # more data to make graphics

// We convert the hash into an array of integers.
$VALUES=array();
for($i=0; $i<strlen($hash); $i=$i+2){ array_push($VALUES,hexdec(substr($hash,$i,2))); }
$VALUES_INDEX=0; // to walk the array.

function getInt() // Returns a single integer from the $VALUES array (0...255)
{
    global $VALUES,$VALUES_INDEX;
    $v= $VALUES[$VALUES_INDEX]; 
    $VALUES_INDEX++;
    $VALUES_INDEX %= count($VALUES); // Warp around the array
    return $v;
}

function getX() // Returns a single integer from the array (roughly mapped to image width) 
{
    global $width;
    return $width*getInt()/256;
}

function getY() // Returns a single integer from the array (roughly mapped to image height) 
{ 
    global $height; 
    return $height*getInt()/256;
}

# Gradient function taken from:
# http://www.supportduweb.com/scripts_tutoriaux-code-source-41-gd-faire-un-degrade-en-php-gd-fonction-degrade-imagerie.html
function degrade($img,$direction,$color1,$color2)
{
        if($direction=='h') { $size = imagesx($img); $sizeinv = imagesy($img); }
        else { $size = imagesy($img); $sizeinv = imagesx($img);}
        $diffs = array(
                (($color2[0]-$color1[0])/$size),
                (($color2[1]-$color1[1])/$size),
                (($color2[2]-$color1[2])/$size)
        );
        for($i=0;$i<$size;$i++)
        {
                $r = $color1[0]+($diffs[0]*$i);
                $g = $color1[1]+($diffs[1]*$i);
                $b = $color1[2]+($diffs[2]*$i);
                if($direction=='h') { imageline($img,$i,0,$i,$sizeinv,imagecolorallocate($img,$r,$g,$b)); }
                else { imageline($img,0,$i,$sizeinv,$i,imagecolorallocate($img,$r,$g,$b)); }
        }
        return $img;
}

// Then use these integers to drive the creation of an image.
$image = imagecreatetruecolor($width,$height);
imageantialias($image, true); // Use antialiasing (if available)

$r0 = getInt();$r=$r0;
$g0 = getInt();$g=$g0;
$b0 = getInt();$b=$b0;

// First, create an image with a specific gradient background.
$op='v'; if ((getInt()%2)==0) { $op='h'; };
$image = degrade($image,$op,array($r0,$g0,$b0),array(0,0,0));

function drawshape($image,$action,$color)
{
    switch($action%7)
    {
        case 0:
            ImageFilledRectangle ($image,getX(),getY(),getX(),getY(),$color);  
            break;
        case 1:
        case 2:
            ImageFilledEllipse ($image, getX(), getY(), getX(), getY(), $color);  
            break;
        case 3:
            $points = array(getX(), getY(), getX(), getY(), getX(), getY(),getX(), getY());
            ImageFilledPolygon ($image, $points, 4, $color);
            break;
        case 4:
        case 5:
        case 6:
            $start=getInt()*360/256; $end=$start+getInt()*180/256;
            ImageFilledArc ($image, getX(), getY(), getX(), getY(),$start,$end,$color,IMG_ARC_PIE);
            break;     
    }
}

for($i=0; $i<7; $i=$i+1)
{     
    $action=getInt();
    $color = imagecolorallocate($image, $r,$g,$b);
    $r = ($r0 + getInt()/25)%256;
    $g = ($g0 + getInt()/25)%256;
    $b = ($b0 + getInt()/25)%256;
    $r0=$r; $g0=$g; $b0=$b;
    drawshape($image,$action,$color);   
}
$color = imagecolorallocate($image,getInt(),getInt(),getInt());
drawshape($image,getInt(),$color);

// Image expires in 7 days (to lighten the load on the server)
// and allow image to be cached by proxies.
$duration=7*24*60*60;
header ('Expires: ' . gmdate ('D, d M Y H:i:s', time() + $duration) . ' GMT');
header('Cache-Control: max-age='.$duration.', public');

// Prevent some servers to add "Pragma:no-cache" by default
header('Pragma:cache');

header('Content-type: image/png');
imagepng($image); // Return the image in PNG format.
echo ' -- vizhash_gd '.$VERSION.' by sebsauvage.net';
imagedestroy($image);
?>