var ProfilePage = function (options) {
    var pageOptions = $.extend(true, {
        startBlockUrl: ''
    }, options);

    var selectors = {
        blockStart: '#block-start',
        block: '.block',
        leftPart: '.left-part',
        rightPart: '.right-part'
    };

    var $blocks = $(selectors.block);

    ($blocks) && $.each($blocks, function (index, value) {
        var leftHeight = $(value).find(selectors.leftPart).height();
        $(value).find(selectors.rightPart).height(leftHeight);
    });

    $('body').on("click", selectors.blockStart, function (e) {
        e.preventDefault();

        var url = document.location.origin;

        $.when(
            $.ajax({
                url: pageOptions.startBlockUrl,
                type: 'POST',
                data: {_csrf: SiteCore.getCsrfToken(), hash: $(this).data('hash')},
                dataType: "json",
                success: function (json) {
                    if (typeof json.message !== 'undefined') {
                        new PNotify({
                            title: json.status === 'error' ? 'Помилка!' : 'Увага!',
                            text: json.message,
                            icon: '',
                            type: json.status,
                            delay: 6000 //Show the notification 4sec
                        });
                    }

                    url += json.answerBlockUrl;
                }
            })
        ).then(function (data, textStatus, jqXHR) {
            $(location).attr('href', url).delay(1000);
        });
    });
};
