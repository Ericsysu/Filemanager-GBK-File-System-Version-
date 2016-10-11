<?php
include_once dirname(__FILE__) . '/config.php';
include_once dirname(__FILE__) . '/../myconfig.php';
PGRFileManagerConfig::$rootDir = PGRFileManagerConfig::$rootPath;

//Lang
if (isset($_GET['langCode'])) {
    $PGRLang = $_GET['langCode'];
} else {
    $PGRLang = 'zh_CN';
}

include_once dirname(__FILE__) . '/auth.php';

$PGRUploaderDescription = 'all files';
//For fckeditor
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    if ($type === 'Image') {
        PGRFileManagerConfig::$allowedExtensions = PGRFileManagerConfig::$imagesExtensions;
        $PGRUploaderDescription = 'images';
    } else if ($type === 'Flash') {
        PGRFileManagerConfig::$allowedExtensions = 'swf|flv';   
        $PGRUploaderDescription = 'flash';
    } else {
        $PGRUploaderDescription = 'all files';        
    }
    
    $PGRUploaderType = $_GET['type']; 
} else {
    $PGRUploaderType = 'all files';
}

//for ckeditor
if (isset($_GET['CKEditorFuncNum'])) {
    $ckEditorFuncNum = $_GET['CKEditorFuncNum'];
} else {
    $ckEditorFuncNum = '1';
}

//for PHP <= 5.2.0 json
//include_once dirname(__FILE__) . '/json.php';