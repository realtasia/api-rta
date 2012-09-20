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




jQuery.fn.blindToggle = function(speed, easing, callback) {
    var h = this.height() + parseInt(this.css('paddingTop')) + parseInt(this.css('paddingBottom'));
    return this.animate({
        marginTop: parseInt(this.css('marginTop')) <0 ? 0 : -h
    }, speed, easing, callback);
};


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
    
    function qtipStyle(position, cssClass){
        var tooltip = 'bottom', width = 330;
        switch(position){
            case 'bottom':
                tooltip = 'top'
                break;
            case 'left':
                tooltip = 'right';
                break;
            case 'right':
                tooltip = 'left';
                break;                
        }
        
        tooltip += 'Middle';  
        
        if((typeof cssClass == 'string' && cssClass.length > 0) && cssClass == 'large'){
            width = 420;
        }
        
        return {
            width: width,
            padding: 10,
            border: {
                width: 1,
                radius: 3,
                color: '#999'
            },
            tip: tooltip,
            classes:{
                content:'hoverCard ' + cssClass
            },
            name: 'light' // Inherit the rest of the attributes from the preset dark style
        }
    }
    
    //$.fn.qtip.styles.regular = new qtipStyle(position, 'regular');
    var rtaSlider = {
        display : function(obj, direction){
            return  obj.show("slide", {
                direction: direction
            }, 200)
        },
        hideit : function(obj, direction){
            return  obj.hide("slide", {
                direction: direction
            }, 200)
        },
        tooltip : function(content, direction, cssClass, csswidth){
            return {
                content: content,
                show:{
                    effect: function(){
                        return rtaSlider.display($(this), direction.slide)
                    },
                    delay : 800
                //when : 'click'
                },
                hide:'unfocus',
                style: {
                    width: csswidth,
                    padding: 10,
                    border: {
                        width: 1,
                        radius: 3,
                        color: '#999'
                    },
                    tip: direction.tooltip,
                    classes:{
                        content:'hoverCard ' + (typeof cssClass == 'string' && cssClass.length > 0) ? cssClass : ''
                    },
                    name: 'light' // Inherit the rest of the attributes from the preset dark style
                },
                position: {
                    corner: {
                        target: direction.target,
                        tooltip: direction.tooltip
                    },
                    adjust: {
                        resize: true,
                        scroll: true
                    }
                }
            //$('.tipsLikeTest').html()
            }
        }
    }
    tips.each(function(index) {
        var tipContent = $(this).parent().parent().find('.hoverCard'),
        cssClass = tipContent.hasClass('large') ? 'large' : 'regular',
        csswidth = cssClass == 'large' ? '420' : '330',
        direction = {
            tooltip : 'bottom',
            target : 'top',
            slide :'down'
        };
        if ($(this).hasClass('tipsRight')){
            direction.tooltip = 'left';
            direction.target  = 'right';
            direction.slide = 'left';
        }
        if ($(this).hasClass('tipsLeft')){
            direction.tooltip = 'right';
            direction.target  = 'left';
            direction.slide = 'right';
        }
        if ($(this).hasClass('tipsBottom')){
            direction.tooltip = 'top';
            direction.target  = 'bottom';
            direction.slide = 'up';
        }
        direction.tooltip += 'Middle';
        direction.target += 'Middle';
        return $(this)
        .qtip(rtaSlider.tooltip(tipContent.html(), direction, cssClass, csswidth)).click(function(event) {
            event.preventDefault();
            clearTimeout($(this).qtip('api').timers.show);
        }).mouseleave(function(event) {
            clearTimeout($(this).qtip('api').timers.show);
        });
    });
    tips.mouseover();

});