            function limitLines(e, lines) {
                var originalHeight = parseInt(e.css('height')),
                fontSize = parseInt(e.css('font-size')),
                lineHeight = fontSize.toString().replace('px', '') * 1.3,
                a = jQuery(document.createElement('a'));
                if(lineHeight == 1){
                    lineHeight = fontSize.toString().replace('px', '') * 1.3;
                }
                //alert("Height : " + originalHeight + "  LineHeight:" + lineHeight)
                e.css({height : lines * lineHeight, overflow:'hidden'});
                
                a.attr({
                    href : '#', 
                    'class' : 'readMore', 
                    'onclick' : 'javascript: showMore(jQuery(this), ' + originalHeight + ');return false;'
                });
                a.html('read more &hellip;');                
                
                e.after(jQuery('<div>').append(a.clone()).remove().html());
                
            }
            function showMore(e, originalHeight){
                e.prev().css({overflow:"visible"}).css('height', originalHeight);
                e.remove();
                return false;
            }
            jQuery(document).ready(function($){
                limitLines($('.limitLines'), 4);
                $('.postSettings').hide();
                $('.postFeed').hover(function(){$(this).find('.postSettings').fadeIn()}, function(){$(this).find('.postSettings').fadeOut()});
		
            });