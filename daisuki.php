<?php
/*
Plugin Name: daisuki
Plugin URI: http://blog.coolecho.net
Description: 文章点赞，社会分享，投食。
Version: 1.0.0
Author: Cononico
Author URI: http://blog.coolecho.net/2015/12/daisuki.html
Text Domain: daisuki
*/


/*
	Copyright 2015  Cononico (email : min@coolecho.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('DAISUKI_PATH', dirname( __FILE__ ));
define('DAISUKI_ADMIN_URL', admin_url());
define('DAISUKI_VERSION', '0.0.8');


add_filter('the_content',display_daisuki);
function display_daisuki($content){
   if(is_single()){
        
        /*支付宝邮箱账号*/
        $payemail="min@coolecho.net";
        global $post;
        $content.= '<!-- DAISUKI OF HTML -->
        <div id="daisuki_main" class="daisuki_main">
        <div class="daisuki">
            <span class="like_span">'.wp_daisuki().'
            </span>
            <span class="shang">
                <a class="shang_a" title="投食">投</a>
            </span>
            <span class="share">
                <a class="share_a" title="搞比利"><i class="share_icon"></i>扩散</a>
            </span>
        </div>
        <div class="pay_div" id="pay_div">
            <div class="pay_main">
                <h4>支付宝投食</h4>
                <form accept-charset="GBK" action="https://shenghuo.alipay.com/send/payment/fill.htm" method="POST" target="_blank">
                <input name="optEmail" type="hidden" value="'.$payemail.'">
                <input name="payAmount" type="hidden" value="0">
                <input id="title" name="title" type="hidden" value="投食">
                <input name="memo" type="hidden" value="请填写您的联系方式，面基之需！">
                <input title="赞助本站" name="pay" src="'.plugins_url('img/alipay.png',__FILE__).'" type="image" value="投食">
                </form>
                <h4>扫描投食</h4>
                <img title="掏出支付宝扫一扫" src="'.plugins_url('img/qr_code.png',__FILE__).'">
                <h4>支付宝扫一扫</h4>
            </div>
        </div>
        <div class="share_div">
            <!-- JiaThis Button BEGIN -->
            <div class="jiathis_style_32x32 jiathis_div">
	           <a class="jiathis_button_qzone"></a>
	           <a class="jiathis_button_tsina"></a>
	           <a class="jiathis_button_tqq"></a>
	           <a class="jiathis_button_weixin"></a>
	           <a class="jiathis_button_renren"></a>
	           <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
	           <a class="jiathis_counter_style"></a>
            </div> 
            <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
            <!-- JiaThis Button END -->
        </div>
        </div>';
    }
    return $content;
}

//加载脚本
function daisuki_load_scripts() {    
    wp_enqueue_script( 'daisuki',  plugins_url('js/func.js', __FILE__), array(), DAISUKI_VERSION );
    wp_enqueue_style( 'daisuki', plugins_url('css/style.css', __FILE__), array(),DAISUKI_VERSION);
    wp_register_script('load_script', plugins_url('js/html.js', __FILE__), array('jquery'),'1.11.3', true);
    wp_enqueue_script('load_script'); 
    wp_localize_script( 'daisuki', 'daisuki_ajax_url', DAISUKI_ADMIN_URL . 'admin-ajax.php');
 }
 add_action( 'wp_enqueue_scripts', 'daisuki_load_scripts',20,1 );

/*
*定义数据库
*/
global $wpdb, $daisuki_table_name;
$daisuki_table_name = isset($table_prefix) ? ($table_prefix . 'daisuki') : ($wpdb->prefix . 'daisuki');

/**
 * 插件激活,新建数据库
 */
register_activation_hook(__FILE__, 'daisuki_install');

/**
* 加载函数 
*/
require DAISUKI_PATH . '/daisuki.function.php';

/**
* 加载类
*/
require DAISUKI_PATH . '/class.daisuki.php';

?>