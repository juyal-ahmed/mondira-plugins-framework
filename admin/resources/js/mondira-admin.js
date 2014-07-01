var mondiraAdmin = {
    
    //displaying added image with fancybox effect
    //NEED TO ADD LOADING ICON HERE ------------------------------------
	themeGetNDisplayImage : function(attachment_id,target){
        
        
        var cssObj = {
          'background-color' : '#fff',
          'border' : '1px solid #CCC',
          'height' : '120px',
          'width' : '140px',
          'padding' : '5px',
          'text-align' : 'center'
        }
        jQuery("#"+target+"_preview").css(cssObj);
        jQuery("#"+target+"_preview").html('<img style="margin-top:44px;" src="'+ MONDIRA_PLUGINS_FRAMEWORK_ADMIN_RESOURCES_URI + '/images/ajax-loader.gif"/>');
        
        jQuery.post(ajaxurl, {
            action:'mondira-image-upload-get-image',
            id: attachment_id, 
            cookie: encodeURIComponent(document.cookie)
        }, function(src){
            if ( src == '0' ) {
                alert( 'Empty image source.');
            } else {
                if(jQuery("#"+target).size()>0){
                    jQuery("#"+target).val(src);
                    jQuery("#"+target+"_preview").html('<a class="fancybox prview_thumb_image" href="'+src+'"><img src="' + src+'" width="140" /></a>');
                }
            }
            
            resetAll();
        });
    },
    
    themeGetNDisplayZIP : function(attachment_id,target){
        jQuery("#"+target).val(attachment_id);
	}
}

jQuery(document).ready( function($) {
	resetAll();
    
    $(".yesno")
      // attach the iButton behavior
      .iButton({
         labelOn: "Yes"
       , labelOff: "No"
       , change: function ($input){
           if($input.is(":checked")){
                $input[0].value = 'yes';    
           } else {
               $input[0].value = 'no';
           }
        }
      })
      // trigger the change event (to update the text)
      .trigger("change");
   $(":range").rangeinput();
          
          
          
    $(function(){

        var pickerOpts = {

        appendText: "mm/dd/yyyy",

        defaultDate: "+5",

        showOtherMonths: true

        };  

        $(".datepicker").datepicker(pickerOpts);

    });
    
     $('.wp-color-picker').wpColorPicker();
    
 
});



function resetAll(){
    creatTabForMondiraDocumentation();
    enablingFancyBox();
}

function creatTabForMondiraDocumentation(){
    if(jQuery("#mondira-docs-tabs").size()>0){
        jQuery("#mondira-docs-tabs").tabs({selected:0});    
    }
}

function enablingFancyBox(){
    //jQuery("a.fancybox").fancybox();
}

//Enabling input fold on/off
jQuery(document).ready(function($){
    $('input[data-fold^="_"]').each(function(){
        var $input_obj = $(this); // assigning the object
        
        var fold_id = $input_obj.data("fold");
        
        if($('#'+fold_id).is(':checked')){
            
        } else {
            $input_obj.parents("tr").hide("slow");    
        }
        
        $('#'+fold_id).change(function(){
            if($('#'+fold_id).is(':checked')){
                $input_obj.parents("tr").show("slow");
            } else {
                $input_obj.parents("tr").hide("slow");    
            }    
        });
        
    });                                                              
});