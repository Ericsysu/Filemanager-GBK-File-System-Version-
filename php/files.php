<?php

include_once dirname(__FILE__) . '/init.php';
include_once dirname(__FILE__) . '/utils.php';

require_once(realpath(dirname(__FILE__) . '/../PGRThumb/myconfig.php'));
PGRFileManagerConfig::$pgrThumbPath = 'http://' . $_SERVER['SERVER_NAME'] . substr(dirname($_SERVER['PHP_SELF']), 0, strlen(dirname($_SERVER['PHP_SELF'])) - 3) . 'PGRThumb';

$lastdeletefiles_str = array();
$test = "No";


//get dir from post
if (isset($_POST['dir'])) {
    $directory =  PGRFileManagerUtils::charchangerev(realpath(PGRFileManagerConfig::$rootDir) . urldecode($_POST['dir']));
} else {
    $directory = PGRFileManagerUtils::charchangerev(realpath(PGRFileManagerConfig::$rootDir));    
}

$lastdeletefiles_str = "";
$test = PGRFileManagerUtils::charchange(urldecode($_POST['dir']));
$path = $directory;

//check if dir exist
if (!is_dir($directory)) $test = "No";

//check if dir is in rootdir
if(strpos($directory, realpath(PGRFileManagerConfig::$rootDir)) !== 0) die();

//check for extra function to do
if (isset($_POST['fun']) && PGRFileManagerConfig::$allowEdit) {
    $fun = $_POST['fun'];
    unset($lastdeletefiles);
    
    if (($fun === 'deleteFiles') && (isset($_POST['files']))) {
        $files = str_replace("\\", "", $_POST['files']);
        $files = json_decode($files, true);
        $backupPath = realpath(PGRFileManagerConfig::$backupPath);
        $lastdeletefilepath = realpath(PGRFileManagerConfig::$lastdeletefilesave) . '/' . "lastdeletefiles.txt";
        
        foreach ($files as $filename) {
            $filename = PGRFileManagerUtils::charchangerev($filename);
            $file = PGRFileManagerUtils::charchangerev($directory . '/' . $filename);
            //check if file is in dir
            //if(dirname($file) !== $directory) continue;
            $test = $file;
            if(file_exists($file)) {
                copy($file, $backupPath. '/' . $filename .'.'.$_COOKIE['username'].date("YmdHis",time()));
                $lastdeletefiles_str .= $filename .'.'.$_COOKIE['username'].date("YmdHis",time()).',';
                if(unlink($file)) $test = "Success";
            }
        }
        $myfile = fopen($lastdeletefilepath, "w") or die("Unable to open file!");
        fwrite($myfile, $lastdeletefiles_str);
        fclose($myfile);
    } else if(($fun === 'recoverFiles') /*&& (isset($_POST['files']))*/) {
        $recoverDirectory = urldecode($directory);
        $backupPath = realpath(PGRFileManagerConfig::$backupPath);
        $lastdeletefilepath = realpath(PGRFileManagerConfig::$lastdeletefilesave) . '/' . "lastdeletefiles.txt";
        
        //$myfile = fopen($lastdeletefilepath, "r") or die("Unable to open file!");
        $filestr = file_get_contents($lastdeletefilepath);
        //fclose($myfile);
        $files = explode(',',$filestr);

        $test = $files[0];

        foreach ($files as $filename) {
            $file = $backupPath . '/' . $filename;
            //$test = $file;
            $newFile = realpath($recoverDirectory) . '/' . $filename;
            if(dirname($file) !== $backupPath) continue;
            if(file_exists($file) && !file_exists($newFile)) {
                $newFile_cut = PGRFileManagerUtils::Cut_Filename($newFile);
                if($newFile_cut!==false) copy($file, $newFile_cut);
                else copy($file, $newFile);
            }
        }
        unset($lastdeletefiles);
    } else if (($fun === 'moveFiles') && (isset($_POST['toDir'])) && (isset($_POST['files']))) {
        $targetDirectory = PGRFileManagerUtils::charchangerev(PGRFileManagerConfig::$rootDir . urldecode($_POST['toDir']));
        //check if dir is in rootdir
        if(strpos($targetDirectory, realpath(PGRFileManagerConfig::$rootDir)) !== 0) die();
        if($directory === $targetDirectory) die();
        
        $files = str_replace("\\", "", $_POST['files']);
        $files = json_decode($files, true);
        
        foreach ($files as $filename) {
            //$filename = basename($filename);
            $filename = PGRFileManagerUtils::charchangerev($filename);
            $file = PGRFileManagerUtils::charchangerev($directory . '/' . $filename);
            $newFile = PGRFileManagerUtils::charchangerev($targetDirectory . '/' . $filename);
            //check if file is in dir
            if(dirname($file) !== $directory) continue;
            if(file_exists($file) && !file_exists($newFile)) {
                rename($file, $newFile);
            }
        }        
    } else if (($fun === 'copyFiles') && (isset($_POST['toDir'])) && (isset($_POST['files']))) {
        $targetDirectory = PGRFileManagerUtils::charchangerev(PGRFileManagerConfig::$rootDir . urldecode($_POST['toDir']));
        //check if dir is in rootdir
        if(strpos($targetDirectory, realpath(PGRFileManagerConfig::$rootDir)) !== 0) die();
        if($directory === $targetDirectory) die();
        
        $files = str_replace("\\", "", $_POST['files']);
        $files = json_decode($files, true);
        
        foreach ($files as $filename) {
            //$filename = basename($filename);
            $filename = PGRFileManagerUtils::charchangerev($filename);
            $test = $filename;
            $file = PGRFileManagerUtils::charchangerev($directory . '/' . $filename);
            $newFile = PGRFileManagerUtils::charchangerev($targetDirectory . '/' . $filename);
            //check if file is in dir
            if(dirname($file) !== $directory) continue;
            if(file_exists($file)) {
                copy($file, $newFile);
            }
        }
        die();
    } else if (($fun === 'renameFile') && (isset($_POST['filename'])) && (isset($_POST['newFilename']))) {
        
        $filename = PGRFileManagerUtils::charchangerev(urldecode($_POST['filename']));
        $newFilename = PGRFileManagerUtils::charchangerev(urldecode($_POST['newFilename']));
        //$directory = urldecode($directory);
        
        //allowed chars
        if(preg_match("/^[.\x{4e00}-\x{9fa5}A-Za-z0-9_ !@#$%^&()+={}\\[\\]\\',~`-]+$/u", $newFilename) === 0) die();
        
        $fileLength = strlen($newFilename);
        if($fileLength === 0) die();
        if($fileLength > 200) die();
                
        $file = PGRFileManagerUtils::charchangerev($directory . '/' . $filename);
        $test = $file;
        $newFile = PGRFileManagerUtils::charchangerev($directory . '/' . $newFilename);
        //check if file is in dir
        //if(dirname($file) !== $directory) die();
        if(file_exists($file) && !file_exists($newFile)) {
            rename($file, $newFile);
        }
    } else if (($fun === 'createThumb') && (isset($_POST['filename'])) && (isset($_POST['thumbWidth'])) && (isset($_POST['thumbHeight']))) {
        $thumbWidth = intval($_POST['thumbWidth']);
        $thumbHeight = intval($_POST['thumbHeight']);
        if (($thumbWidth >= 10) && ($thumbHeight >= 10)) {
            require_once(realpath(dirname(__FILE__) . '/../PGRThumb/php/Image.php'));
            $filename = PGRFileManagerUtils::charchangerev(urldecode($_POST['filename']));
            $file = PGRFileManagerUtils::charchangerev(realpath($directory) . '/' . $filename);
            $fileInfo = pathinfo($file);
            $image = PGRThumb_Image::factory($file);
            $image->maxSize($thumbWidth, $thumbHeight);
            $image->saveImage($fileInfo['dirname'] . '/' . $fileInfo['filename']  . $thumbWidth . 'x' . $thumbHeight . '.' . $fileInfo['extension']);
        }    
    } else if (($fun === 'rotateImage90Clockwise') && (isset($_POST['filename']))) {
        require_once(realpath(dirname(__FILE__) . '/../PGRThumb/php/Image.php'));
        $filename = PGRFileManagerUtils::charchangerev(urldecode($_POST['filename']));
        $file = $directory . '/' . $filename;
        $image = PGRThumb_Image::factory($file);
        $image->rotate(-90);
        $image->saveImage($file);        
    } else if (($fun === 'rotateImage90CounterClockwise') && (isset($_POST['filename']))) {
        require_once(realpath(dirname(__FILE__) . '/../PGRThumb/php/Image.php'));
        $filename = PGRFileManagerUtils::charchangerev(urldecode($_POST['filename']));
        $file = $directory . '/' . $filename;
        $test = $file;
        $image = PGRThumb_Image::factory($file);
        $image->rotate(90);
        $image->saveImage($file);        
    } else if (($fun === 'openFile') && (isset($_POST['path']))) {
        $path = PGRFileManagerUtils::charchangerev($_POST['path']);
    }
}

// $myfile = fopen($lastdeletefilepath, "w") or die("Unable to open file!");
// fwrite($myfile, $lastdeletefiles_str);
// fclose($myfile);

$files = array();
$directory = PGRFileManagerUtils::charchangerev($directory);
//$directory = iconv("UTF-8","gb2312",$directory);  
//group files
foreach (scandir($directory) as $elem) {
    if (($elem === '.') || ($elem === '..')) continue;
    //check file ext
    if (PGRFileManagerConfig::$allowedExtensions != "") {
        if(preg_match('/^.*\.(' . PGRFileManagerConfig::$allowedExtensions . ')$/', strtolower($elem)) === 0) {
            continue;            
        }
    } 
    //$elem = PGRFileManagerUtils::charchange($elem);
    $filepath = PGRFileManagerUtils::charchangerev($directory . '/' . $elem);
    //$test = PGRFileManagerUtils::charchange($filepath);
    if (is_file($filepath)) {              
        $file = array();
        $file['filename'] = PGRFileManagerUtils::charchange($elem);
        $file['shortname'] = (strlen($elem) > 16) ? PGRFileManagerUtils::charchange(substr($elem, 0, 16)) . '...' : PGRFileManagerUtils::charchange($elem);
        $file['size'] = PGRFileManagerUtils::formatBytes(filesize($filepath));
        $file['md5'] = md5(filemtime($filepath));
        // if (PGRFileManagerConfig::$ckEditorExtensions != "") $file['ckEdit'] = (preg_match('/^.*\.(' . PGRFileManagerConfig::$ckEditorExtensions . ')$/', strtolower($elem)) > 0);
        // else 
        $file['ckEdit'] = false;
        $file['date'] = date('Y-m-d H:i:s', filemtime($filepath));
        $file['imageInfo'] = PGRFileManagerUtils::getImageInfo($filepath);
        if ($file['imageInfo'] != false) {
            $file['thumb'] = PGRFileManagerUtils::getPhpThumb("src=" . urlencode(PGRFileManagerConfig::$rootPath . PGRFileManagerUtils::charchangerev(urldecode($_POST['dir'])) . '/' .PGRFileManagerUtils::charchangerev($elem)) . "&w=64&h=64&md5=" . $file['md5']);
        //     //$test = $file['thumb'];
        } else $file['thumb'] = false;
        $files[] = $file; 
    } 
}
//$test = urldecode($directory);

if(isset($_POST['test']) && isset($_POST['lastdeletefiles'])){
    echo PGRFileManagerUtils::ch_json_encode(array(
        'res'     => 'OK',
        'files' => $files,
        'lastdeletefiles' => $lastdeletefiles_str,
        'test' => $test
    ));
}else if(isset($_POST['test'])){
    echo PGRFileManagerUtils::ch_json_encode(array(
        'res'     => 'OK',
        'files' => $files,
        'test' => $test
    ));
}else if (isset($_POST['lastdeletefiles'])){
    echo PGRFileManagerUtils::ch_json_encode(array(
        'res'     => 'OK',
        'files' => $files,
        'lastdeletefiles' => $lastdeletefiles_str
    ));
}else if(isset($_POST['path'])){
    echo PGRFileManagerUtils::ch_json_encode(array(
        'res'     => 'OK',
        'path' => urlencode($path)
    ));
}else{
echo PGRFileManagerUtils::ch_json_encode(array(
    'res'     => 'OK',
    'files' => $files
));
}
exit(0);