function daisuki(post_id, user_id){
    
    var id = "#daisuki-" + post_id,
    $daisuki = jQuery(id);
    
    if( $daisuki.hasClass('daisuki_done') ){
        alert('住手，无耻老贼！');
        return;
    }
    
    if(post_id){
        jQuery.post(daisuki_ajax_url, {
            "action": "daisuki",
            "post_id": post_id,
            "user_id": user_id
        }, function(result) { 
            if( result.status == 200 ){
               var $count = $daisuki.find('.count');
                $daisuki.addClass('daisuki_done');
                $daisuki.removeClass('daisuki_none');
                if((result.count/1000) < 1.0){
                    console.log('true');
                    var res = result.count +'';
                }
                else{
                    console.log('false');
                    var res = (result.count/1000).toFixed(1) +'K';
                }
			    $count.text('('+res+')');
		   }else{
			   alert('只有一块硬币哦~~');
               
           }
        }, 'json');		
    }
}