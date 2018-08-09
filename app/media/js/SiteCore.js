var SiteCore = function (options) {
    return {
        init: function () {
            this.initPNotify();
        },
        initPNotify: function () {
            var $flashMessages = $('#module-flash-messages').find('.message');

            if ($flashMessages.length) {
                $flashMessages.each(function (i) {
                    var $msg = $(this);

                    new PNotify({
                        title: $msg.data('title'),
                        text: $msg.text(),
                        icon: $msg.data('icon'),
                        type: $msg.data('style'),
                        delay: 6000 //Show the notification 4sec
                    });
                });
            }
        },
        getCsrfToken: function () {
            var token = $('meta[name="csrf-token"]').attr("content");
            return (typeof token != 'undefined' ? token : null);
        }
    }
}();

$(function () {
    SiteCore.init();
});