<?php 
	function charchange($data){
      if( !empty($data) ){    
        $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5', 'GB2312')) ;   
        if( $fileType != 'UTF-8'){   
          $data = mb_convert_encoding($data ,'utf-8' , $fileType);   
        }   
      }   
      return $data;    
    }
    function charchangerev($data){
      if( !empty($data) ){    
        $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;   
        if( $fileType != 'GBK'){   
          $data = mb_convert_encoding($data ,'gbk' , $fileType);   
        }   
      }   
      return $data;    
    }
    function formatBytes($bytes, $precision = 2) 
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
  
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
  
        $bytes /= pow(1024, $pow);
  
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    function getImageInfo($filename)
    {
    	$filename = charchange($filename);
        if ((list($width, $height, $type, $attr) = getimagesize($filename) ) !== false ) {
            if(($type == 4) || ($type == 13)) return false;
            return array(
                'type' => self::$imageType[$type],
                'width' => $width,
                'height' => $height
            );
        }
        return false;
    }
    function getPhpThumb($params)
    {
    	$pgrThumbPath = 'http://' . $_SERVER['SERVER_NAME'] . substr(dirname($_SERVER['PHP_SELF']), 0, strlen(dirname($_SERVER['PHP_SELF'])) - 3) . 'PGRThumb';
        return $pgrThumbPath . '/pgrthumb.php?' . $params . '&hash=' . md5($params);
    }

	$dir = "/home/user/tools/PicturePlatform/src/pgrfilemanager/userfiles/files/battle/战斗";
	$dir = charchangerev($dir);
	//$dir = charchange($dir);
	//$dir= iconv("utf-8","gbk//IGNORE",$dir);
	$file = array();
	$res = scandir($dir);
	if($res == false) echo "No";
	foreach (scandir($dir) as $elem) {
	     if (($elem === '.') || ($elem === '..')) continue;
	   
	     $filepath = $dir . '/' . $elem;
	    if (is_file($filepath)) {              
	        $file[] = $elem;
	    }else $files[] = "fuckyou";
	}
	foreach ($file as $filename) {
		# code...
		echo mb_convert_encoding($filename,"utf-8","gbk").' ';
	}
	echo '<br>';
	foreach ($file as $filename) {
		# code...
		echo $filename.' ';
	}
	echo "<br>";
	// echo $dir;
	// echo charchange($dir,"utf-8","gbk");
	// $filepath = '../pgrfilemanager/userfiles';
	// $filename = '跳转按钮.png';
	// $file = charchangerev($filepath . '/' . $filename);
	// //$fp=fopen($filename,"r");
	// if(!file_exists($file)){
	// 	echo "Not Exist";
	// }
	// $filesize = filesize($file);
	// //echo $filesize;
	// //$file = PGRFileManagerUtils::charchangerev($_GET['filename']);
	// header("Pragma: public"); // required
	// header("Expires: 0");
	// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	// header("Cache-Control: private",false); // required for certain browsers 
	// header("Content-Type: application/octet-stream");
	// header("Accept-Ranges: bytes");
	// header("Accept-Length: $filesize");
	// // change, added quotes to allow spaces in filenames
	// header("Content-Disposition: attachment; filename=\"".$filename."\";" );
	// header("Content-Transfer-Encoding: binary");
	// header("Content-Length: $filesize");
	// readfile($file);

	// $buffer=1024;

	// while(!feof($fp)){
	//   $file_data=fread($fp,$buffer);
	  
	//   echo $file_data;
	// }

	// fclose($fp);
	$dir = 'files/battle/战斗';
	$rootPath = '/home/user/tools/PicturePlatform/src/pgrfilemanager/userfiles';
    $rootDir = '192.168.1.2/tools/PicturePlatform/src/pgrfilemanager/userfiles';
    $directory = charchangerev($rootPath . '/' . $dir);
    $urlencodedir = 'file/battle/%D5%BD%B6%B7';
    $urldecodedir = urldecode($urlencodedir);
    echo charchangerev("战斗").'<br>';
    echo $directory.'<br>';
    //echo $rootDir . '/' . $dir.'<br>';
    $allowedExtensions = 'jpg|gif|jpeg|png|bmp';
	//check if dir exist
	if (!is_dir($directory)) die();
	echo '<br>'.$directory.'<br>';

	//check if dir is in rootdir
	if(strpos($directory, realpath($rootPath)) !== 0) die();


	$files = array();
	//$directory = charchangerev($directory);
	echo $directory.'<br>';

	foreach (scandir($directory) as $elem) {
	    if (($elem === '.') || ($elem === '..')) continue;

	    if ($allowedExtensions != "") {
	        if(preg_match('/^.*\.(' . $allowedExtensions . ')$/', strtolower($elem)) === 0) {
	            continue;            
	        }
	    } 
	    //$elem = charchangerev($elem);
	    echo $elem.'<br>';
	    $filepath = $directory . '/' . $elem;
	    echo $filepath.'<br>';
	    if (is_file($filepath)) {             
	        $file = array();
	        $file['filename'] = charchange($elem);
	        $file['shortname'] = (strlen($elem) > 24) ? charchange(substr($elem, 0, 24)) . '...' : charchange($elem);
	        $file['size'] = formatBytes(filesize($filepath));
	        $file['md5'] = md5(filemtime($filepath));
			$file['ckEdit'] = false;
	        $file['date'] = date('Y-m-d H:i:s', filemtime($filepath));
	        $file['imageInfo'] = getImageInfo($filepath);
	        if ($file['imageInfo'] != false) {
	            $file['thumb'] = getPhpThumb("src=" . urlencode($rootPath . '/' . $dir . '/' .charchangerev($elem)) . "&w=64&h=64&md5=" . $file['md5']);
	        } else $file['thumb'] = false;
	        $files[] = $file; 
	    } 
	}
	echo "NO";
	foreach ($files as $elem) {
		echo $elem['filename'] . ' ' .$elem['thumb'];
		echo '<br>';
	}


?>