<?php

/*

CometChat
Copyright (c) 2011 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts 
retains ownership of the Software and any copies of it, regardless of the 
form in which the copies may exist. This license is not a sale of the 
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each 
installed instance of the Software, a separate license is required. 
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts. 

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form. 

The Software source code may be altered (at your risk) 

All Software copyright notices within the scripts must remain unchanged (and visible). 

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to, 
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets, 
software piracy, and patents held by individuals, corporations, or other entities. 

If any of the terms of this Agreement are violated, Inscripts reserves the right 
to revoke the Software license at any time. 

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

if (!defined('CCADMIN')) { echo "NO DICE"; exit; }

$navigation = <<<EOD
	<div id="leftnav">
		<a href="?module=language">Languages</a>
		<a href="?module=language&action=createlanguage">Create new language</a>
		<a href="?module=language&action=uploadlanguage">Import language</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $languages;
	global $navigation;
	global $lang;
	global $rtl;

	$alanguages = array();
	
	if ($handle = opendir(dirname(dirname(__FILE__)).'/lang')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/lang/'.$file) && strtolower(extension($file)) == 'php') {
				$alanguages[] = substr($file,0,-4);
			}
		}
		closedir($handle);
	}

	$languages = '';
	$no = 0;
	$activelanguages = '';

	foreach ($alanguages as $ti) {
		if (strtolower($lang) == strtolower($ti)) {
			$languages .= '<option selected>'.$ti;	
		} else {
			$languages .= '<option>'.$ti;	
		}

		++$no;

		$activelanguages .= '<li class="ui-state-default" id="'.$no.'" d1="'.$ti.'"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;" id="'.$ti.'_title">'.$ti.'</span><span style="font-size:11px;float:right;margin-top:2px;margin-right:5px;"><a href="?module=language&action=editlanguage&data='.$ti.'">edit</a> | <a href="?module=language&action=exportlanguage&data='.$ti.'" target="_blank">export</a> | <a href="javascript:void(0)" onclick="javascript:themes_sharelanguage(\''.$ti.'\')">share</a> | <a href="javascript:void(0)" onclick="javascript:themes_removelanguage(\''.$ti.'\')">remove</a></span><div style="clear:both"></div></li>';
	}

	$rtly = "";
	$rtln = "";

	if ($rtl == 1) {
		$rtly = "checked";
	} else {
		$rtln = "checked";
	}


	$body = <<<EOD
	$navigation
	<form action="?module=language&action=updatelanguage" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Languages</h2>
		<h3>To set the language, select an option from the drop-down menu. If the language direction is right-to-left then set the parameter to yes.</h3>

		<div>
			<div id="centernav">
				<div class="title">Language:</div><div class="element"><select class="inputbox" name="lang">$languages</select></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Right to left:</div><div class="element"><input type="radio" name="rtl" value="1" $rtly>Yes <input type="radio" $rtln name="rtl" value="0" >No</div>
				<div style="clear:both;padding:5px;"></div>

				<div style="clear:both;padding:7.5px;"></div>
					<input type="submit" value="Update Language" class="button">&nbsp;&nbsp;or <a href="?module=language">cancel</a>
				
				<div style="clear:both;padding-top:20px;margin-top:20px;border-top:1px solid #ccc;">
					<ul id="modules_livelanguage">
						$activelanguages
					</ul>
				</div>	
					
				
				</div>

				
				
			</div>
			<div id="rightnav">
				<h1>Tips</h1>
				<ul id="modules_availablemodules">
					<li>To edit English language, simply create a new language (say E2) and then edit it.</li>
 				</ul>
			</div>
		</div>


	<div style="clear:both"></div>
	</form>
EOD;

	template();

}

function updatelanguage() {

	$icons = '';

	if (!empty($_POST['lang'])) {
		$data = '$lang = \''.$_POST['lang'].'\';'."\r\n".'$rtl = '.$_POST['rtl'].';';

		configeditor('LANGUAGE',$data,0);
	}

	$_SESSION['cometchat']['error'] = 'Language details updated successfully';

	header("Location:?module=language");

}

function editlanguage() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	
	$lang = $_GET['data'];

	$filestoedit = array ( "" => "", "iphone" => "iphone", "bb" => "bb" );

	if ($handle = opendir(dirname(dirname(__FILE__)).'/modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/code.php') && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/lang/en.php')) {
				$filestoedit["modules/".$file] = $file;
			}
		}
		closedir($handle);
	}

	if ($handle = opendir(dirname(dirname(__FILE__)).'/plugins')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/code.php') && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/lang/en.php')) {
				$filestoedit["plugins/".$file] = $file;
			}
		}
		closedir($handle);
	}

	$data = '';

	foreach ($filestoedit as $name => $file) {
		
		if (empty($name)) {
			$namews = $name;
		} else {
			$namews = $name.'/';
		}

		if (file_exists((dirname(dirname(__FILE__))).'/'.$namews.'lang/en.php')) {
			if ($name == '') {
				$data .= '<h4 onclick="javascript:$(\'#'.md5($name).'\').slideToggle(\'slow\')">core</h4>';
			} else {
				$data .= '<div style="clear:both"></div><h4 onclick="javascript:$(\'#'.md5($name).'\').slideToggle(\'slow\')">'.$name.'</h4>';
			}

			$data .= '<div id="'.md5($name).'" style="display:none"><form action="?module=language&action=editlanguageprocess&type='.$name.'" method="post" enctype="multipart/form-data">';

			require (dirname(dirname(__FILE__))).'/'.$namews.'lang/en.php';

			if (file_exists((dirname(dirname(__FILE__))).'/'.$namews.'lang/'.$lang.'.php')) {
				require (dirname(dirname(__FILE__))).'/'.$namews.'lang/'.$lang.'.php';
			}

			if (!empty($file)) {
				$file .= '_';
			}

			$array = $file.'language';

			$x = 0;

			foreach (${$array} as $l) {
				$x++;
				$data .= '<div style="clear:both"></div><div class="title">'.$x.':</div><div class="element"><textarea name="lang_'.$x.'" class="inputbox inputboxlong">'.(stripslashes($l)).'</textarea></div>';
			}

			if ($lang != "en") {
				$data .= '<div style="clear:both;padding:7.5px;"></div><div style="float:right;margin-right:20px;"><input type="button" value="Update language" onclick="language_updatelanguage(\''.md5($name).'\',\''.$name.'\',\''.$file.'\',\''.$lang.'\')" class="button">&nbsp;&nbsp;or <a onclick="language_restorelanguage(\''.md5($name).'\',\''.$name.'\',\''.$file.'\',\''.$lang.'\')" href="#">restore</a></div><div style="clear:both;padding:7.5px;"></div></form></div>';
			} else {
				$data .= '<div style="clear:both;padding:7.5px;"></div><div style="float:right;margin-right:20px;">This language cannot be edited. Please create a new language.</div><div style="clear:both;padding:7.5px;"></div></form></div>';
			}
		}
	}

	$body = <<<EOD
	$navigation
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Edit language - {$lang}</h2>
		<h3>Please select the section you would like to edit.</h3>
		<div>
			<div id="centernav" class="centernavextend">
				$data
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function removelanguageprocess() {
	$lang = $_GET['data'];

	if ($lang != 'en') {
		if ($handle = opendir(dirname(dirname(__FILE__)).'/modules')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/code.php') && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/lang/'.$lang.'.php')) {
					unlink(dirname(dirname(__FILE__)).'/modules/'.$file.'/lang/'.$lang.'.php');
				}
			}
			closedir($handle);
		}

		if ($handle = opendir(dirname(dirname(__FILE__)).'/plugins')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/code.php') && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/lang/'.$lang.'.php')) {
					unlink(dirname(dirname(__FILE__)).'/plugins/'.$file.'/lang/'.$lang.'.php');
				}
			}
			closedir($handle);
		}

		if (file_exists(dirname(dirname(__FILE__)).'/lang/'.$lang.'.php')) {
			unlink(dirname(dirname(__FILE__)).'/lang/'.$lang.'.php');
		}

		if (file_exists(dirname(dirname(__FILE__)).'/iphone/lang/'.$lang.'.php')) {
			unlink(dirname(dirname(__FILE__)).'/iphone/lang/'.$lang.'.php');
		}

		$_SESSION['cometchat']['error'] = 'Language deleted successfully';
	} else {
		$_SESSION['cometchat']['error'] = 'Sorry, this language cannot be deleted.';
	}	

		header("Location:?module=language");


}

function editlanguageprocess() {

	$lang = $_POST['lang'];

	$data = '<?php'."\r\n"."\r\n".'/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////'."\r\n"."\r\n".'/* LANGUAGE */'."\r\n"."\r\n";

	foreach ($_POST['language'] as $i => $l) {
		$data .= '$'.$_POST['file'].'language['.$i.'] = \''.(str_replace("'", "\'", $l)).'\';'."\r\n";
	}

	$data .= "\r\n".'/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////';
	
	if (!empty($_POST['id'])) {
		$_POST['id'] .= '/';
	}

	$file = dirname(dirname(__FILE__)).'/'.$_POST['id'].'lang/'.strtolower($lang).".php";
	$fh = fopen($file, 'w');
	if (fwrite($fh, $data) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
	}
	fclose($fh);
	chmod($file, 0777);

	echo "1";
	exit;
}

function restorelanguageprocess() {

	$lang = $_POST['lang'];

	if (!empty($_POST['id'])) {
		$_POST['id'] .= '/';
	}

	$file = dirname(dirname(__FILE__)).'/'.$_POST['id'].'lang/en.php';
	$fh = fopen($file, 'r');
	$restoredata = fread($fh, filesize($file));
	fclose($fh);

	$file = dirname(dirname(__FILE__)).'/'.$_POST['id'].'lang/'.strtolower($lang).".php";
	$fh = fopen($file, 'w');
	if (fwrite($fh, $restoredata) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
	}
	fclose($fh);
	chmod($file, 0777);

	$_SESSION['cometchat']['error'] = 'Language has been restored successfully.';

	echo "1";
	exit;
}

function createlanguage() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=language&action=createlanguageprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Create new language</h2>
		<h3>Enter the first two letters of your new language.</h3>
		<div>
			<div id="centernav">
				<div class="title">Language:</div><div class="element"><input type="text" class="inputbox" name="lang" maxlength=2></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add language" class="button">&nbsp;&nbsp;or <a href="?module=language">cancel</a>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function createlanguageprocess() {

	$file = dirname(dirname(__FILE__)).'/lang/'.strtolower($_POST['lang']).".php";
	$fh = fopen($file, 'w');
	fclose($fh);
	chmod($file, 0777);

	$_SESSION['cometchat']['error'] = 'New language added successfully';

	header("Location:?module=language");
}


function exportlanguage() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$lang = $_GET['data'];

	$data = getlanguage($lang);

	header('Content-Description: File Transfer');
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename='.$lang.'.lng');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	ob_clean();
	flush();
	echo ($data);

}

function sharelanguage() {
	global $currentversion;

	$lang = $_GET['data'];
	$data = getlanguage($lang);

	$ch = curl_init("http://www.cometchat.com/users/sharelanguage");
	curl_setopt ($ch, CURLOPT_POST, true);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, "id=".urlencode($_GET['data'])."&lang=".urlencode($_GET['lang'])."&version=".urlencode($currentversion)."&data=".urlencode($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	exit;
}

function getlanguage($lang) {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$filestoedit = array ( "" => "", "iphone" => "iphone", "bb" => "bb" );

	if ($handle = opendir(dirname(dirname(__FILE__)).'/modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/code.php') && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/lang/en.php')) {
				$filestoedit["modules/".$file] = $file;
			}
		}
		closedir($handle);
	}

	if ($handle = opendir(dirname(dirname(__FILE__)).'/plugins')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/code.php') && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/lang/en.php')) {
				$filestoedit["plugins/".$file] = $file;
			}
		}
		closedir($handle);
	}

	$data = '<?php '."\r\n".'// CometChat Language File - '.$lang."\r\n"."\r\n";

	foreach ($filestoedit as $name => $file) {
		
		if (empty($name)) {
			$namews = $name;
		} else {
			$namews = $name.'/';
		}

		if (file_exists((dirname(dirname(__FILE__))).'/'.$namews.'lang/en.php')) {

			if (file_exists((dirname(dirname(__FILE__))).'/'.$namews.'lang/'.$lang.'.php')) {

				if (!empty($file)) {
					$file .= '_';
				}

				$array = $file.'language';

				$file = (dirname(dirname(__FILE__))).'/'.$namews.'lang/'.$lang.'.php';
				$fh = fopen($file, 'r');
				$readdata = @fread($fh, filesize($file));
				fclose($fh);

				$data .= '$file["'.$name.'"]=\''.base64_encode($readdata).'\';'; 

			}


		}
	}

	$data .= ' ?>';

	return $data;
}

function uploadlanguage() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=language&action=uploadlanguageprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Upload new language</h2>
		<h3>Have you downloaded a new CometChat language? Use our simple installation facility to add the new language to your site.</h3>

		<div>
			<div id="centernav">
				<div class="title">Language:</div><div class="element"><input type="file" class="inputbox" name="file"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Tips</h1>
				<ul id="modules_availablelanguages">
					<li>You can download new languages from <a href="http://www.cometchat.com">our website</a>.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add language" class="button">&nbsp;&nbsp;or <a href="?module=language">cancel</a>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function uploadlanguageprocess() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$extension = '';
	$error = '';

	if (!empty($_FILES["file"]["size"])) {
		if ($_FILES["file"]["error"] > 0) {
			$error = "Language corrupted. Please try again.";
		} else {
			if (file_exists(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"])) {
				unlink(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"]);
			}

			if (!move_uploaded_file($_FILES["file"]["tmp_name"], dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"])) {
				$error = "Unable to copy to temp folder. Please CHMOD temp folder to 777.";
			}
		}
	} else {
		$error = "Language not found. Please try again.";
	}
	
	if (!empty($error)) {
		$_SESSION['cometchat']['error'] = $error;
		header("Location: ?module=language&action=uploadlanguage");
		exit;
	}

	require_once(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"]);

	$lang = basename(strtolower($_FILES["file"]["name"]), ".lng");

	foreach ($file as $f => $d) {
		
		if (!empty($f)) { $f .= '/'; }

		$file = dirname(dirname(__FILE__)).'/'.$f.'lang/'.strtolower($lang).".php";
		$fh = fopen($file, 'w');
		if (fwrite($fh, base64_decode($d)) === FALSE) {
				echo "Cannot write to file ($file)";
				exit;
		}
		fclose($fh);
		chmod($file, 0777);

	}

	if (!empty($error)) {
		$_SESSION['cometchat']['error'] = $error;
		header("Location: ?module=language&action=uploadlanguage");
		exit;
	}

	unlink(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"]);
	
	$_SESSION['cometchat']['error'] = 'Language added successfully';
	header("Location: ?module=language");
	exit;
	
}