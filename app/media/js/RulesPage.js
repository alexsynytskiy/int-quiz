var RulesPage = function (options) {
    var pageOptions = $.extend(true, {
        acceptAgreementUrl: ''
    }, options);

    var selectors = {
        confirm: '#rules-read-agreement'
    };

    $('body').on("click", selectors.confirm, function (e) {
        e.preventDefault();

        var url = document.location.origin;

        $.when(
            $.ajax({
                url: pageOptions.acceptAgreementUrl,
                type: 'POST',
                data: {_csrf: SiteCore.getCsrfToken()},
                dataType: "json",
                success: function (json) {
                    url += json.profileUrl;
                }
            })
        ).then(function( data, textStatus, jqXHR ) {
            $(location).attr('href', url);
        });
    });
};
