<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Class_Pisol_Dpmw_Disable_Rules_list{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "Payment method rules";

    private $setting_key = 'pi_dpmw_list_shipping';
    
    public $tab;

    public $post_id;
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

       
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $action = sanitize_text_field(filter_input(INPUT_GET, 'action'));
        if($action == 'dpmw_disable_rule_delete'){
            $this->post_id = (int) filter_input(INPUT_GET, 'id');
            add_action('init',array($this,'deletePost' ));
        }

    }

    
    function tab(){
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo esc_attr(($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary')); ?>" href="<?php echo esc_url(admin_url( 'admin.php?page='.sanitize_text_field(wp_unslash($_GET['page'] ?? '')).'&tab='.$this->this_tab )); ?>">
           <span class="dashicons dashicons-admin-settings"></span> <?php echo esc_html( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
       $this->listShippingMethod();
    }

    function listShippingMethod(){
        
        include plugin_dir_path( __FILE__ ) . 'partials/list-disable-payment-rules.php';
    }

    function deletePost(){
        $post_id = (int) $this->post_id;

        if ( $post_id <= 0 ) {
            wp_die( esc_html__( 'Invalid rule.', 'disable-payment-method-for-woocommerce' ), '', array( 'response' => 400 ) );
        }

        $submitted_value = isset($_REQUEST['_wpnonce']) ? sanitize_text_field(wp_unslash( $_REQUEST['_wpnonce'] )) : '';
        if(!wp_verify_nonce($submitted_value, 'dpmw-delete-' . $post_id)){
            wp_die( 'Your page has expired, refresh and try again' );
        }

        $access = Pi_dpmw_Menu::getCapability();
        if(!current_user_can( $access )) {
            wp_safe_redirect(  admin_url( '/admin.php?page=pisol-dpmw-settings' )  );
            exit();
        }

        /*
         * The ID comes from the request, so make sure the target really is one of our
         * payment method rules. Without this check an authorized user could delete any
         * page or post by changing the "id" parameter.
         */
        if ( get_post_type( $post_id ) !== 'pi_dpmw_rules' ) {
            wp_die( esc_html__( 'Invalid rule.', 'disable-payment-method-for-woocommerce' ), '', array( 'response' => 403 ) );
        }

        wp_delete_post( $post_id );
        wp_safe_redirect(  admin_url( '/admin.php?page=pisol-dpmw-settings' )  );
        exit();
    }
    
}

new Class_Pisol_Dpmw_Disable_Rules_list($this->plugin_name);