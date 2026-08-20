<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

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
            self::getCapability(),
            'pisol-dpmw-settings',
            array($this, 'menu_option_page'),
            plugin_dir_url( __FILE__ ).'img/pi.svg',
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
        
 
    }

    static function  getCapability(){
        $access_control = get_option('pi_dpmw_allow_shop_manager', '0');
        if(empty($access_control)){
            $capability = 'manage_options';
        }else{
            $capability = 'manage_woocommerce';
        }

        return (string)apply_filters('pisol_dpmw_settings_cap', $capability);
    }

    public function bootstrap_style() {

        add_thickbox();

        wp_enqueue_style( $this->plugin_name.'-bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name.'-promotion', plugin_dir_url( __FILE__ ) . 'css/promotion.css', array(), $this->version, 'all' );
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
                            <div class="col-12 col-sm-2 py-3 d-flex align-items-center justify-content-center">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img id="pi-logo" class="img-fluid ml-2" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/pi-web-solution.svg"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <nav id="pisol-navbar" class="navbar navbar-expand-lg navbar-light mr-0 ml-auto">
                                    <div>
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <?php do_action($this->plugin_name.'_tab'); ?>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="pisol-row">
                <div class="col-12">
                <div id="pisol-dpmw-notices"></div>
                <div class="bg-light border pl-3 pr-3 pt-0">
                    <div class="pisol-row">
                        <div class="col border-right">
                            <div class="pi-dpmw-arrow-circle closed" title="Open / Close sidebar">
                                <svg class="pi-dpmw-arrow-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <!-- First arrow -->
                                    <path d="M13 6l-6 6 6 6" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <!-- Second arrow (slightly right-shifted) -->
                                    <path d="M17 6l-6 6 6 6" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <?php do_action('pisol_dpmw_dependency_install'); ?>
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
        $this->support();
    }

    function promotion(){
        ?>
        <div class="col-12 col-xl-4 col-lg-4 col-md-4" id="promotion-sidebar">
            <div class="pisol-pro-banner">
            <div class="pisol-pro-header">
                <div class="pisol-pro-logo">
                    <img class="pisol-new-promotion-box-icon" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'img/pi-web-solution-icon.svg' ); ?>">
                </div>
                <h2>Get Premium</h2>
                <p class="pisol-pro-trust">Trusted by 4000+ websites</p>
            </div>
            <ul class="pisol-pro-features">
                <li>Partial payment rules with conditions</li>
                <li>Unlimited disable payment method rules</li>
                <li>Unlimited payment method fees rules</li>
                <li>Unlimited Partial payment OR Advance Fee for Cash on Delivery rules</li>
                <li>Different partial payment amount based on country / state / zone / postcode</li>
                <li>Offer partial payment based on the Order subtotal</li>
                <li>Offer partial payment based on the User role</li>
                <li>All rules support Multi-currency</li>
            </ul>
            <div class="pisol-pro-price"><?php echo esc_html(DISABLE_PAYMENT_METHOD_FOR_WOOCOMMERCE_PRICE); ?> only</div>
            <a href="<?php echo esc_url(DISABLE_PAYMENT_METHOD_FOR_WOOCOMMERCE_BUY_URL); ?>" target="_blank" class="pisol-pro-btn">Unlock Pro Now – Limited Time Price!</a>
            <div class="pisol-pro-rating">
                <span class="pisol-pro-wp">W</span>
                <span class="pisol-pro-stars">★★★★★</span>
                <span>(5/5 Read reviews)</span>
            </div>
            </div>
        </div>
        <?php
    }

    function support(){
        $website_url = home_url();
        $plugin_name = $this->plugin_name;
        ?>
        <form action="https://www.piwebsolution.com/quick-support/" method="post" target="_blank" style="display:inline; position:fixed; bottom:30px; right:25px; z-index:9999;" >
            <input type="hidden" name="website_url" value="<?php echo esc_attr( $website_url ); ?>">
            <input type="hidden" name="plugin_name" value="<?php echo esc_attr( $plugin_name ); ?>">
            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/chat.png" 
                    alt="Live Support" title="Quick Support" style="width:60px;height:60px;">
            </button>
        </form>
        <?php
    }
}