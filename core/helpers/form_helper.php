<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	
	/**
	*
	* Controls all the form helper functions 
	*/
	
	/* ======================================== */
	
	/**
	 * Form Declaration - Multipart type
	 *
	 * Creates the opening portion of a form for the current page.
	 *
	 */

	if( ! function_exists('form_open_this')){
	
		function form_open_this($action = '', $attributes = '', $hidden = array()){
		
			$FURY =& get_instance();
	
			if ($attributes == ''){
				$attributes = 'method="post"';
			}
	
			$action = ( strpos($action, '://') === FALSE) ? $FURY->core->site_url($action) : $action;
	
			$form = '<form action="'.$action.'"';
		
			$form .= _attributes_to_string($attributes, TRUE);
		
			$form .= '>';
	
			if (is_array($hidden) AND count($hidden) > 0){
				$form .= form_hidden($hidden);
			}
	
			return $form;
		}
	
	}
	
	
	/**
	 * Form Declaration - Multipart type
	 *
	 * Creates the opening portion of the form, but with "multipart/form-data".
	 *
	 */
	 
	if ( ! function_exists('form_open_multipart')){

		function form_open_multipart($action, $attributes = array(), $hidden = array()){
			if (is_string($attributes)){
				$attributes .= ' enctype="multipart/form-data"';
			}
			else{
				$attributes['enctype'] = 'multipart/form-data';
			}
	
			return form_open_this($action, $attributes, $hidden);
		}
		
	}
	
	
	// =========== 
	// ! Closes a form.   
	// =========== 
	
	if( ! function_exists('form_close')){
		function form_close(){
			return '</form>';
		}
	}
	
	// =========== 
	// ! Adds a button of your choice. 
	// =========== 
	
	if( ! function_exists('make_button')){
		function make_button($type,$value,$attributes){
			$button = '<button type="'.$type.'"';
			$button.= _attributes_to_string($attributes, TRUE);
			$button.= '>'.$value.'</button>';
			return $button;
		}
	}	
	
	
	// =========== 
	// ! Error box presets   
	// =========== 
	
	if( ! function_exists('errorbox')){
	
		function errorbox($msg,$class=false){
			$class = (!$class) ? "errorbox" : $class;
			echo '<div class="'.$class.'">'.$msg.'</div>';
		}
	
	}
	
	// =========== 
	// ! Success box presets   
	// =========== 
	
	if( ! function_exists('successbox')){
	
		function successbox($msg,$class=false){
			$class = (!$class) ? "successbox" : $class;
			echo '<div class="'.$class.'">'.$msg.'</div>';
		}
	
	}
	
	
	// =========== 
	// ! Sets a value to the value  
	// =========== 
	
	if( ! function_exists('set_value')){
	
		function set_value($value,$default = false){
			
			if(isset($_POST[$value])){
				return $_POST[$value];
			}elseif($default){
				return $default;
			}else{
				return false;
			}
		
		}
	
	}
	
	// =========== 
	// ! Set a radio   
	// =========== 
	
	if ( ! function_exists('set_radio')){
		function set_radio($field = '', $value = '', $default = FALSE){
			$OBJ =& _get_validation_object();
	
			if ($OBJ === FALSE){
				if ( ! isset($_POST[$field])){
					if (count($_POST) === 0 AND $default == TRUE){
						return ' checked="checked"';
					}
					return '';
				}
	
				$field = $_POST[$field];
				
				if (is_array($field)){
					if ( ! in_array($value, $field)){
						return '';
					}
				}
				else{
					if (($field == '' OR $value == '') OR ($field != $value)){
						return '';
					}
				}
	
				return ' checked="checked"';
			}
	
			return $OBJ->set_radio($field, $value, $default);
		}
	}	
	
	// =========== 
	// ! site url   
	// ===========
	if ( ! function_exists('site_url')){
		function site_url($attributes, $formtag = FALSE){
		}
	}	 
	
	// =========== 
	// ! Converts all attributes passed in an array to a string.   
	// =========== 
	
	if ( ! function_exists('_attributes_to_string')){
		function _attributes_to_string($attributes, $formtag = FALSE){
			if (is_string($attributes) AND strlen($attributes) > 0){
				if ($formtag == TRUE AND strpos($attributes, 'method=') === FALSE){
					$attributes .= ' method="post"';
				}
	
			return ' '.$attributes;
			}
		
			if (is_object($attributes) AND count($attributes) > 0){
				$attributes = (array)$attributes;
			}
	
			if (is_array($attributes) AND count($attributes) > 0){
			$atts = '';
	
			if ( ! isset($attributes['method']) AND $formtag === TRUE){
				$atts .= ' method="post"';
			}
	
			foreach ($attributes as $key => $val){
				$atts .= ' '.$key.'="'.$val.'"';
			}
	
			return $atts;
			}
		}
	}
	
	
	// =========== 
	// ! Create a form input governed by a php array.   
	// =========== 
	
	if ( ! function_exists('form_input')){
		function form_input($data = '', $value = '', $extra = ''){
			$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
	
			return "<input "._parse_form_attributes($data, $defaults).$extra." />";
		}
	}
	
	// =========== 
	// ! Create a select box.   
	// =========== 
		
	if ( ! function_exists('set_select')){
		function set_select($field = '', $value = '', $default = FALSE){
			$OBJ =& _get_validation_object();
	
			if ($OBJ === FALSE){
				if ( ! isset($_POST[$field])){
					if (count($_POST) === 0 AND $default == TRUE){
						return ' selected="selected"';
					}
					return '';
				}
	
				$field = $_POST[$field];
	
				if (is_array($field)){
					if ( ! in_array($value, $field)){
						return '';
					}
				}else{
					if (($field == '' OR $value == '') OR ($field != $value)){
						return '';
					}
				}
	
				return ' selected="selected"';
			}
	
			return $OBJ->set_select($field, $value, $default);
		}
	}
	
	
	// =========== 
	// ! Parse the form attributes
	// =========== 	

	if ( ! function_exists('_parse_form_attributes')){
		function _parse_form_attributes($attributes, $default){
			if (is_array($attributes)){
				foreach ($default as $key => $val){
					if (isset($attributes[$key])){
						$default[$key] = $attributes[$key];
						unset($attributes[$key]);
					}
				}
	
				if (count($attributes) > 0){
					$default = array_merge($default, $attributes);
				}
			}
	
			$att = '';
			
			foreach ($default as $key => $val){
				if ($key == 'value'){
					$val = form_prep($val, $default['name']);
				}
	
				$att .= $key . '="' . $val . '" ';
			}
	
			return $att;
		}
	}
	
	// =========== 
	// ! Prep form inputs   
	// =========== 
	
	if ( ! function_exists('form_prep')){
		function form_prep($str = '', $field_name = ''){
			static $prepped_fields = array();
			
			// if the field name is an array we do this recursively
			if (is_array($str)){
				foreach ($str as $key => $val){
					$str[$key] = form_prep($val);
				}
	
				return $str;
			}
	
			if ($str === ''){
				return '';
			}
	
			// we've already prepped a field with this name
			// @todo need to figure out a way to namespace this so
			// that we know the *exact* field and not just one with
			// the same name
			if (isset($prepped_fields[$field_name])){
				return $str;
			}
			
			$str = htmlspecialchars($str);
	
			// In case htmlspecialchars misses these.
			$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);
	
			if ($field_name != ''){
				$prepped_fields[$field_name] = $str;
			}
			
			return $str;
		}
	}	
	
	// =========== 
	// ! Get a validation object   
	// =========== 
	
	if ( ! function_exists('_get_validation_object')){
		function &_get_validation_object(){
			$FURY =& get_instance();
	
			// We set this as a variable since we're returning by reference
			$return = FALSE;
	
			if ( ! isset($FURY->load->_ci_classes) OR  ! isset($FURY->load->_ci_classes['form_validation'])){
				return $return;
			}
	
			$object = $FURY->load->_ci_classes['form_validation'];
	
			if ( ! isset($FURY->$object) OR ! is_object($FURY->$object)){
				return $return;
			}
	
			return $FURY->$object;
		}
	}	