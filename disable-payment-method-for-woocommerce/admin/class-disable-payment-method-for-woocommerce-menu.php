<?php

class Pi_dpmw_Menu{

    public $plugin_name;
    public $menu;
    public $version;
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));
    }

    function plugin_menu(){
        
        $this->menu = add_menu_page(
            __( 'Payment Method','disable-payment-method-for-woocommerce'),
            __( 'Payment Method','disable-payment-method-for-woocommerce'),
            'manage_options',
            'pisol-dpmw-settings',
            array($this, 'menu_option_page'),
            plugin_dir_url( __FILE__ ).'img/pi.svg',
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
        
 
    }

    static function  getCapability(){
        $capability = 'manage_options';

        return (string)apply_filters('pisol_dpmw_settings_cap', $capability);
    }

    public function bootstrap_style() {

        add_thickbox();

        wp_enqueue_style( $this->plugin_name.'-bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/disable-payment-method-for-woocommerce-admin.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/disable-payment-method-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

        wp_enqueue_style( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.min.js', array('jquery'), $this->version);

        wp_enqueue_script( $this->plugin_name."_timepicker", plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.min.js', array('jquery'), $this->version);

        wp_enqueue_style( $this->plugin_name."_timepicker", plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name."_datepicker", plugin_dir_url( __FILE__ ) . 'js/flatpickr.min.js', array('jquery'), $this->version);

        wp_enqueue_style( $this->plugin_name."_datepicker", plugin_dir_url( __FILE__ ) . 'css/flatpickr.min.css', array(), $this->version, 'all' );


        wp_localize_script( $this->plugin_name, 'dpmw_variables',
            array( 
                '_wpnonce' => wp_create_nonce( 'dpmw-actions' )
            )
	    );

        wp_enqueue_script( $this->plugin_name."_quick_save", plugin_dir_url( __FILE__ ) . 'js/pisol-quick-save.js', array('jquery'), $this->version, 'all' );
		
	}

    function menu_option_page(){
        if(function_exists('settings_errors')){
            settings_errors();
        }
        ?>
        <div id="bootstrap-wrapper" class="pisol-setting-wrapper  pisol-container-wrapper">
        <div class="pisol-container-fluid mt-2">
            <div class="pisol-row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="pisol-row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.svg"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="pisol-row">
                <div class="col-12">
                <div id="pisol-dpmw-notices"></div>
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
                    <div class="pisol-row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    function promotion(){
       ?>
        <div class="col-12 col-sm-4 mt-3" id="promotion-sidebar">
            <a href="javascript:void()" onClick="jQuery(this).parent().remove()" class="text-right">Hide Banner</a>
            <div class="pi-shadow">
                <div class="pisol-row justify-content-center">
                    <div class="col-md-10 col-sm-12">
                        <div class="p-2  text-center">
                            <img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ )); ?>img/bg.svg">
                        </div>
                    </div>
                </div>
                <div class="text-center py-2">
                    <a class="btn btn-success btn-sm text-uppercase mb-2 " href="<?php echo esc_url(DISABLE_PAYMENT_METHOD_FOR_WOOCOMMERCE_BUY_URL); ?>&utm_ref=top_link" target="_blank">Buy Now !!</a>
                </div>
                <h2 id="pi-banner-tagline" class="mb-0">Get Pro for <?php echo esc_html(DISABLE_PAYMENT_METHOD_FOR_WOOCOMMERCE_PRICE); ?> Only</h2>
                <div class="inside">
                    <ul class="text-left  h6 pisol-pro-feature-list">
                    <li class="border-top h6"><span>Unlimited disable</span>  payment method rules</li>
                    <li class="border-top h6"><span>Unlimited payment</span>  method fees rules</li> 
                    <li class="border-top h6"><span>Unlimited Partial payment OR Advance Fee for Cash on Delivery </span> rules</li>
                    <li class="border-top h6"><span>Partial payment</span> rules with conditions</li>
                    <li class="border-top h6">Different <span>partial payment</span> amount <span>based on country / state / zone / postcode </span></li>
                    <li class="border-top h6">Offer <span>partial payment</span> based on the <span>Order subtotal</span></li>
                    <li class="border-top h6">Offer <span>partial payment</span> based on the <span>User role</span></li>   
                    <li class="border-top h6">All rules support <span>Multi-currency</span></li>                           
                    </ul>
                </div>
                <div class="text-center pb-3 pt-2">
                        <a class="btn btn-primary btn-md" href="<?php echo esc_url( DISABLE_PAYMENT_METHOD_FOR_WOOCOMMERCE_BUY_URL ); ?>&utm_ref=bottom_link" target="_blank">BUY PRO VERSION</a>
                    </div>
            </div>
        </div>
       <?php
    }

}