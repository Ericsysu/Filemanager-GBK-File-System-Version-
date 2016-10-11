<?php
include_once dirname(__FILE__) . '/init.php';
include_once dirname(__FILE__) . '/utils.php';

$currentDir = urldecode($_POST['currentDir']);
$fulldir = '../userfiles'.$currentDir.'/';

if(sizeof($_FILES) > 0){
	$fileUploader = new FileUploader($_FILES,$fulldir);
}

class FileUploader{
	public function __construct($uploads,$uploadDir='../userfiles/'){
		$paths = explode("###",rtrim(urldecode($_POST['paths']),"###"));
		$myfile = fopen("../testfile.txt","w") or die("Unable to open file!");

		foreach($uploads as $key => $current){
			$this->uploadFile=$uploadDir.rtrim($paths[$key],"/.");
			$this->folder = substr($this->uploadFile,0,strrpos($this->uploadFile,"/"));

			if(strlen($current['name'])!=1)
				if($this->upload($current,$this->uploadFile))
					fwrite($myfile,$this->uploadFile." The file ".$paths[$key]." has been uploaded\r\n");
				else 
					fwrite($myfile,"Error\n");
		}
		fclose($myfile);
	}
	
	private function upload($current,$uploadFile){
		if(!is_dir($this->folder)){
			$this->folder = iconv("UTF-8", "GBK", $this->folder);
			mkdir($this->folder,0777,true);
		}
		$uploadFile = iconv("UTF-8", "GBK", $uploadFile);
		if(move_uploaded_file($current['tmp_name'],$uploadFile)){
			return true;
		}
		else 
			return false;
	}
}
?>