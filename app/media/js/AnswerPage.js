var AnswerPage = function (options) {
    var pageOptions = $.extend(true, {
        questionId: '',
        checkAnswerUrl: '',
        expiringAt: ''
    }, options);

    var selectors = {
        answer: '.answer',
        question: '.question',
        confirm: '#submit-answer',
        questionWrapper: '#question-wrapper'
    };

    var selection = {state: true, answerId: null, questionId: null};

    $('body').on("mousedown", selectors.answer, function (e) {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

            selection.state = false;
        }

        $(this).addClass('pressed');
    }).on("mouseup", selectors.answer, function () {
        if (!selection.state) {
            $(this).removeClass('selected');
            selection.state = true;
        }
        else {
            var $answers = $(selectors.answer);

            ($answers) && $.each($answers, function (index, value) {
                if ($(value).hasClass('selected')) {
                    $(value).removeClass('selected');
                }
            });

            $(this).addClass('selected');
        }

        $(this).removeClass('pressed');
    }).on("click", selectors.confirm, function (e) {
        e.preventDefault();

        var url = document.location.origin,
            newQuestion = false;

        var $answer = $(selectors.answer + '.selected');

        if ($answer.size() !== 1) {
            new PNotify({
                title: 'Помилка!',
                text: 'Можна та необхідно обрати лише 1 варіант відповіді',
                icon: '',
                type: 'error',
                delay: 6000 //Show the notification 4sec
            });
        }
        else {
            $.when(
                $.ajax({
                    url: pageOptions.checkAnswerUrl,
                    type: 'POST',
                    data: {
                        _csrf: SiteCore.getCsrfToken(),
                        questionId: $(selectors.question).data('id'),
                        groupId: $(selectors.question).data('group-id'),
                        answerId: $answer.data('id')
                    },
                    dataType: "json",
                    success: function (json) {
                        if (typeof json.message !== 'undefined') {
                            new PNotify({
                                title: json.status === 'error' ? 'Помилка!' : 'Успіх!',
                                text: json.message,
                                icon: '',
                                type: json.status,
                                delay: 6000 //Show the notification 4sec
                            });
                        }

                        if (typeof json.blockFinishedUrl !== 'undefined') {
                            url += json.blockFinishedUrl;
                        }

                        if (typeof json.newQuestion !== 'undefined') {
                            newQuestion = true;

                            $(selectors.questionWrapper).html(json.newQuestion).delay(1000);
                        }
                    }
                })
            ).then(function (data, textStatus, jqXHR) {
                if (!newQuestion) {
                    $(location).attr('href', url).delay(1000);
                }
            });
        }
    });

    var countDownDate = new Date(pageOptions.expiringAt).getTime();

    var x = setInterval(function() {
        var now = Math.floor(new Date().getTime() / 1000);
        var distance = countDownDate - now;

        console.log(distance);

        var minutes = Math.floor((distance % (60 * 60)) / 60);
        var seconds = Math.floor(distance % (60));

        document.getElementById("time-value").innerHTML = (minutes < 10 ? '0' + minutes : minutes) + ":"
            + (seconds < 10 ? '0' + seconds : seconds);

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("time-value").innerHTML = "Час вийшов!";
        }
    }, 1000);
};
