<?php
/**
 * @package Plugin JSmallfibPro for Joomla! 1.6/1.7/2.5
 * @version version 1.3.3
 * @author Enrico Sandoli
 * @copyright (C) 2012 Enrico Sandoli. All Rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

	// ES 20100615
	// 
	// UPLOAD.PHP - this script will receive one file at a time, and will use the POST-transmitted filename
	// to communicate with jsmallfib.php, which is reloaded once the upload queue has been processed client-side

	// debug code
	if (0)
	{
		file_put_contents("swfupload.log.html", "<br />count of POST DATA = [".count($_POST)."]:<br /><br />", FILE_APPEND);

		while (list($key, $val) = each($_POST))
		{
			file_put_contents("swfupload.log.html", "KEY [$key] -> VAL [$val]<br />", FILE_APPEND);
		}

		file_put_contents("swfupload.log.html", "<br />count of FILES DATA = [".count($_FILES)."]:<br /><br />", FILE_APPEND);

		while (list($key, $aval) = each($_FILES))
       		{
			file_put_contents("swfupload.log.html", "KEY [$key]<br />", FILE_APPEND);
			while (list($key, $val) = each($aval))
		       	{
				file_put_contents("swfupload.log.html", "Filedata KEY [$key] -> VAL [$val]<br />", FILE_APPEND);
			}
		}

		exit(0);
	}

	// set variables

	$access_rights = urldecode($_POST['access_rights']);

	$upload_dir = urldecode($_POST['upload_dir']);

	$upload_dir = decrypt($upload_dir, date("Y-m-d"));

	$dir_sep = urldecode($_POST['dir_sep']);
	$default_file_chmod = urldecode($_POST['default_file_chmod']);
	$archived_string = urldecode($_POST['archived_string']);
	$encode_to_utf8 = urldecode($_POST['encode_to_utf8']);
	$upload_file_basename = stripslashes($encode_to_utf8 ? utf8_decode($_FILES['Filedata']['name']) : $_FILES['Filedata']['name']);

	$upload_file = $upload_dir.$upload_file_basename;
	$tmp_file = $_FILES['Filedata']['tmp_name'];
	$waiting_file = $tmp_file."_WAITING";

	$resolve_conflicts = $_POST['resolve_conflicts'];
	$resolve_conflicts_filename = $_POST['resolve_conflicts_filename'];

	if (!strcmp($dir_sep, "forwardslash"))
	{
		$dir_sep = '/';
	}
	else
	{
		$dir_sep = '\\';
	}

	// debug code
	if (0)
	{
		file_put_contents("swfupload.log", "ACCESS_RIGHTS = [".$access_rights."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "UPLOAD_DIR = [".$upload_dir."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "DIR_SEP = [".$dir_sep."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "ARCHIVED_STRING = [".$archived_string."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "ENCODE_TO_UTF8 = [".$encode_to_utf8."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "UPLOAD_FILE_BASENAME = [".$upload_file_basename."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "UPLOAD_FILE = [".$upload_file."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "TMP_FILE = [".$tmp_file."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "WAITING_FILE = [".$waiting_file."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "RESOLVE_CONFLICTS = [".$resolve_conflicts."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "RESOLVE_CONFLICTS_FILENAME = [".$resolve_conflicts_filename."]".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", "----------------------------------------------------------------------------------".PHP_EOL, FILE_APPEND);
		file_put_contents("swfupload.log", PHP_EOL, FILE_APPEND);

		exit(0);
	}

	// start processing file

	// ERROR codes (the first 4 come from $_FILES):
	//
	// 1: File exceeded maximum server upload size of ini_get('upload_max_filesize')
	// 2: File exceeded maximum file size
	// 3: File only partially uploaded
	// 4: No file uploaded
	// 5: Cannot override existing file (copy)
	// 6: Cannot create new archive directory
	// 7: Cannot copy existing file in archive directory
	// 8: Cannot override existing file (move)

	//file_put_contents("swfupload.log", "MOVING File [$tmp_file] to [$upload_file]".PHP_EOL, FILE_APPEND);
	//file_put_contents("swfupload.log", "MOVING File [".html_entity_decode($tmp_file)."] to [".html_entity_decode($upload_file)."]".PHP_EOL, FILE_APPEND);
	//file_put_contents("swfupload.log", "MOVING File [".html_entity_decode(utf8_decode($tmp_file))."] to [".html_entity_decode(utf8_decode($upload_file))."]".PHP_EOL, FILE_APPEND);

	if ($_FILES['Filedata']['error'] || !is_uploaded_file(html_entity_decode($tmp_file)))
	{
		file_put_contents($resolve_conflicts_filename, "ERROR;".$upload_file_basename.";".$_FILES['Filedata']['error'].PHP_EOL, FILE_APPEND);
	}
	else
	{
		if(file_exists($upload_file))
		{
			switch($resolve_conflicts)
			{
			case 0:
				// ask what to do
				file_put_contents($resolve_conflicts_filename, "ASK;".$upload_file_basename.";".$waiting_file.PHP_EOL, FILE_APPEND);

				move_uploaded_file(html_entity_decode($tmp_file), html_entity_decode($waiting_file));
				@unlink(html_entity_decode($tmp_file));
				break;
			case 1:
				// cancel upload
				file_put_contents($resolve_conflicts_filename, "CANCELED;".$upload_file_basename.";".PHP_EOL, FILE_APPEND);

				@unlink(html_entity_decode($tmp_file));
				break;
			case 2:
				// override existing file
				if(!copy(html_entity_decode($tmp_file), html_entity_decode($upload_file)))
				{
					file_put_contents($resolve_conflicts_filename, "ERROR;".$upload_file_basename.";5".PHP_EOL, FILE_APPEND);
				}
				else
				{
					file_put_contents($resolve_conflicts_filename, "OVERRIDEN;".$upload_file_basename.";".PHP_EOL, FILE_APPEND);
					@chmod(html_entity_decode($upload_file), $default_file_chmod);
					@unlink(html_entity_decode($tmp_file));
				}
				break;
			case 3:
				// archive existing file
				if (!is_dir($upload_dir."JS_ARCHIVE") && !($rc = @mkdir ($upload_dir."JS_ARCHIVE")))
				{
					file_put_contents($resolve_conflicts_filename, "ERROR;".$upload_file_basename.";6".PHP_EOL, FILE_APPEND);
					break;
				}

				if (strpos($upload_file_basename, '.') === false)
				{
					$archive_file = $upload_dir."JS_ARCHIVE".$dir_sep.$upload_file_basename." (".$archived_string." ".date("Y-m-d H.i.s").")";
				}
				else
				{
					$archive_file = fileWithoutExtension($upload_dir."JS_ARCHIVE".$dir_sep.$upload_file_basename)." (".$archived_string." ".date("Y-m-d H.i.s").").".fileExtension($upload_file_basename);
				}

				// copy current file into archive folder
				if(!copy(html_entity_decode($upload_file), html_entity_decode($archive_file)))
				{
					file_put_contents($resolve_conflicts_filename, "ERROR;".$upload_file_basename.";7".PHP_EOL, FILE_APPEND);
				}
			       	else
				{
					// copy WAITING file onto existing one (will then unlink WAITING tmp file)
					if(!copy(html_entity_decode($tmp_file), html_entity_decode($upload_file)))
					{
						file_put_contents($resolve_conflicts_filename, "ERROR;".$upload_file_basename.";5".PHP_EOL, FILE_APPEND);
					}
					else
					{
						file_put_contents($resolve_conflicts_filename, "ARCHIVED;".$upload_file_basename.";".PHP_EOL, FILE_APPEND);
						@chmod(html_entity_decode($upload_file), $default_file_chmod);
						@unlink(html_entity_decode($tmp_file));
					}
				}
			}
		}
		else if(!move_uploaded_file(html_entity_decode($tmp_file), html_entity_decode($upload_file)))
		{
			file_put_contents($resolve_conflicts_filename, "ERROR;".$upload_file_basename.";8".PHP_EOL, FILE_APPEND);
		}
		else
		{
			file_put_contents($resolve_conflicts_filename, "UPLOADED;".$upload_file_basename.";".PHP_EOL, FILE_APPEND);
		}
	}

	// to solve MAC problem -- see http://www.swfupload.org/forum/generaldiscussion/1744
	print "OK";

	//
	// Return file extension (the string after the last dot.
	// THIS IS A REPLICA OF THE FUNCTION CONTAINED IN JSMALLFIB.PHP
	//
	function fileExtension($file)
	{
		$a = explode(".", $file);
		$b = count($a);
		return $a[$b-1];
	}

	// Return file without extension (the string before the last dot.
	// THIS IS A REPLICA OF THE FUNCTION CONTAINED IN JSMALLFIB.PHP
	//
	function fileWithoutExtension($file)
	{
		$a = explode(".", $file);
		$b = count($a);
		$c = $a[0];
		for ($i = 1; $i < $b - 1; $i++)
		{
			$c .= ".".$a[$i];
		}
		return $c;
	}

	function decrypt($string, $key) { 
		
		$result = ''; 
		$string = base64_decode($string); 

		for($i = 0; $i < strlen($string); $i++) { 
		
			$char = substr($string, $i, 1); 
			$keychar = substr($key, ($i % strlen($key)) - 1, 1); 
			$char = chr(ord($char) - ord($keychar));
			
			$result .= $char; 
		}

		return $result;
	}

?>
