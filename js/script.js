function CurrentUser(){

}

function User (){

}

function Like(){

}

function Subscribe(){

}

function Comment(){

}

function Post(){

}

function Testimonial(){

}

function Listing(){

}

function Upload(){

}

function Tag(){

}



function limitLines(e, lines) {
    var originalHeight = parseInt(e.css('height')),
    fontSize = parseInt(e.css('font-size')),
    lineHeight = fontSize.toString().replace('px', '') * 1.3,
    a = jQuery(document.createElement('a'));
    if(lineHeight == 1){
        lineHeight = fontSize.toString().replace('px', '') * 1.3;
    }
    //alert("Height : " + originalHeight + "  LineHeight:" + lineHeight)
    e.css({
        height : lines * lineHeight,
        overflow:'hidden'
    });

    a.attr({
        href : '#',
        'class' : 'readMore',
        'onclick' : 'javascript: showMore(jQuery(this), ' + originalHeight + ');return false;'
    });
    a.html('read more &hellip;');

    e.after(jQuery('<div>').append(a.clone()).remove().html());

}
function showMore(e, originalHeight){
    e.prev().css({
        overflow:"visible"
    }).css('height', originalHeight);
    e.remove();
    return false;
}







jQuery(document).ready(function($){
    $.expr[':'].isIconClass = function(obj){
        return (/\bi-/).test(obj.className);
    };
    limitLines($('.limitLines'), 4);
    
    var unApproved = $(".unApproved");
    
    unApproved.children().fadeTo('fast', 0.6);
    unApproved.children('.postActions').hide();

    $('.postFeed:not(".editMode")').children('.postSettings').hide();
    $('.postFeed:not(".editMode")').hover(function(){
        $(this).find('.postSettings').fadeIn()
    }, function(){
        $(this).find('.postSettings').fadeOut()
    });

    $('#cancelSearch').click(function(e){
        e.preventDefault();
        $('#drop-down-filter').slideUp();
    });
    $('#searchInput').focus(function(e){
        e.preventDefault();
        $('#drop-down-filter').slideDown();
    });
    $('*[class^="i-"]').hover(function(){
        console.log($(this).attr('class'))
    });

    var isHovering = false;

    $("*:isIconClass").on({

        mouseenter: function(){
            var icon = $(this);
            if(!isHovering){ 
                isHovering = true;               
                
                if(!icon.hasClass('Active') || !icon.hasClass('Deactive')){
                    icon.addClass('Hover');
                }
                
                if(icon.hasClass('Active')){
                    icon.removeClass('Active').removeClass('Hover').addClass('Deactive');
                }                
            }
            
            return false;
        },

        mouseleave: function(){
            isHovering = false;
            var icon = $(this);
            icon.removeClass('Hover'); 
            if(icon.hasClass('Deactive')){
                icon.removeClass('Deactive').removeClass('Hover').addClass('Active');
            } 
            return false;

        }
    }).parent('a').on({
        mouseenter: function(){
            var icon = $(this).find('i');
            
            if(!isHovering){
                isHovering = true;
            
                if(icon.hasClass('Active')){
                    icon.removeClass('Active').removeClass('Hover').addClass('Deactive');
                }
                
                if(!icon.hasClass('Active') || !icon.hasClass('Deactive')){
                    icon.addClass('Hover');
                }
            }
            return false;
        }, //notice the comma..

        mouseleave: function(){
            var icon = $(this).find('i');
            isHovering = false;

            icon.removeClass('Hover'); 

            if(icon.hasClass('Deactive')){
                icon.removeClass('Deactive').removeClass('Hover').addClass('Active');
            }
            return false;
        }

    });
    var tips = $('.tipsTest');        
    tips.each(function(index) {
        var direction = 'top';
        if ($(this).hasClass('tipsRight')){
            direction = 'right';
        }
        if ($(this).hasClass('tipsLeft')){
            direction = 'left';
        }     
        if ($(this).hasClass('tipsBottom')){
            direction = 'bottom';
        }         
        var tipContent = $(this).parent().find('.hoverCard');
        return $(this).bubbletip(tipContent, {
            bindHide:false,
            deltaDirection:direction
        });
    });
   
    
    tips.mouseover();

});