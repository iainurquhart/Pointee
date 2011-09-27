<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 'Plates Fieldtype for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Fieldtypes
 * @category	Fieldtypes
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @copyright 	Copyright (c) 2011 Iain Urquhart
 * @license   	Commercial, All Rights Reserved: http://devot-ee.com/add-ons/license/plates/
*/


class Pointee_ft extends EE_Fieldtype {
	
	var $info = array(
		'name'		=> 'Pointee',
		'version'	=> '2.0'
	);

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

		if(is_array($data)) $data = implode('|', $data);

		$data = ($data != '') ? explode('|', $data) : array();
		$vars = array();
		$vars['fixed_img_url'] 	= ( isset($this->settings['fixed_img_url']) ) ? $this->settings['fixed_img_url'] : '';
		$vars['color'] 		= ( isset($this->settings['color']) ) ? $this->settings['color'] : 'black';
		$vars['field_id'] 	= $this->field_id;
		$vars['field_name'] = $this->field_name;
		$vars['image'] 		= ( isset($data[0]) ) ? str_replace('img:', '', $data[0]) : '';
		$vars['xc'] 		= ( isset($data[1]) ) ? str_replace('x:', '', $data[1]) : 0;
		$vars['yc'] 		= ( isset($data[2]) ) ? str_replace('y:', '', $data[2]) : 0;
		$vars['offset_x'] 	= 9;
		$vars['offset_y'] 	= 27;

		if($vars['image'] != '')
		{
			$this->EE->load->library('typography');
			$this->EE->typography->parse_images = TRUE;
			$vars['image'] = $this->EE->typography->parse_file_paths($vars['image']);
		}
		
		return $this->EE->load->view('field', $vars, TRUE);
	}
	
	// --------------------------------------------------------------------
	
	// we're not saving anything
	function save($data)
	{
		$image 	= (isset($data['img'])) ? $data['img'] : '';
		$xc 	= (isset($data['xc'])) ? $data['xc'] : 0;
		$yc 	= (isset($data['yc'])) ? $data['yc'] : 0;
		
		return 'img:'.$image.'|x:'.$xc.'|y:'.$yc;
	}
	
	// --------------------------------------------------------------------
		
	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field contents
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
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_img($data, $params = array(), $tagdata = FALSE)
	{
		
		list($img, $xc, $yc) = explode('|', $data);
		
		$img =  str_replace('img:', '', $img);
		$img = ($img != '[fixed]') ? $img : $this->settings['fixed_img_url'];
		
		if($img != '')
		{
			$this->EE->load->library('typography');
			$this->EE->typography->parse_images = TRUE;
			return $this->EE->typography->parse_file_paths($img);
		}
		
	}
	
	
	// --------------------------------------------------------------------


	/**
	 * Replace :x
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_x($data, $params = array(), $tagdata = FALSE)
	{

		$offset = (isset($params['offset'])) ? (int) $params['offset'] : 0;
	
		list($image_url, $xc, $yc) = explode('|', $data);
		$xc =  str_replace('x:', '', $xc);
		return ($xc + $offset);
		
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
		$offset = (isset($params['offset'])) ? (int) $params['offset'] : 0;
		list($image_url, $xc, $yc) = explode('|', $data);
		$yc =  str_replace('y:', '', $yc);
		return ($yc + $offset);
		
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
		$this->EE->lang->loadfile('pointee');
		$vars = array();
		$data['fixed_img_url'] 	= ( isset($data['fixed_img_url']) ) ? $data['fixed_img_url'] : '';
		$data['color'] 		= ( isset($data['color']) ) ? $data['color'] : '';
		
		$colors = array('black' 	=> lang('black'), 
						 'blue'  	=> lang('blue'), 
						 'pink'  	=> lang('pink'), 
						 'yellow' 	=> lang('yellow') );
		
		$this->EE->table->add_row(
			lang('image_select_instructions').':',
			form_input("pointee_options[fixed_img_url]", $data['fixed_img_url'])
		);
		$this->EE->table->add_row(
			lang('marker_color').':',
			form_dropdown("pointee_options[color]", $colors, $data['color'])
		);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Save Settings
	 *
	 * @access	public
	 * @return	field settings
	 *
	 */
	function save_settings($data)
	{
		$options = $this->EE->input->post('pointee_options');
				
		return array(
			'fixed_img_url' => $options['fixed_img_url'],
			'color' => $options['color']
		);
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
/* Location: ./system/expressionengine/third_party/plates/ft.plates.php */