/*
 * jQuery Reveal Plugin 1.0
 * www.ZURB.com
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/

     var doc_height = 0;

(function($) {

/*---------------------------
 Defaults for Reveal
----------------------------*/
     
/*---------------------------
 Listener for data-reveal-id attributes
----------------------------*/

/*---------------------------
 Extend and Execute
----------------------------*/

    $.fn.reveal = function(options) {
        
        var defaults = {  
            animation: 'fade', //fade, fadeAndPop, none
            animationspeed: 300, //how fast animtions are
            closeonbackgroundclick: false, //if you click background will modal close?
            dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
        }; 
        
        //Extend dem' options
        var options = $.extend({}, defaults, options); 
    
        return this.each(function() {
        
/*---------------------------
 Global Variables
----------------------------*/
            var modal = $(this),
                topMeasure  = parseInt(modal.css('top')),
                topOffset = modal.height() + topMeasure,
                  locked = false,
                modalBG = $('.reveal-modal-bg');

                
/*---------------------------
 Create Modal BG
----------------------------*/

            if(modalBG == "" || modalBG === undefined || modalBG.length == 0) {
                modalBG = $('<div class="reveal-modal-bg" />').insertAfter(modal);
            }            
     
/*---------------------------
 Open & Close Animations
----------------------------*/
            //Entrance Animations
            modal.bind('reveal:open', function () {
              modalBG.unbind('click.modalEvent');
                $('.' + options.dismissmodalclass).unbind('click.modalEvent');
                if(!locked) {
                    lockModal();
                    if(options.animation == "fadeAndPop") {
                        modal.css({'top': $(document).scrollTop()+$(window).height()/2-topOffset/2, 'opacity' : 0, 'visibility' : 'visible'});
                        modalBG.fadeIn(options.animationspeed/2);
                        modal.delay(options.animationspeed/2).animate({
                            "top": $(document).scrollTop()+$(window).height()/2-topOffset/2 + 'px', //$(document).scrollTop()+topMeasure + 'px',
                            "opacity" : 1
                        }, options.animationspeed,unlockModal());                    
                    }
                    if(options.animation == "fade") {
                        modalBG.css({"display":"block"});    
                        
                        if (modal.hasClass("prw"))
                            w_hg = 458;
                        if (modal.hasClass("wgt"))
                            w_hg = 780;
                            
                        if (w_hg >= doc_height)
                            modal.css('top', '35px');
                        else
                            modal.css('top', $(document).scrollTop() + $(window).height()/2 - w_hg/2);

                        modal.css({'left' : '50%'});
                        modal.css('height', w_hg);

                        //modal.css('display', 'block');
                        //modal.css('behavior', 'url(/new_widget/creditline/css/ie-css3.htc)');
                        
                        
                        modalBG.height($(document).height());
                        unlockModal();
                        /*
                        modal.css({'opacity' : 0, 'visibility' : 'visible', 'top': $(window).height()/2-topOffset/2});
                        modalBG.fadeIn(options.animationspeed/2);
                        modal.delay(options.animationspeed/2).animate({
                            "opacity" : 1
                        }, options.animationspeed,unlockModal());                    
                        */
                    } 
                    if(options.animation == "none") {
                        modal.css({'visibility' : 'visible', 'top':$(document).scrollTop()+topMeasure});
                        modalBG.css({"display":"block"});    
                        unlockModal()                
                    }
                }
                modal.unbind('reveal:open');
            });     

            //Closing Animation
            modal.bind('reveal:close', function () {
              if(!locked) {
                    lockModal();
                    if(options.animation == "fadeAndPop") {
                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        modal.animate({
                            "top":  $(document).scrollTop()-topOffset + 'px',
                            "opacity" : 0
                        }, options.animationspeed/2, function() {
                            modal.css({'top':topMeasure, 'opacity' : 1, 'visibility' : 'hidden'});
                            unlockModal();
                        });                    
                    }      
                    if(options.animation == "fade") {
                            //modal.css({'visibility' : 'hidden', 'top' : topMeasure});
                            modal.css({'left' : '-10000px', 'top' : topMeasure});
                            modalBG.css({'display' : 'none'});    
                            unlockModal();
                    /*
                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        modal.animate({
                            "opacity" : 0
                        }, options.animationspeed, function() {
                            modal.css({'opacity' : 1, 'visibility' : 'hidden', 'top' : topMeasure});
                            unlockModal();
                        });                    
                        */
                    }      
                    if(options.animation == "none") {
                        modal.css({'visibility' : 'hidden', 'top' : topMeasure});
                        modalBG.css({'display' : 'none'});    
                    }        
                }
                modal.unbind('reveal:close');
            });     
       
/*---------------------------
 Open and add Closing Listeners
----------------------------*/
            //Open Modal Immediately
        modal.trigger('reveal:open')
            
            //Close Modal Listeners
            var closeButton = $('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
              modal.trigger('reveal:close')
            });
            
            if(options.closeonbackgroundclick) {
                modalBG.css({"cursor":"pointer"})
                modalBG.bind('click.modalEvent', function () {
                  modal.trigger('reveal:close')
                });
            }
            $('body').keyup(function(e) {
                if(e.which===27){ modal.trigger('reveal:close'); } // 27 is the keycode for the Escape key
            });
            
            
            $('#cl_gotoContinue').click(function(e) {
                modal.trigger('reveal:close');            
                e.preventDefault();
                e.stopPropagation();
            });
            
            
            
/*---------------------------
 Animations Locks
----------------------------*/
            function unlockModal() { 
                locked = false;
            }
            function lockModal() {
                locked = true;
            }    
            
        });//each call
    }//orbit plugin call
})(jQuery);
        
