<?php
function curPageURL() {
        $pageURL = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))?'https':'http'; 
        $pageURL .= '://' . $_SERVER['SERVER_NAME'];
        if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != '80')) {
            $pageURL .= ':' . $_SERVER['SERVER_PORT'];
        } 
        $pageURL .= $_SERVER['REQUEST_URI'];
        return $pageURL;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>FileManager</title>
  </head>
  <body>
  	<h1>YOUR ROOTPATH IS:</h1>
  	<?php echo dirname(__FILE__)?>
  	<h1>YOUR URLPATH IS:</h1>
  	<?php echo dirname(curPageURL())?>
  </body>
</html>   
