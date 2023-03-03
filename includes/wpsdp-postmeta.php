<?php 

defined( 'ABSPATH' ) || exit;

class WPDSP_Postmeta {

    function __construct() {
        add_action( 'admin_menu', [$this, 'admin_taxo_menu_page'] );
        add_action( 'init', [$this, 'save_form_info'] );
        add_action( 'elementor_pro/forms/actions/after_run', [$this, 'callback_api_newsletter'] );
    }

    // Add the custom page to the admin menu
    function admin_taxo_menu_page() {
        add_menu_page(
            'WP Taxo Settings',
            'WP Taxo Settings',
            'manage_options', 
            'wpsdp-taxo-settings', 
            [$this, 'callback_show_page_content']
        );
    }

    public function callback_show_page_content() { 
    ?>
        <div class="wrap wpsdp-container">      
            <h1> <?php esc_html_e( 'Category and Tag Selection Page', 'wp-taxo-show-default-postmeta'); ?> </h1>
            <div class="content-area">
                <form method="post" class="wpsdp-form">
                    <?php $this->list_categories(); ?>
                    <?php $this->list_post_tags(); ?>
                    <div class="wpsdp-form-submit">
                        <input type="submit" value="<?php echo esc_attr( 'Save' ); ?>" name="wpsdp_form_submit" class="wpsdp-form-btn" />
                    </div>
                    <?php wp_nonce_field( 'wpdsp-taxo-action', 'wpdsp-form-nonce' ); ?>
                </form>
            </div>
        </div>
    <?php    
    }

    public function list_categories() {

        $args = array(            
            'taxonomy' => 'category',
	        'hide_empty' => false
        );        
        $categories = get_terms( $args );
        $taxo_list = get_option('wpsdp_taxo_list');
    ?>
        <div class="wpsdp-form-field">
            <label><?php esc_html_e( 'Categories', 'wp-taxo-show-default-postmeta' ); ?> </label>
            <select name="wpsdp_cats[]" class="wpsdp-category-selection" multiple="multiple" data-placeholder="Select Categories">
                <?php if ( $categories ) : foreach ( $categories as $category ) :  ?>
                    <?php if( $category->name == "Uncategorized" ) : continue; endif; ?>
                    <?php $checked = in_array( $category->slug, $taxo_list['cats'] ) ? 'selected' : ''; ?>
                    <option value="<?php echo $category->slug; ?>" <?php echo $checked; ?>> <?php echo esc_html_e( $category->name, 'wp-taxo-show-default-postmeta' ); ?> </option>
                <?php endforeach; endif; ?>                
            </select>
        </div>        
    <?php   
    }
    
    public function list_post_tags() {

        $args = array(            
            'taxonomy' => 'post_tag',
	        'hide_empty' => false
        );        
        $tags = get_terms( $args );
        $taxo_list = get_option('wpsdp_taxo_list');
    ?>
        <div class="wpsdp-form-field">
            <label><?php echo esc_html_e( 'Tags', 'wp-taxo-show-default-postmeta' ); ?> </label>
            <select name="wpsdp_tags[]" class="wpsdp-tags-selection" multiple="multiple" data-placeholder="Select Tags"> 
                <?php if ( $tags ) : foreach ( $tags as $tag ) : ?>
                    <?php $checked = in_array( $tag->slug, $taxo_list['tags'] ) ? 'selected' : ''; ?>
                    <option value="<?php echo $tag->slug; ?>" <?php echo $checked; ?> > <?php echo esc_html_e( $tag->name, 'wp-taxo-show-default-postmeta' ); ?> </option>
                <?php endforeach; endif; ?>                
            </select>
        </div>        
    <?php   
    }
    
    function save_form_info() {

        if( isset( $_POST['wpsdp_form_submit'] ) && isset( $_POST['wpdsp-form-nonce'] ) && wp_verify_nonce( $_POST['wpdsp-form-nonce'], 'wpdsp-taxo-action' ) ) {
            
            $taxo['tags'] = $_POST['wpsdp_tags'];
            $taxo['cats'] = $_POST['wpsdp_cats'];            
                        
            if( empty($taxo['cats']) && empty($taxo['tags']) ) {
                update_option( 'wpsdp_taxo_list', array() );
            }else {
                update_option( 'wpsdp_taxo_list', $taxo );
            }
        }
    }

    function callback_api_newsletter() {
        
        $post_id = get_the_ID();
        $settings = get_option('wpsdp_taxo_list');

        if( !empty($settings) && !empty($post_id) ) {

            $categories = get_the_category( $post_id );
            $tags = wp_get_post_tags( $post_id );

            if ( !empty( $categories ) || !empty( $tags ) ) {

                if ( in_category( $settings['cats'], $post_id ) || has_term( $settings['tags'], 'post_tag', $post_id ) ) {
                    // echo 'yes i have all things';
                }else {
                    // echo 'NO i does not have all things';
                }
            }
        }
    }
}
