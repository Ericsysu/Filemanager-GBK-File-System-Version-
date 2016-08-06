<?php
if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
}

include_once dirname(__FILE__) . '/init.php';
include_once dirname(__FILE__) . '/utils.php';

//check if upload is allowed
if (!PGRFileManagerConfig::$allowEdit) die("Not allowed!");

//get dir from GET
if (isset($_POST['dir'])) {
    $directory = PGRFileManagerUtils::charchangerev(realpath(PGRFileManagerConfig::$rootDir) . urldecode($_POST['dir']));
} else {
    $directory = PGRFileManagerUtils::charchangeressv(realpath(PGRFileManagerConfig::$rootDir));
}   

//check if dir exist
if (!is_dir($directory)) die();

//check if dir is in rootdir
if (strpos($directory, realpath(PGRFileManagerConfig::$rootDir)) === false) die();

if (!empty($_FILES)) {
    $tempFile = PGRFileManagerUtils::charchangerev($_FILES['Filedata']['tmp_name']);
    $targetFile =  PGRFileManagerUtils::charchangerev($directory . '/' . PGRFileManagerUtils::charchangerev($_FILES['Filedata']['name']));
    //$targetFile = PGRFileManagerUtils::charchangerev($targetFile);
            
    
    // Validate the file size (Warning: the largest files supported by this code is 2GB)
    $file_size = filesize($tempFile);
    if (!$file_size || $file_size > PGRFileManagerConfig::$fileMaxSize) exit(0);
        
    //check file ext
    if (PGRFileManagerConfig::$allowedExtensions != "") {
        if(preg_match('/^.*\.(' . PGRFileManagerConfig::$allowedExtensions . ')$/', strtolower($_FILES['Filedata']['name'])) === 0) {
            exit(0);            
        }
    }         
    
    move_uploaded_file($tempFile,$targetFile);
    
    //if image check size, and rescale if necessary    
    try{
        if (preg_match('/^.*\.(jpg|gif|jpeg|png|bmp)$/', strtolower($_FILES['Filedata']['name'])) > 0) {
            $targetFile = PGRFileManagerUtils::charchangerev($targetFile);
            $imageInfo = PGRFileManagerUtils::getImageInfo($targetFile);
            if (($imageInfo !== false) && 
               (($imageInfo['height'] > PGRFileManagerConfig::$imageMaxHeight) || 
                ($imageInfo['width'] > PGRFileManagerConfig::$imageMaxWidth))) {                
                    require_once(realpath(dirname(__FILE__) . '/../PGRThumb/php/Image.php'));
                    $image = PGRThumb_Image::factory($targetFile);
                    $image->maxSize(PGRFileManagerConfig::$imageMaxWidth, PGRFileManagerConfig::$imageMaxHeight);
                    $image->saveImage($targetFile, 80);
            }
        }
    } catch(Exception $e) {
        //todo    
    }    
}

exit(0);