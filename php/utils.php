<?php
class PGRFileManagerUtils
{
    static private $imageType = array(
        1  => 'GIF',
        2  => 'JPEG',
        3  => 'PNG',
        4  => 'SWF',
        5  => 'PSD',
        6  => 'BMP',
        7  => 'TIFF',
        8  => 'TIFF',
        9  => 'JPC',
        10 => 'JP2',
        11 => 'JPX',
        12 => 'JB2',
        13 => 'SWC',
        14 => 'IFF',
        15 => 'WBMP',
        16 => 'XBM'
    );
    
    static public function formatBytes($bytes, $precision = 2) 
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
  
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
  
        $bytes /= pow(1024, $pow);
  
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    static public function getImageInfo($filename)
    {
        //$filename = PGRFileManagerUtils::charchange($filename);
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
    
    static public function getPhpThumb($params)
    {
        return PGRFileManagerConfig::$pgrThumbPath . '/pgrthumb.php?' . $params . '&hash=' . md5($params . PGRThumb_Config::$pass);
    }
            
    static public function deleteDirectory($dir) 
    {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!self::deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
    }
    
    static public function sendError($message)
    {
        echo json_encode(array(
            'res' => 'ERROR',
            'msg' => $message
        ));
        
        die();
    }
        
    static public function curPageURL() {
        $pageURL = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))?'https':'http'; 
        $pageURL .= '://' . $_SERVER['SERVER_NAME'];
        if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != '80')) {
            $pageURL .= ':' . $_SERVER['SERVER_PORT'];
        } 
        $pageURL .= $_SERVER['REQUEST_URI'];
        return $pageURL;
    }

    static public function ch_json_encode($data) {
        function ch_urlencode($data) {
            if (is_array($data) || is_object($data)) {
                foreach ($data as $k => $v) {
                    if (is_scalar($v)) {
                        if (is_array($data)) {
                            $data[$k] = urlencode($v);
                        } elseif (is_object($data)) {
                            $data->$k =urlencode($v);
                        }
                    } elseif (is_array($data)) {
                        $data[$k] = ch_urlencode($v);
                    } elseif (is_object($data)) {
                        $data->$k = ch_urlencode($v);
                    }
                }
            }
            return $data;
        }
        $ret = ch_urlencode($data);
        $ret =json_encode($ret);
        return urldecode($ret);
    }

    static public function Cut_Filename($filename){
        $types = array('.gif','.jpg','.jpeg','.png','.bmp','.swf','.psd');
        foreach ($types as $ext) {
            $pos = stripos($filename, $ext);
            if($pos === false) continue;
            else{
                return substr($filename, 0, $pos + strlen($ext));
            }
        }
        return false;
    }

    static public function charchange($data){
      if( !empty($data) ){    
        $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;   
        if( $fileType != 'UTF-8'){   
          $data = mb_convert_encoding($data ,'utf-8' , $fileType);   
        }   
      }   
      return $data;    
    }

    static public function charchangerev($data){
      if( !empty($data) ){    
        $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;   
        if( $fileType != 'GBK'){   
          $data = mb_convert_encoding($data ,'gbk' , $fileType);   
        }   
      }   
      return $data;    
    }

    static public function unescape($str) 
    { 
        $ret = ''; 
        $len = strlen($str); 
        for ($i = 0; $i < $len; $i ++) 
        { 
            if ($str[$i] == '%' && $str[$i + 1] == 'u') 
            { 
                $val = hexdec(substr($str, $i + 2, 4)); 
                if ($val < 0x7f) 
                    $ret .= chr($val); 
                else  
                    if ($val < 0x800) 
                        $ret .= chr(0xc0 | ($val >> 6)) . 
                         chr(0x80 | ($val & 0x3f)); 
                    else 
                        $ret .= chr(0xe0 | ($val >> 12)) . 
                         chr(0x80 | (($val >> 6) & 0x3f)) . 
                         chr(0x80 | ($val & 0x3f)); 
                $i += 5; 
            } else  
                if ($str[$i] == '%') 
                { 
                    $ret .= urldecode(substr($str, $i, 3)); 
                    $i += 2; 
                } else 
                    $ret .= $str[$i]; 
        } 
        return $ret; 
    }

}