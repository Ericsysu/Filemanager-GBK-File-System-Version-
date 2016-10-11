<?php
include_once dirname(__FILE__) . '/init.php';

if (strlen(PGRFileManagerConfig::$ckEditorScriptPath) === 0) die();
if (strlen(PGRFileManagerConfig::$ckEditorExtensions) === 0) die();

//get dir from GET
if (isset($_POST['dir'])) {
    $directory = realpath(PGRFileManagerConfig::$rootDir . $_POST['dir']);
} else die();

//check if dir exist
if (!is_dir($directory)) die();

//check if dir is in rootdir
if (strpos($directory, realpath(PGRFileManagerConfig::$rootDir)) === false) die();

if (!isset($_POST['filename'])) die();

$filename = realpath($directory . '/' . $_POST['filename']);
//check if file is in dir
if(dirname($filename) !== $directory) die();

//check file extension
if (preg_match('/^.*\.(' . PGRFileManagerConfig::$ckEditorExtensions . ')$/', strtolower($filename)) === 0) die();

//check for extra function to do
if (isset($_POST['fun']) && PGRFileManagerConfig::$allowEdit) {
    $fun = $_POST['fun'];    
    if ($fun === 'getContent') {
        echo file_get_contents($filename);
    } else if (($fun === 'putContent') && (isset($_POST['content']))) {
        if (get_magic_quotes_gpc()) {
            $_POST['content'] = stripslashes($_POST['content']);
        }
        file_put_contents($filename, $_POST['content']);
    }
}
die();