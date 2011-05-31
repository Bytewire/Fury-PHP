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
		<a href="?module=themes">Themes</a>
		<a href="?module=themes&action=clonetheme&theme=base">Create new theme</a>
		<a href="?module=themes&action=uploadtheme">Upload new theme</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $themes;
	global $navigation;
	global $theme;

	$athemes = array();
	
	if ($handle = opendir(dirname(dirname(__FILE__)).'/themes')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "base" && file_exists(dirname(dirname(__FILE__)).'/themes/'.$file.'/css/cometchat.css')) {
				$athemes[] = $file;
			}
		}
		closedir($handle);
	}

	$activethemes = '';
	$no = 0;

	foreach ($athemes as $ti) {

		$title = ucwords($ti);

		++$no;

		$default = '';

		if (strtolower($theme) == strtolower($ti)) {
			$default = ' (Default)';
		}

		$clone = '';

		if ($ti != 'lite') {
			$clone = '| <a href="?module=themes&action=clonetheme&theme='.$ti.'">clone</a>';
		}
		
		$activethemes .= '<li class="ui-state-default" id="'.$no.'" d1="'.$ti.'"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;" id="'.$ti.'_title">'.stripslashes($title).$default.'</span><span style="font-size:11px;float:right;margin-top:2px;margin-right:5px;"><a href="javascript:void(0)" onclick="javascript:themes_makedefault(\''.$ti.'\')">make default</a> | <a href="javascript:void(0)" onclick="javascript:themes_edittheme(\''.$ti.'\')">edit</a> '.$clone.' | <a href="javascript:void(0)" onclick="javascript:themes_removetheme(\''.$ti.'\')">remove</a></span><div style="clear:both"></div></li>';
	}


	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Themes</h2>
		<h3>To set the theme, click on the "Make Default" option next to the theme. If you do not want the user to change the themes, disable the "Theme Changer" module in the modules tab.</h3>

		<div>
			<ul id="modules_livethemes">
				$activethemes
			</ul>
		</div>


	</div>

	<div style="clear:both"></div>



EOD;

	template();

}

function makedefault() {

	if (!empty($_POST['theme'])) {

		$themedata = '$theme = \'';

		$themedata .= $_POST['theme'];
		$themedata .= '\';';
		if ($_POST['theme'] != 'lite') {
			configeditor('THEME',$themedata);
			$_SESSION['cometchat']['error'] = 'Default theme successfully updated. Please clear your browser cache and try.';
		} else {
			$_SESSION['cometchat']['error'] = 'Sorry, you cannot set the lite theme as default.';
		}
	}

	echo "1";

}

$colors = array();

function getcolors($matches) {
	global $colors;

	if (strlen($matches[0]) == 4) {
		$matches[0] = $matches[0].substr($matches[0],1);
	}
	array_push($colors,strtolower($matches[0]));
}

function edittheme() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$csslist = array();
	
	if ($handle = opendir(dirname(dirname(__FILE__)).'/modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css'));
				}
				if (file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css'));
				}
			}
		}
		closedir($handle);
	}

	if ($handle = opendir(dirname(dirname(__FILE__)).'/plugins')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css'));
				}
				if (file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css'));
				}
			}
		}
		closedir($handle);
	}

	if (file_exists(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat_rtl.css')) {
		array_push($csslist,(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat_rtl.css'));
	}

	if (file_exists(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat.css')) {
		array_push($csslist,(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat.css'));
	}

	global $colors;

	$search = "/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/i";

	foreach ($csslist as $css) {
		$fh = fopen($css, 'r');
		$filedata = fread($fh, filesize($css));
		fclose($fh);
		$text = preg_replace_callback($search, "getcolors", $filedata);  
	}




	$file = dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat.css';
	$fh = fopen($file, 'r');
	$cometchatcss = fread($fh, filesize($file));
	fclose($fh);

	if (file_exists(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat_rtl.css')) {
		$file = dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat_rtl.css';
		$fh = fopen($file, 'r');
		$cometchatrtlcss = fread($fh, filesize($file));
		fclose($fh);
	} else {
		$cometchatrtlcss = $cometchatcss;
	}




	$inputs = '';
	$js = '';

	foreach (array_unique($colors) as $input) {
		$inputs .= '<div class="colorSelector" id="'.substr($input,1).'" oldcolor="'.$input.'" newcolor="'.$input.'" ><div style="background:'.$input.'"></div></div>';

		$input = substr($input,1);
		$js .= <<<EOD
$('#$input').ColorPicker({
	color: '#$input',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#$input div').css('backgroundColor', '#' + hex);
		$('#$input').attr('newcolor','#'+hex);
	}
});

EOD;
	}


	$body = <<<EOD

	<script>
		$(document).ready(function() { $js });
	</script>
	$navigation
	<form action="?module=themes&action=editthemeprocess&data={$_GET['data']}" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Edit theme</h2>
		<h3>You can edit both the stylesheets for the theme. The cometchat_rtl.css file is used when the "Right to Left" parameter is set in languages.</h3>

		<div>
			<div id="centernav">

				$inputs

				<div style="clear:both;padding:7.5px;"></div>
				<input type="button" value="Update colors" class="button" onclick="javascript:themes_updatecolors('{$_GET['data']}')">&nbsp;&nbsp;or <a href="?module=themes">cancel</a>
				<div style="clear:both;padding:7.5px;"></div>

				<div class="titlefull" style="width:480px">cometchat.css</div>
				<div style="clear:both;padding:5px;"></div>
				<div style="clear:both;padding:5px;"><textarea name="css" rows=20 style="width:480px">$cometchatcss</textarea></div>
				<div class="titlefull" style="width:480px">cometchat_rtl.css</div>
				<div style="clear:both;padding:5px;"></div>
				<div style="clear:both;padding:5px;"><textarea name="cssrtl" rows=20 style="width:480px">$cometchatrtlcss</textarea></div>
			</div>
			<div id="rightnav">
				<h1>Tips</h1>
				<ul id="modules_availablethemes">
					<li>If your theme does not appear properly after modifications, simply delete the cometchat.css and cometchat_rtl.css file and replace it with the original files.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update CSS" class="button">&nbsp;&nbsp;or <a href="?module=themes">cancel</a>
	</div>

	<div style="clear:both"></div>

EOD;

	template();
}

function updatecolorsprocess() {
	
	global $colors;
	$colors = $_POST['colors'];
	$_GET['data'] = $_POST['theme'];

	$csslist = array();
	
	if ($handle = opendir(dirname(dirname(__FILE__)).'/modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css'));
				}
				if (file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css'));
				}
			}
		}
		closedir($handle);
	}

	if ($handle = opendir(dirname(dirname(__FILE__)).'/plugins')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'.css'));
				}
				if (file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css')) {
					array_push($csslist,(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$_GET['data'].'/'.$file.'_rtl.css'));
				}
			}
		}
		closedir($handle);
	}

	if (file_exists(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat_rtl.css')) {
		array_push($csslist,(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat_rtl.css'));
	}

	if (file_exists(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat.css')) {
		array_push($csslist,(dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat.css'));
	}

	global $colors;

	$search = "/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/i";

	foreach ($csslist as $css) {
		$fh = fopen($css, 'r');
		$filedata = fread($fh, filesize($css));
		fclose($fh);
		$text = preg_replace_callback($search, "updatecolors", $filedata);  

		if (!$handle = fopen($css, 'w')) {
			 echo "Cannot open file ($file)";
			 exit;
		}

		if (fwrite($handle, $text) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
		}

		fclose($handle);

	}


}

function updatecolors($matches) {
	global $colors;

	if (strlen($matches[0]) == 4) {
		$matches[0] = $matches[0].substr($matches[0],1);
	}

	if (empty($colors[strtolower($matches[0])])) {
		$colors[strtolower($matches[0])] = strtolower($matches[0]);
	}

	return $colors[strtolower($matches[0])];
}

function editthemeprocess() {

	$file = dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat.css';

	if (is_writable($file)) {
		if (!$handle = fopen($file, 'w')) {
			 echo "Cannot open file ($file)";
			 exit;
		}

		if (fwrite($handle, $_POST['css']) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
		}

		fclose($handle);

	} else {
		echo "The file $file is not writable. Please CHMOD config.php to 777.";
		exit;
	}

	$file = dirname(dirname(__FILE__)).'/themes/'.$_GET['data'].'/css/cometchat_rtl.css';

	if (!file_exists($file)) {
		 $fh = fopen($file, 'w');
		 fclose($fh);
	}

	if (is_writable($file)) {
		if (!$handle = fopen($file, 'w')) {
			 echo "Cannot open file ($file)";
			 exit;
		}

		if (fwrite($handle, $_POST['cssrtl']) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
		}

		fclose($handle);

	} else {
		echo "The file $file is not writable. Please CHMOD config.php to 777.";
		exit;
	}

	$_SESSION['cometchat']['error'] = 'Theme updated successfully';

	header("Location:?module=themes&action=edittheme&data={$_GET['data']}");
}


function clonetheme() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=themes&action=clonethemeprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Create Theme</h2>
		<h3>Please enter the name of your new theme. Do not include special characters in your theme name.</h3>
		<div>
			<div id="centernav">
				<div class="title">Theme name:</div><div class="element"><input type="text" class="inputbox" name="theme"><input type="hidden" name="clone" value="{$_GET['theme']}"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add Theme" class="button">&nbsp;&nbsp;or <a href="?module=language">cancel</a>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function clonethemeprocess() {

	$theme = createslug($_POST['theme']);
	$clone = $_POST['clone'];

	$dirstoclone = array();
	
	if ($handle = opendir(dirname(dirname(__FILE__)).'/modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$clone.'/'.$file.'.css')) {
					array_push($dirstoclone,(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$clone));
				}
			}
		}
		closedir($handle);
	}

	if ($handle = opendir(dirname(dirname(__FILE__)).'/plugins')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$clone.'/'.$file.'.css')) {
					array_push($dirstoclone,(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$clone));
				}
			}
		}
		closedir($handle);
	}

	if (file_exists(dirname(dirname(__FILE__)).'/themes/'.$clone.'/css/cometchat.css')) {
		array_push($dirstoclone,(dirname(dirname(__FILE__)).'/themes/'.$clone));
		array_push($dirstoclone,(dirname(dirname(__FILE__)).'/themes/'.$clone.'/css'));
		array_push($dirstoclone,(dirname(dirname(__FILE__)).'/themes/'.$clone.'/images'));
	}

	foreach ($dirstoclone as $dir) {
		$newdir = str_replace($clone,$theme,$dir);
		copydirectory($dir,$newdir);
	}

	$_SESSION['cometchat']['error'] = 'New theme added successfully';
	header("Location:?module=themes");
}

function removethemeprocess() {

	$theme = $_GET['data'];

	if ($theme != 'default' && $theme != 'dark' && $theme != 'base' && $theme != 'lite') {
	
		if ($handle = opendir(dirname(dirname(__FILE__)).'/modules')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/modules/'.$file.'/code.php')) {
					if (is_dir(dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$theme)) {
						deletedirectory((dirname(dirname(__FILE__)).'/modules/'.$file.'/themes/'.$theme));
					}
				}
			}
			closedir($handle);
		}

		if ($handle = opendir(dirname(dirname(__FILE__)).'/plugins')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && file_exists(dirname(dirname(__FILE__)).'/plugins/'.$file.'/code.php')) {
					if (is_dir(dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$theme)) {
						deletedirectory((dirname(dirname(__FILE__)).'/plugins/'.$file.'/themes/'.$theme));
					}
				}
			}
			closedir($handle);
		}

		if (is_dir(dirname(dirname(__FILE__)).'/themes/'.$theme)) {
			deletedirectory((dirname(dirname(__FILE__)).'/themes/'.$theme));
		}

		$_SESSION['cometchat']['error'] = 'Theme deleted successfully';

	} else {
		$_SESSION['cometchat']['error'] = 'Sorry, this theme cannot be deleted. Please manually remove the theme from the "themes" folder.';
	}

	
	header("Location:?module=themes");
}

function uploadtheme() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=themes&action=uploadthemeprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Upload new theme</h2>
		<h3>Have you downloaded a new CometChat theme? Use our simple installation facility to add the new theme to your site.</h3>

		<div>
			<div id="centernav">
				<div class="title">Theme:</div><div class="element"><input type="file" class="inputbox" name="file"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Tips</h1>
				<ul id="modules_availablethemes">
					<li>You can download new themes from <a href="http://www.cometchat.com">our website</a>.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add theme" class="button">&nbsp;&nbsp;or <a href="?module=themes">cancel</a>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function uploadthemeprocess() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $themes;

	$extension = '';
	$error = '';

	if (!empty($_FILES["file"]["size"])) {
		if ($_FILES["file"]["error"] > 0) {
			$error = "Theme corrupted. Please try again.";
		} else {
			if (file_exists(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"])) {
				unlink(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"]);
			}

			if (!move_uploaded_file($_FILES["file"]["tmp_name"], dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"])) {
				$error = "Unable to copy to temp folder. Please CHMOD temp folder to 777.";
			}
		}
	} else {
		$error = "Theme not found. Please try again.";
	}
	
	if (!empty($error)) {
		$_SESSION['cometchat']['error'] = $error;
		header("Location: ?module=themes&action=uploadtheme");
		exit;
	}

	require_once('pclzip.lib.php');

	$filename = $_FILES['file']['name'];

	$archive = new PclZip(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"]);

	if ($archive->extract(PCLZIP_OPT_PATH, dirname(dirname(__FILE__))."/themes") == 0) {
		$error = "Unable to unzip archive. Please upload the contents of the zip file to themes folder.";
	}

	if (!empty($error)) {
		$_SESSION['cometchat']['error'] = $error;
		header("Location: ?module=themes&action=uploadtheme");
		exit;
	}

	unlink(dirname(dirname(__FILE__))."/temp/" . $_FILES["file"]["name"]);

	header("Location: ?module=themes");
	exit;
	
}

