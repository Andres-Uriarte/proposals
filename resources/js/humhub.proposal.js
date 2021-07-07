humhub.module('proposal', function(module, require, $) {

    var init = function() {
        console.log('proposal module activated');
    };

    module.export({
        //uncomment the following line in order to call the init() function also for each pjax call
        //initOnPjaxLoad: true,
        init: init
    });
});
