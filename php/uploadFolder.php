<?php
if(sizeof($_FILES) > 0)
	$fileUploader = new FileUploader($_FILES);

class FileUploader{
	public function __construct($uploads,$uploadDir='../userfiles/'){
		
		// Split the string containing the list of file paths into an array 
		$paths = explode("###",rtrim(urldecode($_POST['paths']),"###"));
		$myfile = fopen("../testfile.txt","w") or die("Unable to open file!");
		
		// Loop through files sent
		foreach($uploads as $key => $current)
		{
			// Stores full destination path of file on server
			$this->uploadFile=$uploadDir.rtrim($paths[$key],"/.");
			// Stores containing folder path to check if dir later
			$this->folder = substr($this->uploadFile,0,strrpos($this->uploadFile,"/"));
			
			// Check whether the current entity is an actual file or a folder (With a . for a name)
			if(strlen($current['name'])!=1)
				// Upload current file
				if($this->upload($current,$this->uploadFile))
					fwrite($myfile,$this->uploadFile." The file ".$paths[$key]." has been uploaded\r\n");
				else 
					fwrite($myfile,"Error\n");
		}
		fclose($myfile);
	}
	
	private function upload($current,$uploadFile){
		// Checks whether the current file's containing folder exists, if not, it will create it.
		//$uploadFile = $uploadFile;
		if(!is_dir($this->folder)){
			$this->folder = iconv("UTF-8", "GBK", $this->folder);
			mkdir($this->folder,0777,true);
		}
		// Moves current file to upload destination
		$uploadFile = iconv("UTF-8", "GBK", $uploadFile);
		if(move_uploaded_file($current['tmp_name'],$uploadFile)){
			//rename($uploadFile, charchangerev($uploadFile));
			return true;
		}
		else 
			return false;
	}
}
?>