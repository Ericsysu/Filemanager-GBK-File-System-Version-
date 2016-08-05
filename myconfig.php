<?php

//Include your own script with authentication if you wish
//i.e. include($_SERVER['DOCUMENT_ROOT'].'/_files/application/PGRFileManagerConfig.php');

//real absolute path to root directory (directory you want to use with PGRFileManager) on your server  
//i.e  PGRFileManagerConfig::$rootPath = '/home/user/htdocs/userfiles'
//you can check your absoulte path using
PGRFileManagerConfig::$rootPath = '/home/user/tools/PicturePlatform/src/pgrfilemanager/userfiles';
//url path to root directory
//this path is using to display images and will be returned to ckeditor with relative path to selected file
//i.e http://my-super-web-page/gallery
//i.e /gallery
PGRFileManagerConfig::$urlPath = '192.168.1.2/tools/PicturePlatform/src/pgrfilemanager/userfiles';
PGRFileManagerConfig::$backupPath = '/home/user/tools/PicturePlatform/src/pgrfilemanager/backup';
PGRFileManagerConfig::$lastdeletefilesave = '/home/user/tools/PicturePlatform/src/pgrfilemanager';


//    !!!How to determine rootPath and urlPath!!!
//    1. Copy mypath.php file to directory which you want to use with PGRFileManager
//    2. Run mypath.php script, i.e http://my-super-web-page/gallery/mypath.php
//    3. Insert correct values to myconfig.php
//    4. Delete mypath.php from your root directory


//Max file upload size in bytes
PGRFileManagerConfig::$fileMaxSize = 1024 * 1024 * 10;
//Allowed file extensions
//PGRFileManagerConfig::$allowedExtensions = '' means all files
PGRFileManagerConfig::$allowedExtensions = '';
//Allowed image extensions
PGRFileManagerConfig::$imagesExtensions = 'jpg|gif|jpeg|png|bmp';
//Max image file height in px
PGRFileManagerConfig::$imageMaxHeight = 724;
//Max image file width in px
PGRFileManagerConfig::$imageMaxWidth = 1280;
//Thanks to Cycle.cz
//Allow or disallow edit, delete, move, upload, rename files and folders
PGRFileManagerConfig::$allowEdit = true;		// true - false
//Autorization
PGRFileManagerConfig::$authorize = false;        // true - false
PGRFileManagerConfig::$authorizeUser = 'user';
PGRFileManagerConfig::$authorizePass = 'password';
//Path to CKEditor script
//i.e. http://mypage/ckeditor/ckeditor.js
//PGRFileManagerConfig::$ckEditorScriptPath = '/ckeditor/ckeditor.js';
//File extensions editable by CKEditor
//PGRFileManagerConfig::$ckEditorExtensions = 'html|html|txt';