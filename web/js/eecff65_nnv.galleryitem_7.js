/**
GalleryItem editable input.
Internally value stored as {type: "product", content: "..."}

@class GalleryItem
@extends abstractinput
@final
@example
<a href="#" id="address" data-type="address" data-pk="1">awesome</a>
<script>
$(function(){
    $('#galleryitem').editable({
        url: '/',
        title: '',
        value: {
            type: "product", 
            content: "Lenina"
        }
    });
});
</script>
**/
(function ($) {
    "use strict";
    
    var GalleryItem = function (options) {
        this.init('galleryitem', options, GalleryItem.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(GalleryItem, $.fn.editabletypes.abstractinput);

    $.extend(GalleryItem.prototype, {
        /**
        Renders input from tpl

        @method render() 
        **/        
        render: function() {
            this.$input = this.$tpl.find('input, select, textarea');
//            this.$input.filter('[name="type"]').change(function(e){
//                // 选择entity类型时提示
//            });
        },
        
        /**
        Default method to show value in element. Can be overwritten by display option.
        
        @method value2html(value, element) 
        **/
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return; 
            }
            
            var html = $('<div>').text(value.content).html();
            $(element).html(html); 
            $(element).attr('data-metatype', value.type);
        },
        
        /**
        Gets value from element's html
        
        @method html2value(html) 
        **/        
        html2value: function(html) {  
          /*
            you may write parsing method to get value by element's html
            e.g. "Moscow, st. Lenina, bld. 15" => {city: "Moscow", street: "Lenina", building: "15"}
            but for complex structures it's not recommended.
            Better set value directly via javascript, e.g. 
            editable({
                value: {
                    city: "Moscow", 
                    street: "Lenina", 
                    building: "15"
                }
            });
          */ 
          return null;  
        },
      
       /**
        Converts value to string. 
        It is used in internal comparing (not for sending to server).
        
        @method value2str(value)  
       **/
       value2str: function(value) {
           var str = '';
           if(value) {
               for(var k in value) {
                   str = str + k + ':' + value[k] + ';';  
               }
           }
           return str;
       }, 
       
       /*
        Converts string to value. Used for reading value from 'data-value' attribute.
        
        @method str2value(str)  
       */
       str2value: function(str) {
           /*
           this is mainly for parsing value defined in data-value attribute. 
           If you will always set value by javascript, no need to overwrite it
           */
           return str;
       },                
       
       /**
        Sets value of input.
        
        @method value2input(value) 
        @param {mixed} value
       **/         
       value2input: function(value) {
           if(!value) {
             return;
           }
           this.$input.filter('[name="type"]').val(value.type);
           this.$input.filter('[name="content"]').val(value.content);
       },       
       
       /**
        Returns value of input.
        
        @method input2value() 
       **/          
       input2value: function() { 
           return {
              type: this.$input.filter('[name="type"]').val(), 
              content: this.$input.filter('[name="content"]').val()
           };
       },        
       
        /**
        Activates input: sets focus on the first field.
        
        @method activate() 
       **/        
       activate: function() {
            this.$input.filter('[name="content"]').focus();
       },  
       
       /**
        Attaches handler to submit form in case of 'showbuttons=false' mode
        
        @method autosubmit() 
       **/       
       autosubmit: function() {
           this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
           });
       }       
    });
    
    var typeOptsHTML = '';
    for (var key in typesChoices) {
        typeOptsHTML += '<option value="' + key + '">' + typesChoices[key] + '</option>';
    }
    GalleryItem.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<!--<div class="editable-galleryitem"><select class="form-control" name="type">' + typeOptsHTML + '</select></div>-->'+
             '<div class="editable-galleryitem"><textarea class="form-control" name="content"></textarea></div>',
             
        inputclass: ''
    });

    $.fn.editabletypes.galleryitem = GalleryItem;

}(window.jQuery));