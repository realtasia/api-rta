/*
 * Definition 1.01
 * @author Jason Nathan
 * @created 1st Octber 2012
 */

ko.bindingHandlers.timeago = {
    update: function(element, valueAccessor) {
        var value = ko.utils.unwrapObservable(valueAccessor());
        var $this = $(element);
        
        $this.attr('title', value);
        
        if ($this.data('timeago')) {
            var datetime = $.timeago.datetime($this);
            var distance = (new Date().getTime() - datetime.getTime());
            var inWords = $.timeago.inWords(distance);
            
            $this.data('timeago', { 'datetime': datetime });
            $this.text(inWords);
        } else {
            $this.timeago();
        }
    }
};

/**
 * @param obj {Object} the options object that contains writable values
 * @param obs {ko.observable} the observable object that needs updating
 * @returns {ko.observables|Object}
 */
function updateObservables(obj, obs){
    obj = (typeof obj == 'object') ? obj : {};
    for(var key in obs){
        if(typeof obs[key] == 'function'){
            if(obj[key]){
                obs[key](obj[key])
            }
        }else{
            if(obj[key]){
                obs[key] = obj[key];
            }
        }
    }
    return obs;
}
/**
 * @returns {viewModelUser}
 */
function User(opts) {
    opts = (typeof opts == 'object') ? opts : {};
    var optionCounts  = (typeof opts.counts == 'object') ? opts.counts : {},
        optionAvatar  = (typeof opts.avatar == 'object') ? opts.avatar : {},
        optionCropped = (typeof optionAvatar.cropped == 'object') ? optionAvatar.cropped : {};
    
    this.id = opts.id || null;
    this.avatar = {
        cropped: {
            '16' : ko.observable(optionCropped['16'] || '/images/A16.png'),
            '40' : ko.observable(optionCropped['40'] || '/images/A40.png'),
            '90' : ko.observable(optionCropped['90'] || '/images/A90.png'),
            '150' : ko.observable(optionCropped['150'] || '/images/A150.png')
        },
        uncropped: ko.observable(optionAvatar.uncropped || null)
    };
    this.active = ko.observable(opts.active || new Date().toISOString());
    this.joined = ko.observable(opts.active || new Date("October 13, 2010 11:13:00").toISOString());
    this.beta_key = opts.beta_key || null;
    this.birthday = opts.birthday || null;
    this.company = opts.company || null;
    this.counts = {
        attachments : ko.observable(optionCounts['attachments'] || 0),
        comments : ko.observable(optionCounts['comments'] || 0),
        events : ko.observable(optionCounts['events'] || 0),
        likers : ko.observable(optionCounts['likers'] || 0),
        likes : ko.observable(optionCounts['likes'] || 0),
        listings : ko.observable(optionCounts['listings'] || 0),
        locations : ko.observable(optionCounts['locations'] || 0),
        posts : ko.observable(optionCounts['posts'] || 0),
        subscribers : ko.observable(optionCounts['subscribers'] || 0),
        subscriptions : ko.observable(optionCounts['subscriptions'] || 0),
        users : ko.observable(optionCounts['users'] || 0)
    },
    this.roles = ko.observableArray(opts.designation || ['RTUser']);
    this.gender = null;
    this.modified = ko.observable(opts.modified || new Date().toISOString());
    this.email = ko.observableArray(opts.email || null);
    this.first_name = ko.observable(opts.first_name || 'New');
    this.last_name = ko.observable(opts.last_name || 'New');
    this.display_name = ko.computed(function() {
        return this.first_name() + ' ' + this.last_name();
    }, this);
    this.username = opts.username || null;
    this.password = opts.password || null;
    this.status   = opts.status || null;
}

/**
 * @returns {viewModelUser}
 */
function RTR(opts) {
    this.base = User;
    this.base(opts);
    this.roles.push('RTRealtor');
    this.avatar.cropped['16']('NEW AVATAR');
}

RTR.prototype = new User;

ko.applyBindings(new RTR({
    first_name: 'Amy',
    last_name: 'Sylvia'
}));

