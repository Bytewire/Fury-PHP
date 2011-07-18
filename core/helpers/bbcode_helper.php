<?php

if ( ! defined('ROOT')) exit('No direct script access allowed');
	

/**
 * FURY bbCode Helpers
 *
 * @package        FURY
 * @subpackage    Helpers
 * @category    Helpers
 * @author        Santoni Jean-AndrŽ
 */

// ------------------------------------------------------------------------

/**
 * JS Insert bbCode
 *
 * Generates the javascrip function needed to insert bbcodes into a form field
 *
 * @access    public
 * @param    string    form name
 * @param    string    field name
 * @return    string
 */    

function js_insert_bbcode($form_name = '', $form_field = '')
{
    ?>
    <script type="text/javascript">
    function insert_bbcode(bbopen, bbclose)
    {
        
        var input = window.document.<?=$form_name.'.'.$form_field; ?>;
        input.focus();
        
        
        /* for Internet Explorer )*/
        if(typeof document.selection != 'undefined')
        {
            var range = document.selection.createRange();
            var insText = range.text;
            range.text = bbopen + insText + bbclose;
            range = document.selection.createRange();
            if (insText.length == 0)
            {
                range.move('character', -bbclose.length);
            }
            else
            {
                range.moveStart('character', bbopen.length + insText.length + bbclose.length);
            }
            range.select();
        }
        
        /* for newer browsers like Firefox */

        else if(typeof input.selectionStart != 'undefined'){
        
            var start = input.selectionStart;
            var end = input.selectionEnd;
            var insText = input.value.substring(start, end);
            input.value = input.value.substr(0, start) + bbopen + insText + bbclose + input.value.substr(end);
            var pos;
            if (insText.length == 0)
            {
                pos = start + bbopen.length;
            }
            else
            {
                pos = start + bbopen.length + insText.length + bbclose.length;
            }
            input.selectionStart = pos;
            input.selectionEnd = pos;
        
        }    

        /* for other browsers like Netscape... */
        else{
        
            var pos;
            var re = new RegExp('^[0-9]{0,3}$');
            while(!re.test(pos))
            {
                pos = prompt("insertion (0.." + input.value.length + "):", "0");
            }
            if(pos > input.value.length)
            {
                pos = input.value.length;
            }
            var insText = prompt("Please tape your text");
            input.value = input.value.substr(0, pos) + bbopen + insText + bbclose + input.value.substr(pos);
            
        }
    } 
    </script> 
    <?php 
} 

// ------------------------------------------------------------------------

/**
 * Parse bbCode
 *
 * Takes a string as input and replace bbCode by HTML tags
 *
 * @access   public
 * @param    string    the text to be parsed
 * @return   string
 */    
function parse_bbcode($str, $clear = 0, $bbcode_to_parse = NULL)
{
    if ( ! is_array($bbcode_to_parse))
    {
        if (FALSE === ($bbcode_to_parse = _get_bbcode_to_parse_array()))
        {
            return FALSE;
        }        
    }
    
    // fetch our replacments
   
   $bbcode_replacements = _get_bbcode_replacements();
   
   if($bbcode_to_parse!='' && $bbcode_replacements!=''):
		
		/* Pass url */
		$str = preg_replace_callback("/\[url\](.*?)\[\/url\]/is","_pass_url",$str);	
		/* pass image */
		$str = preg_replace_callback("/\[img\](.*?)\[\/img\]/is","_pass_image",$str);
		/* pass youtube */
		$str = preg_replace_callback("/\[youtube\](.*?)\[\/youtube\]/is","_pass_youtube",$str);
		$str = preg_replace ($bbcode_to_parse, $bbcode_replacements, $str);  
   
   endif;
    
/*
    foreach ($bbcode_to_parse as $key => $val)
    {        
        for ($i = 1; $i <= $bbcode_to_parse[$key][2]; $i++) // loop for imbricated tags
        {	             
            $str = preg_replace($key, $bbcode_to_parse[$key][$clear], $str);
        }
    }
*/
    
    return $str;
}

// ------------------------------------------------------------------------

/**
 * Clear bbCode
 *
 * Takes a string as input and remove bbCode tags
 *
 * @access   public
 * @param    string    the text to be parsed
 * @return   string
 */    
function clear_bbcode($str)
{
    return parse_bbcode($str, 1);
}

// ------------------------------------------------------------------------

/**
 * Get bbCode Buttons
 *
 * Returns an array of bbcode buttons that can be clicked to be inserted 
 * into a form field.  
 *
 * @access    public
 * @return    array
 */    

function get_bbcode_buttons($bbcode = NULL)
{
    if ( ! is_array($bbcode))
    {
        if (FALSE === ($bbcode = _get_bbcode_array()))
        {
            return $str;
        }        
    }

    foreach ($bbcode as $key => $val)
    {
        $button[] = '<input type="button" class="button" id="'.$key.'" name="'.$key.'" value="'.$key.'" onClick="'.$val.'" />';
    }
    
    return $button;
}

// ------------------------------------------------------------------------

/**
 * Get bbCode Array
 *
 * Fetches the config/bbcode.php file
 *
 * @access    private
 * @return    mixed
 */    
function _get_bbcode_array()
{
    if ( ! file_exists(APP_PATH.'config/bbcode'.EXT))
    {
        return FALSE;
    }

    include(APP_PATH.'config/bbcode'.EXT);

    if ( ! isset($bbcode) OR ! is_array($bbcode))
    {
        return FALSE;
    }
    
    return $bbcode;
}

// ------------------------------------------------------------------------

/**
 * Get bbCode Array for parsing
 *
 * Fetches the config/bbcode.php file
 *
 * @access    private
 * @return    mixed
 */    
function _get_bbcode_to_parse_array()
{
    if ( ! file_exists(APP_PATH.'config/bbcode'.EXT))
    {
        return FALSE;
    }

    include(APP_PATH.'config/bbcode'.EXT);
    
    if ( ! isset($bbcode_to_parse) OR ! is_array($bbcode_to_parse))
    {
        return FALSE;
    }
    
    return $bbcode_to_parse;
}


// ------------------------------------------------------------------------

/**
 * Get bbCode Array for parsing
 *
 * Fetches the config/bbcode.php file
 *
 * @access    private
 * @return    mixed
 */    
function _get_bbcode_replacements()
{
    if ( ! file_exists(APP_PATH.'config/bbcode'.EXT))
    {
        return FALSE;
    }

    include(APP_PATH.'config/bbcode'.EXT);
    
    if ( ! isset($bbcode_replacements) OR ! is_array($bbcode_replacements))
    {
        return FALSE;
    }
    
    return $bbcode_replacements;
}

// ------------------------------------------------------------------------

/**
 * Pass url
*/

function _pass_url($matches){
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
    $regex .= "(\:[0-9]{2,5})?"; // Port 
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 
    
    if(preg_match("/^$regex$/", $matches[1])):
    
    	return '<a href="'.$matches[1].'" title="'.$matches[1].'">'.$matches[1].'</a>';
    
    else:
    	
    	return $matches[1];
    	
    endif;
    
}

// ------------------------------------------------------------------------

/**
 * Pass images
*/

// ------------------------------------------------------------------------


function _pass_image($matches){

	/* is the url a valid url*/
	
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
    $regex .= "(\:[0-9]{2,5})?"; // Port 
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 
    
    if(preg_match("/^$regex$/", $matches[1])):
    
    	$image = getimagesize($matches[1]);
    	
    	if($image):
    		
    		$width = $image[0];
    		$height = $image[1];
    		
    		if($width>600)
    			$width = 600;
    		
    		if($height>600)
    			$height = 600;
    		
    		
    		return '<img src="'.$matches[1].'" height="'.$height.'" width="'.$width.'">';
    		
    	else:
    		return $matches[1];
    	endif;
    else:
    
    	return $matches[1];
    	
    endif;	
	

}

/**
 * Pass youtube
*/

function _pass_youtube($matches){

	/* check if url responds ok. */
	
/* 	http://gdata.youtube.com/feeds/api/videos/videoID */
return '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/'.$matches[1].'"></param><embed src="http://www.youtube.com/v/'.$matches[1].'" type="application/x-shockwave-flash" width="425" height="350"></embed></object>';

}


?> 