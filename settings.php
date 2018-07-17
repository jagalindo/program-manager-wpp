<?php
class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        if( is_admin() ) {
        	function my_admin_load_styles_and_scripts() {
        		$mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
        		$modes = array( 'grid', 'list' );
        			if ( isset( $_GET['mode'] ) && in_array( $_GET['mode'], $modes ) ) {
            				$mode = $_GET['mode'];
            				update_user_option( get_current_user_id(), 'media_library_mode', $mode );
        			}
        		if( ! empty ( $_SERVER['PHP_SELF'] ) && 'upload.php' === basename( $_SERVER['PHP_SELF'] ) && 'grid' !== $mode ) {
            			wp_dequeue_script( 'media' );
        		}
        	wp_enqueue_media();
    		}
    	add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
	}
  
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Program Manager Settings', 
            'manage_options', 
            'program_manager_admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'program_manager_option' );
        ?>
        <div class="wrap">
            <h1>Program Manager Settings</h1>
            <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'program_manager_option_group' );
                do_settings_sections( 'program_manager_admin' );
                submit_button();
            ?>
	    </form>
	    <div>
		<p>We do provide the following shotcodes:</p>
		<p>write_full_program: To write the full program</p>
	    </div>
	</div>



	<?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'program_manager_option_group', // Option group
            'program_manager_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'program_manager_section_id', // ID
            'Program generator options', // Title
            array( $this, 'print_section_info' ), // Callback
            'program_manager_admin' // Page
        );  

        add_settings_field(
            'program_data_url', // ID
            'Google sheets URL', // Title 
            array( $this, 'program_data_url_callback' ), // Callback
            'program_manager_admin', // Page
            'program_manager_section_id' // Section           
        );      

 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['program_data_url'] ) )
            $new_input['program_data_url'] = sanitize_text_field( $input['program_data_url'] );
        return $new_input;
    }


    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function pre_days_callback()
    {
        printf(
            '<input type="text" id="program_data_url" name="program_manager_option[program_data_url]" value="%s" />',
            isset( $this->options['program_data_url'] ) ? esc_attr( $this->options['program_data_url']) : ''
        );
    }

   
 
 
}

