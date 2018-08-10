var AnswerPage = function (options) {
    var pageOptions = $.extend(true, {
        questionId: '',
        checkAnswerUrl: ''
    }, options);

    var selectors = {
        answer: '.answer',
        question: '.question',
        confirm: '#submit-answer'
    };

    var selection = {state: true, answerId: null, questionId: null};

    $('body').on("mousedown", selectors.answer, function (e) {
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');

            selection.state = false;
        }

        $(this).addClass('pressed');
    }).on("mouseup", selectors.answer, function () {
        if(!selection.state) {
            $(this).removeClass('selected');
            selection.state = true;
        }
        else {
            var $answers = $(selectors.answer);

            ($answers) && $.each($answers, function (index, value) {
                if($(value).hasClass('selected')) {
                    $(value).removeClass('selected');
                }
            });

            $(this).addClass('selected');
        }

        $(this).removeClass('pressed');
    }).on("click", selectors.confirm, function (e) {
        e.preventDefault();

        $.ajax({
            url: pageOptions.checkAnswerUrl,
            type: 'POST',
            data: {
                _csrf: SiteCore.getCsrfToken(),
                questionId: $(selectors.question).data('id'),
                answerId: $(selectors.answer + '.selected').data('id')
            },
            dataType: "json",
            success: function (json) {
                new PNotify({
                    title: 'Готово!',
                    text: json.message,
                    icon: '',
                    type: json.status,
                    delay: 8000 //Show the notification 4sec
                });
            }
        });
    });
};
