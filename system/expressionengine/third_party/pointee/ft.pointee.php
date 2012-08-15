<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pointee Fieldtype for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Fieldtypes
 * @category	Fieldtypes
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @copyright 	Copyright (c) 2011 Iain Urquhart
 * @license   	Creative Commons Attribution No Derivatives -> http://creativecommons.org/licenses/by-nd/3.0/
*/


class Pointee_ft extends EE_Fieldtype {
	
	var $info = array(
		'name'		=> 'Pointee',
		'version'	=> '2.1'
	);

	 // Set by Low Variables
	var $var_id;

	// --------------------------------------------------------------------
	
	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field($data)
	{
		$this->EE->lang->loadfile('pointee');
		
		if (! isset($this->EE->session->cache['taxonomy']['css_added']) )
		{
			$this->EE->session->cache['taxonomy']['css_added'] = 1;
			$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->EE->config->item('theme_folder_url').'third_party/pointee_assets/css/pointee.css'.'" />');
		}
		
		if(is_array($data)) $data = implode('|', $data);

		$data = ($data != '') ? explode('|', $data) : array();
		$vars = array();
		$vars['fixed_img_url'] 	= ( isset($this->settings['fixed_img_url']) ) ? $this->settings['fixed_img_url'] : '';
		$vars['color'] 		= ( isset($this->settings['color']) ) ? $this->settings['color'] : 'black';
		$vars['field_id'] 	= ($this->var_id) ? $this->var_id : $this->field_id;
		$vars['field_name'] = str_replace(array('[',']'), array('_',''), $this->field_name);
		$vars['image'] 		= ( isset($data[0]) ) ? str_replace('img:', '', $data[0]) : '';
		$vars['xc'] 		= ( isset($data[1]) ) ? str_replace('x:', '', $data[1]) : 0;
		$vars['yc'] 		= ( isset($data[2]) ) ? str_replace('y:', '', $data[2]) : 0;
		$vars['offset_x'] 	= 9;
		$vars['offset_y'] 	= 27;
		$vars['display_image'] = '';
		
		if($vars['image'] != '')
		{
			$this->EE->load->library('typography');
			$this->EE->typography->parse_images = TRUE;
			$vars['display_image'] = $this->EE->typography->parse_file_paths($vars['image']);
		}
		
		return $this->EE->load->view('field', $vars, TRUE);
	}
		
	// --------------------------------------------------------------------


	/**
	 * Display Variable Field
	 * @param string $data
	 * @return string
	 */
	function display_var_field($data)
	{
		if (! $this->var_id) return;

		// since we are "within" Low Variables, we need to add our package path
		$this->EE->load->add_package_path(PATH_THIRD . 'pointee');

		$display = $this->display_field($data);

		// now remove our package path
		$this->EE->load->remove_package_path(PATH_THIRD . 'pointee');

		// also let's be sure EE's filemanager is loaded
		$this->EE->load->library(array('filemanager', 'file_field')); 
		$this->EE->file_field->browser(); 

		// here you go, @low
		return $display;
	}

	// --------------------------------------------------------------------


	/**
	 * Save
	 *
	 * @access	public
	 * @param	field data
	 * @return	pipe delimited string of values
	 *
	 */
	function save($data)
	{
		$image 	= (isset($data['img'])) ? $data['img'] : '';
		$xc 	= (isset($data['xc'])) ? $data['xc'] : 0;
		$yc 	= (isset($data['yc'])) ? $data['yc'] : 0;
		
		if($image)
		{
			return 'img:'.$image.'|x:'.$xc.'|y:'.$yc;
		}
	}
	
	// --------------------------------------------------------------------
		
	/**
	 * Save Variable Field
	 * @param array $data
	 * @return string Keywords
	 */
	function save_var_field($data)
	{
		if (! $this->var_id) return;

		return $this->save($this->EE->input->post('var_' . $this->var_id));
	}

	// --------------------------------------------------------------------

	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field contents, parameters
	 * @return	replacement text
	 *
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		return $data;
	}

	// --------------------------------------------------------------------


	/**
	 * Replace :image
	 *
	 * @access	public
	 * @param	field contents, parameters
	 * @return	replacement text
	 *
	 */
	function replace_img($data, $params = array(), $tagdata = FALSE)
	{
		
		$data = explode('|', $data);
		
		if(count($data))
		{
			$img = (isset($data[0])) ? $data[0] : '';
			$img =  str_replace('img:', '', $img);
			$img = ($img != '[fixed]') ? $img : $this->settings['fixed_img_url'];
			
			if($img != '')
			{
				$this->EE->load->library('typography');
				$this->EE->typography->parse_images = TRUE;
				return $this->EE->typography->parse_file_paths($img);
			}
		}
		
	}
	
	
	// --------------------------------------------------------------------


	/**
	 * Replace :x
	 *
	 * @access	public
	 * @param	field contents, parameters
	 * @return	replacement text
	 *
	 */
	function replace_x($data, $params = array(), $tagdata = FALSE)
	{
		$data = explode('|', $data);
		
		if(count($data))
		{
			$offset = (isset($params['offset'])) ? (int) $params['offset'] : 0;
			$xc = (isset($data[1])) ? $data[1] : '';
			$xc =  str_replace('x:', '', $xc);
			return ($xc + $offset);
		}
	}
	
	// --------------------------------------------------------------------


	/**
	 * Replace :y
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_y($data, $params = array(), $tagdata = FALSE)
	{
	
		$data = explode('|', $data);
		
		if(count($data))
		{
			$offset = (isset($params['offset'])) ? (int) $params['offset'] : 0;
			$yc = (isset($data[2])) ? $data[2] : '';
			$yc =  str_replace('y:', '', $yc);
			return ($yc + $offset);
		}
		
	}

	
	// --------------------------------------------------------------------


	/**
	 * Display Variable Tag
	 */
	function display_var_tag($data, $params = array(), $tagdata = FALSE)
	{
		if (! $this->var_id) return;

		if( ! $tagdata)
		{
			return $this->replace_tag($data, $params, $tagdata);
		}
		else
		{
			// possible tag variables to replace
			$variables = array();

			$variables[] = array(
				$params['var'] => $this->replace_tag($data, $params),
				$params['var'].':img' => $this->replace_img($data, $params),
				$params['var'].':x' => $this->replace_x($data, $params),
				$params['var'].':y' => $this->replace_y($data, $params)
			);

			return $this->EE->TMPL->parse_variables($tagdata, $variables);
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Build & return array of Settings
	 *
	 * @access	public
	 * @return	Array default global settings
	 *
	 */
	function _settings($data)
	{
		$this->EE->lang->loadfile('pointee');
		
		$vars = array();
		$data['fixed_img_url'] 	= ( isset($data['fixed_img_url']) ) ? $data['fixed_img_url'] : '';
		$data['color'] 		= ( isset($data['color']) ) ? $data['color'] : '';
		
		$colors = array('black' 	=> lang('black'), 
						 'blue'  	=> lang('blue'), 
						 'pink'  	=> lang('pink'), 
						 'yellow' 	=> lang('yellow') );

		// build array for table rows
		$rows = array();
		
		// text input for image per field
		$rows[] = array(
			lang('image_select_instructions').':',
			form_input("pointee_options[fixed_img_url]", $data['fixed_img_url'])
		);
		
		// colors
		$rows[] = array(
			lang('marker_color').':',
			form_dropdown("pointee_options[color]", $colors, $data['color'])
		);

		return $rows;
	}

	// --------------------------------------------------------------------

	/**
	 * Display Settings Screen
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function display_settings($data)
	{
		$rows = $this->_settings($data);

		foreach($rows as $row)
		{
			// text input for image per field
			$this->EE->table->add_row(
				$row[0],
				$row[1]
			);
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Display Variable Settings
	 * @param array $data
	 */
	function display_var_settings($data)
	{
		if (!defined('LOW_VAR_VERSION') || version_compare(LOW_VAR_VERSION, '2.2', '<'))
		{
			return array(
				array('', 'Pointee requires Low Variables 2.2 or later.')
			);
		}

		return $this->_settings($data);
	}

	// --------------------------------------------------------------------
		
	/**
	 * Save Settings
	 *
	 * @access	public
	 * @return	field settings
	 *
	 */
	function save_settings()
	{
		$options = $this->EE->input->post('pointee_options');
				
		return array(
			'fixed_img_url' => $options['fixed_img_url'],
			'color' => $options['color']
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Save Low Variable Settings
	 *
	 * @access	public
	 * @return	field settings
	 *
	 */
	function save_var_settings()
	{
		return $this->save_settings();
	}

	// --------------------------------------------------------------------

	/**
	 * Post-Save Field Settings
	 */
	function post_save_settings()
	{
		return $this->save_settings();
	}

	// --------------------------------------------------------------------
	
	/**
	 * Install Fieldtype
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function install()
	{
		return NULL;
	}
	

}

/* End of file ft.plates.php */
/* Location: ./system/expressionengine/third_party/pointee/ft.pointee.php */