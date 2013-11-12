<?php

if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require_once PATH_THIRD . 'mx_cp_notice/config.php';

/**
 *  MX MSM Themes Class for ExpressionEngine2
 *
 * @package  ExpressionEngine
 * @subpackage Plugins
 * @category Plugins
 * @author    Max Lazar <max@eec.ms>
 * @Commercial - please see LICENSE file included with this distribution
 */

/* !TODO


*/

class Mx_cp_notice_ext {

	var $settings        = array();
	var $name   = MX_CP_NOTICE_NAME;
	var $version  = MX_CP_NOTICE_VER;
	var $description = MX_CP_NOTICE_DESC;
	var $settings_exist = 'y';
	var $docs_url  = MX_CP_NOTICE_DOCS;

	var $default = array (
					'theme'=> 'air',
					'location_h' => 'right',
					'location_v' => 'bottom',
					'showCloseButton' => 'true',
					'hideAfter' => '10'
		);



	/**
	 * Defines the ExpressionEngine hooks that this extension will intercept.
	 *
	 * @since Version 1.0.1
	 * @access private
	 * @var mixed an array of strings that name defined hooks
	 * @see http://codeigniter.com/user_guide/general/hooks.html
	 * */

	private $hooks = array( 'cp_js_end' => 'cp_js_end', 'cp_css_end' => 'cp_css_end' );

	// -------------------------------
	// Constructor
	// -------------------------------

	public function __construct( $settings=FALSE ) {

		$this->EE =& get_instance();

		if
		( isset( $this->EE->mx_core ) === FALSE ) {
			$this->EE->load->add_package_path(PATH_THIRD . 'mx_cp_notice/');
			$this->EE->load->library( 'mx_core' );
		}


		$this->EE->mx_core->set_options( array( 'class' => __CLASS__, 'version' => MX_CP_NOTICE_VER ) );

		// define a constant for the current site_id rather than calling $PREFS->ini() all the time
		if
		( defined( 'SITE_ID' ) == FALSE )
			define( 'SITE_ID', $this->EE->config->item( 'site_id' ) );

		// set the settings for all other methods to access
		$this->settings = ( $settings == FALSE ) ? $this->EE->mx_core->_getSettings() : $this->EE->mx_core->_saveSettingsToSession( $settings );
		$this->settings = array_merge($this->default, $this->settings);
	}


	/**
	 * Prepares and loads the settings form for display in the ExpressionEngine control panel.
	 *
	 * @since Version 1.0.0
	 * @access public
	 * @return void
	 * */

	public function settings_form() {


		$this->EE->lang->loadfile( 'mx_cp_notice' );

		// Create the variable array
		$vars = array(
			'addon_name' => MX_CP_NOTICE_NAME,
			'error' => FALSE,
			'input_prefix' => __CLASS__,
			'message' => FALSE,
			'settings_form' =>FALSE,
			'language_packs' => ''
		);

		$vars['settings'] = $this->settings;
		$vars['settings_form'] = TRUE;


		if ( $new_settings = $this->EE->input->post( __CLASS__ ) ) {

			$vars['settings'] = $new_settings;

			$this->EE->mx_core->_saveSettingsToDB( $new_settings );

			$this->_ee_notice( $this->EE->lang->line( 'extension_settings_saved_success' ) );
		}

		return $this->EE->load->view( 'form_settings', $vars, true );

	}

	// END

	/**
	 * _ee_notice function.
	 *
	 * @access private
	 * @param mixed   $msg
	 * @return void
	 */
	function _ee_notice( $msg ) {
		$this->EE->javascript->output( array(
				'$.ee_notice("'.$this->EE->lang->line( $msg ).'",{type:"success",open:true});',
				'window.setTimeout(function(){$.ee_notice.destroy()}, 3000);'
			) );
	}

/*
info
error



*/


	/**
	 * cp_js_end function.
	 *
	 * @access public
	 * @param mixed   $data
	 * @return void
	 */
	function cp_js_end( $data ) {

		if ($this->EE->extensions->last_call !== FALSE)
        {
               $data = $this->EE->extensions->last_call;
        }
		
		$app_path = PATH_THIRD . 'mx_cp_notice';

		$out = file_get_contents($app_path.'/javascript/messenger.min.js');
		$out .= file_get_contents($app_path.'/javascript/messenger-theme-future.js');

		$out .=
		 '
            $(function () {

	            	$.ee_notice = function (a, k) {
	            		Messenger.options = {
						    extraClasses: "messenger-fixed messenger-on-'.$this->settings['location_h'].' messenger-on-'.$this->settings['location_v'].'",
						    theme: "'.$this->settings['theme'].'"
						}


						Messenger().post({
						  message: a,
						  type: k.type,
						  hideAfter: '.$this->settings['hideAfter'].',
						  showCloseButton: '.$this->settings['showCloseButton'].',
						  hideAfter: '.$this->settings['hideAfter'].'
						});

					 }



					 $.ee_notice.destroy = function () {

					 }


					 $("#notice_texts_container > div").each(function (obj) {
					 	if ($(this).html() != "") {
					 		var notice_type = "success";
					 		notice_type = $(this).hasClass("notice_alert") ? "alert" : notice_type;
					 		notice_type = $(this).hasClass("notice_error") ? "error" : notice_type;
					 		notice_type = $(this).hasClass("notice_custom") ? "custom" : notice_type;
					 		$.ee_notice($(this).html(),{type: notice_type,open:true});
					 	
					 	}
					 })
					

					var block_errors = [];

					$("#holder").children().each(function()
					{
						if($(this).find("fieldset.holder > div.notice").length > 0)
						{
							block_errors.push($(this).attr("id"));
						}

					});



					if(block_errors.length > 0)
					{
						for(e=0; e<block_errors.length; e++)
						{
							var em = block_errors[e].replace("menu_", "");
							em = em.replace(/_/g, " ");

												

							$("#" + block_errors[e]).find("div.notice").each(function()
							{
								
									$.ee_notice(em + " tab: " + $(this).html(),{type: "error",open:true});

								
							});

						
						}
						
					}




            }); 
		';




        $data .= $out;

		return $data;
	}


	/**
	 * cp_css_end function.
	 *
	 * @access public
	 * @param mixed   $data
	 * @return void
	 */
	function cp_css_end( $data ) {
		if ($this->EE->extensions->last_call !== FALSE)
        {
               $data = $this->EE->extensions->last_call;
        }
		
		$app_path = PATH_THIRD . 'mx_cp_notice';

		$out = file_get_contents($app_path.'/css/messenger.css');
		$out .= NL . file_get_contents($app_path.'/css/messenger-theme-'.$this->settings['theme'].'.css');

		$out .= NL . '#notice_container {display:none;}';
 	

        $data .= NL .  $out;

		$this->EE->extensions->end_script = FALSE;

		return $data;
	}



	// --------------------------------
	//  Activate Extension
	// --------------------------------

	function activate_extension() {
		$this->EE->mx_core->_createHooks( $this->hooks );
	}

	// END

	// --------------------------------
	//  Update Extension
	// --------------------------------

	function update_extension( $current='' ) {

		if ( $current == '' or $current == $this->version ) {
			return FALSE;
		}

		if ( $current < '2.0.1' ) {
			// Update to next version
		}

		$this->EE->db->query( "UPDATE exp_extensions SET version = '".$this->EE->db->escape_str( $this->version )."' WHERE class = '".get_class( $this )."'" );
	}
	// END

	// --------------------------------
	//  Disable Extension
	// --------------------------------

	function disable_extension() {

		$this->EE->db->delete( 'exp_extensions', array( 'class' => get_class( $this ) ) );
	}
	// END
}

/* End of file ext.mx_cp_notice.php */
/* Location: ./system/expressionengine/third_party/mobile_detect/ext.mx_cp_notice.php */
