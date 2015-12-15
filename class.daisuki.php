<?php

class daisuki {
    
    private		$ip;
	public		$post_id;
	public		$user_id;
	public		$daisuki_count;
	public		$is_loggedin;
    
    public function __construct($post_id, $user_id){
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->post_id = $post_id;
		$this->user_id = $user_id;
		
		if( $user_id && $user_id > 0 ){
			$this->is_loggedin = true;
		}
		
		$this->daisuki_count();
	}
    
    public function daisuki_count(){
		global $wpdb, $daisuki_table_name;
		
		// check in the db for daisuki
		$daisuki_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(post_id) FROM $daisuki_table_name WHERE post_id = %d", $this->post_id));
		
		// returns daisuki, return 0 if no daisuki were found
		$this->daisuki_count = $daisuki_count;
		
	}
    
    public function is_daisuki(){
		if( isset($_COOKIE['daisuki_'.$this->post_id]) ){
			return true;
		}

		global $wpdb, $daisuki_table_name;
		
		if($this->is_loggedin){
			// 用户登录	
			$daisuki_check = $wpdb->get_var($wpdb->prepare("SELECT COUNT(post_id) FROM $daisuki_table_name
											WHERE	post_id = %d
											AND		user_id = %d", $this->post_id, $this->user_id));
		} else{
			// 未登陆，根据ip地址判定重复
			$daisuki_check = $wpdb->get_var($wpdb->prepare("SELECT COUNT(post_id) FROM $daisuki_table_name
											WHERE	post_id = %d
											AND		ip_address = %s
											AND		user_id = %d", $this->post_id, $this->ip, 0));
		}

		$daisuki_check = intval($daisuki_check);

		return $daisuki_check && $daisuki_check > 0;
	}
    
    
    //点赞
    public function add_daisuki(){
		global $wpdb, $daisuki_table_name;
		
		if( !$this->is_daisuki() ){
			$wpdb->insert($daisuki_table_name, array('post_id' => $this->post_id, 
													'user_id' => $this->user_id,
													'ip_address' => $this->ip), array('%d', '%d', '%s'));

			$expire = time() + 365*24*60*60;
        	setcookie('daisuki_'.$this->post_id, $this->post_id, $expire, '/', $_SERVER['HTTP_HOST'], false);
		}

		$this->daisuki_count();
	}
    
    public function daisuki_button($odc){
		$class = $this->is_daisuki() ? 'daisuki_done' : 'daisuki_none';
		$userId = $this->is_loggedin ? $this->user_id : 0;	
		$postId = $this->post_id;

		$action = "daisuki($postId, $userId)";
		
		$btn_html = $odc ? '<a id="daisuki-%d" class="like_a %s" onclick="%s" href="javascript:;"><i class="like_icon"></i>赞<i class="count">(%d)</i></a>' : '<a id="daisuki-%d" class="like_a %s" onclick="%s" href="javascript:;"><i class="like_icon"></i>赞<i class="count">(%d)</i></a>';
		$button = sprintf($btn_html, $postId, $class, $action, $this->daisuki_count);

		return $button;
	}
    
}

?>