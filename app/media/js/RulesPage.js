var RulesPage = function (options) {
    var pageOptions = $.extend(true, {
        acceptAgreementUrl: '',
        profileUrl: ''
    }, options);

    var selectors = {
        confirm: '#rules-read-agreement'
    };

    $('body').on("click", selectors.confirm, function (e) {
        e.preventDefault();

        $.ajax({
            url: pageOptions.acceptAgreementUrl,
            type: 'POST',
            data: {_csrf: SiteCore.getCsrfToken()},
            dataType: "json",
            success: function (json) {
                $(location).attr('href', document.location.origin + pageOptions.profileUrl);
            }
        });
    });
};
