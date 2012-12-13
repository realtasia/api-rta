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

var RTA = {
    Utils : function(){

    },
    Handlers : function(){
        var DOM = $(document.body);
        DOM.on("hover", ".hasHoverState", function(event) {
            event.stopPropagation();
            var self = $(this);
            //console.log(!self.hasClass('active'))
            if(!self.hasClass('active')){
                if(self.find('a').hasClass('disabled')){
                    self.removeClass('hovering');
                }else{
                    self.toggleClass('hovering');
                }

            }
            return true;
        }).on("click", ".hasActiveState", function(event){
            var self = $(this), isNavigation = self.hasClass('isNavigation');
            if(!self.find('a').hasClass('disabled')){
                if(isNavigation){
                    $('.hasActiveState').each(function(index){
                        $(this).removeClass('active').removeClass('hovering');
                    })
                }
            }
            if(isNavigation){
                self.addClass('active');
                self.removeClass('hovering');
            }else{
                if(self.hasClass('active')){
                    self.removeClass('active');
                    self.addClass('hovering');
                }else{
                    self.addClass('active');
                    self.removeClass('hovering');
                }
            }
            return false;
        });
    }
}

function MenuItem(label, css, state, callback){
    var self  = this;
    this.hovering = ko.observable(false);
    this.active = ko.observable(false);
    this.disabled = ko.observable(false);
    if(typeof state === 'object'){
        for(var key in state){
            if(typeof state[key] === 'function'){
                self[key](state[key].call(this));
            }
            self[key](state[key]);
        }
    }
    this.css   = css || {
        iconClass : ['icon'],
        parentClass : null
    };
    this.label = label;
    this.id    = 'RTA_MenuItem_' + label;
    if(typeof callback === 'function'){
        this.callback = callback;
    }
}

function Library(){
    var self  = this, icon = 'social i-';

    this.loadUpload = function(){
        console.log(ko.contextFor(this));
        return false;
    };
    this.loadRTA = function(){
        //console.log(ko.contextFor(this));
        return false;
    }
    this.menu = [
    new MenuItem('Realtasia', {
        iconClass : icon + 'libRTA'
    }, {}, this.loadRTA),
    new MenuItem('Facebook', {
        iconClass : icon + 'libFB'
    }, {
        disabled : true
    }),
    new MenuItem('YouTube', {
        iconClass : icon + 'libYT'
    }, {
        disabled : true
    }),
    new MenuItem('Flickr', {
        iconClass : icon + 'libFlickr'
    }, {
        disabled : true
    }),
    new MenuItem('Picasa', {
        iconClass : icon + 'libRTA'
    }, {
        disabled : true
    }),
    new MenuItem('Upload', {
        iconClass : icon + 'libRTA',
        parentClass : 'rightMenuItem'
    }, {}, this.loadUpload),
    ];



}

function CreateListing(){
    var self  = this, icon = 'listingCreation i-', id    = 'RTA_MenuItem_';

    this.currentStep    = ko.observable(0);
    this.totalSteps     = 5;
    this.completedSteps = ko.observableArray();
    this.isStepComplete = function(step){
        if(step <= this.totalSteps){
            var inArr = jQuery.inArray(step, this.completedSteps());
            inArr =  inArr < 0 ? false : true;
        }
        return false;
    }
    this.isListingStep = ko.computed(function(){
        return this.currentStep() >=3
    }, this)

    this.goBack = function(){

        if(self.currentStep() - 1 >= 0){
            self.currentStep(self.currentStep() - 1)
        }
        return false;
    };
    this.enableNext = function(){
        console.log(self.isStepComplete(self.currentStep() + 1) && self.currentStep() == self.totalSteps)
        return self.isStepComplete(self.currentStep() + 1) || self.currentStep() == self.totalSteps;
    };
    this.resetSteps = function(){
        self.currentStep(0);
        self.completedSteps.removeAll();
        console.log(self.currentStep())
        return false;
    };
    this.save = function(){
        console.log(ko.contextFor(this));
        return false;
    };
    this.publish = function(){
        console.log(ko.contextFor(this));
        return false;
    };
    this.cancel = function(){
        console.log(ko.contextFor(this));
        return false;
    };
    this.progressStep = function(){
        var complete = self.currentStep() + 1;
        if(complete <= self.totalSteps){
            self.currentStep(complete);
            self.completedSteps.push(complete);
        }
    }
}



jQuery.fn.blindToggle = function(speed, easing, callback) {
    var h = this.height() + parseInt(this.css('paddingTop')) + parseInt(this.css('paddingBottom'));
    return this.animate({
        marginTop: parseInt(this.css('marginTop')) <0 ? 0 : -h
    }, speed, easing, callback);
};

ko.bindingHandlers.class = {
    'update': function(element, valueAccessor) {
        if (element['__ko__previousClassValue__']) {
            ko.utils.toggleDomNodeCssClass(element, element['__ko__previousClassValue__'], false);
        }
        var value = ko.utils.unwrapObservable(valueAccessor());
        ko.utils.toggleDomNodeCssClass(element, value, true);
        element['__ko__previousClassValue__'] = value;
    }
};


ko.bindingHandlers.checkedButtons = {
    'init': function (element, valueAccessor, allBindingsAccessor) {
        var type = element.getAttribute('data-toggle') || 'radio';
        var updateHandler = function () {
            var valueToWrite;
            var isActive = !!~element.className.indexOf('active');
            var dataValue = element.getAttribute('data-value');
            if (type == "checkbox") {
                valueToWrite = !isActive;
            } else if (type == "radio" && !isActive) {
                valueToWrite = dataValue;
            } else {
                return; // "checkedButtons" binding only responds to checkbox and radio data-toggle attribute
            }

            var modelValue = valueAccessor();
            if ((type == "checkbox") && (ko.utils.unwrapObservable(modelValue) instanceof Array)) {
                // For checkboxes bound to an array, we add/remove the checkbox value to that array
                // This works for both observable and non-observable arrays
                var existingEntryIndex = ko.utils.arrayIndexOf(ko.utils.unwrapObservable(modelValue), dataValue);
                if (!isActive && (existingEntryIndex < 0))
                    modelValue.push(dataValue);
                else if (isActive && (existingEntryIndex >= 0))
                    modelValue.splice(existingEntryIndex, 1);
            } else {
                if (modelValue() !== valueToWrite) {
                    modelValue(valueToWrite);
                }
            }
        };

        ko.utils.registerEventHandler(element, "click", updateHandler);
    },
    'update': function (element, valueAccessor) {
        var value = ko.utils.unwrapObservable(valueAccessor());
        var type = element.getAttribute('data-toggle') || 'radio';

        if (type == "checkbox") {
            if (value instanceof Array) {
                // When bound to an array, the checkbox being checked represents its value being present in that array
                if (ko.utils.arrayIndexOf(value, element.getAttribute('data-value')) >= 0) {
                    ko.utils.toggleDomNodeCssClass(element, 'active', true);
                }
                else {
                    ko.utils.toggleDomNodeCssClass(element, 'active', false);
                }

            } else {
                // When bound to anything other value (not an array), the checkbox being checked represents the value being trueish
                ko.utils.toggleDomNodeCssClass(element, 'active', value);
            }
        } else if (type == "radio") {
            ko.utils.toggleDomNodeCssClass(element, 'active', element.getAttribute('data-value') == value);
        }
    }
};

function ajaxImg(size){
    return jQuery(document.createElement('i')).addClass('ajax' + size);
}


var CheckedButtons = function() {
    this.radioValue = ko.observable(0);
    this.checkboxArray = ko.observableArray([]);
    this.checkboxValue = ko.observable(true);
}
//ko.applyBindings(new CheckedButtons());

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
    RTA.Handlers();
    ko.applyBindings(new Library(), document.getElementById('mediaLibrary'));
    ko.applyBindings(new CreateListing(), document.getElementById('listingCreationWizard'));
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
                radius: 1,
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
                        width: 2,
                        radius: 1,
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