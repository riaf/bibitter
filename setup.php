<?php
/*
 * copy this file onto the application folder. 
 */
ini_set("display_errors","On");
ini_set("display_startup_errors","On");

if(@include_once("./Rhaco.php")){
	print("Please copy onto the project folder and execute it");
	exit;
}

$rhacopath = "";
$path = str_replace("\\","/",getcwd());

if(file_exists("./__settings__.php")) include_once("./__settings__.php");

if(defined("RHACO_DIR")){
	$rhacopath = constant("RHACO_DIR");
}else if(is_file($path."/library/rhaco/Rhaco.php")){
	$rhacopath = $path."/library/rhaco/";
}else if(isset($_POST["rhacopath"])){
	$rhacopath = $_POST["rhacopath"];
}else if(isset($_SERVER["argc"]) && $_SERVER["argc"] == 2){
	$rhacopath = $_SERVER["argv"][1];
}
if(!empty($rhacopath)){
	$rhacopath = str_replace("\\","/",$rhacopath);
	if(substr($rhacopath,-1) != "/") $rhacopath .= "/";
}
if(@include_once($rhacopath."Rhaco.php")){
	Rhaco::import("setup.SetupGenerator");
	Rhaco::constant("CONTEXT_PATH",Rhaco::filepath(dirname(__FILE__)));
	$setupGenerator	= new SetupGenerator($rhacopath);
	exit;
}else if(empty($_SERVER["HTTP_USER_AGENT"])){
	print("usage: php setup.php [directory path of 'Rhaco.php']\n");
	exit;
}

function search_rhacopath(){
	$path = str_replace("\\","/",getcwd());
	$rhacopath = "";
	$pathList = explode("/",$path);
	for($i=0;$i<sizeof($pathList);$i++){
		if(sizeof($pathList) == ($i + 1)){
			$rhacopath .= "rhaco/";
		}else{
			$rhacopath .= $pathList[$i]."/";
		}
	}
	return $rhacopath;
}
?>
<?php if(!empty($_SERVER["HTTP_USER_AGENT"])): ?>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>setup</title>
		<style type="text/css">
			body{ background: #ffffff; color: #000000; margin: 10px; padding: 0; font: 13px; }
			input{ margin: 2px; vertical-align: middle; }
			input[type=submit]{ background: #eeeeee; color: #222222; border: 1px outset #cccccc; }
			input:focus{ background-color:#ffffcc; }
		</style>
	</head>
	<body>
		<form method="post" name="frm">
			<div>
				directory path of 'Rhaco.php'
			</div>
			<div>
				<input type="text" size="50" name="rhacopath" value="<?php print(search_rhacopath()); ?>" />
				<input type="submit" value="set" />
			</div>
		</form>
	</body>
	</html>
<?php endif; ?>