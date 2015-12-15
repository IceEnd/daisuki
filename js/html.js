(function(){
    
    function loadjscssfile(filename){
        var fileref=document.createElement('script')                                                        
        fileref.setAttribute("type","text/javascript")//定义属性type的值为text/javascript 
        fileref.setAttribute("src", filename)//文件的地址 
        document.getElementsByTagName("head")[0].appendChild(fileref) 
    } 
    
    if(typeof jQuery == 'undefined') { 
        //jQuery 未加载 
        alert('kaishijiazai'); 
        loadjscssfile("jquery-1.11.3.min.js"); 
    } 
    
    $('.shang_a').click(function(){
        $('#pay_div').slideToggle('slow');
    });
    
    $('.share_a').click(function(){
        $('.share_div').slideToggle('slow');
    });
})();

