<?php

function wp_daisuki($odc=false){
    global $user_ID;
    get_currentuserinfo();

    $user_ID = $user_ID ? $user_ID : 0;
    $daisuki = new daisuki(get_the_ID(), $user_ID);

    return $daisuki->daisuki_button($odc);
}
    
function daisuki_install(){
    global $wpdb, $daisuki_table_name;

    if( $wpdb->get_var("show tables like '{$daisuki_table_name}'") != $daisuki_table_name ) {
        $wpdb->query("CREATE TABLE {$daisuki_table_name} (
        id      BIGINT(20) NOT NULL AUTO_INCREMENT,
        post_id BIGINT(20) NOT NULL,
        user_id BIGINT(20) NOT NULL,
        ip_address VARCHAR(25) NOT NULL,
        UNIQUE KEY id (id)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
    }
}

function daisuki_uninstall(){
    global $wpdb, $daisuki_table_name;
    $wpdb->query("DROP TABLE IF EXISTS {$daisuki_table_name}");
}

function daisuki_callback(){
    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];

    $daisuki = new daisuki($post_id, $user_id);
    if( $daisuki->is_daisuki() ){
        $result = array(
            'status' => 300
        );
    }else{
        $daisuki->add_daisuki();

        $result = array(
				'status' => 200,
				'count' => $daisuki->daisuki_count
        );
    }

    header('Content-type: application/json');
    echo json_encode($result);
    exit;
}
add_action( 'wp_ajax_daisuki', 'daisuki_callback');
add_action( 'wp_ajax_nopriv_daisuki', 'daisuki_callback');

add_action('admin_menu', 'daisuki_menu');
function daisuki_menu() {
    add_options_page('daisuki 设置', 'daisuki 设置', 'manage_options', basename(__FILE__), 'daisuki_setting_page');
    add_action( 'admin_init', 'daisuki_setting_group');
}

function daisuki_setting_group() {
    register_setting( 'daisuki_setting_group', 'daisuki_setting' );
}

function daisuki_setting_page(){
    @include 'daisuki-setting.php';
}

add_action('admin_enqueue_scripts', 'daisuki_setting_scripts');
function daisuki_setting_scripts(){
    if( isset($_GET['page']) && $_GET['page'] == "daisuki.function.php" ){
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'daisuki_setting', plugins_url('js/daisuki-setting.js', __FILE__), array( 'wp-color-picker' ), false, true );	
    }
}

function wpzan_get_setting($key=NULL){
    $setting = get_option('daisuki_setting');
    return $key ? $setting[$key] : $setting;
}

?>