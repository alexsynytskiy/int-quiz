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
            newQuestion = false,
            newQuestionHtml = '';

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
            var answerId = $answer.data('id');

            $.when(
                $.ajax({
                    url: pageOptions.checkAnswerUrl,
                    type: 'POST',
                    data: {
                        _csrf: SiteCore.getCsrfToken(),
                        questionId: $(selectors.question).data('id'),
                        groupId: $(selectors.question).data('group-id'),
                        answerId: answerId
                    },
                    dataType: "json",
                    success: function (json) {
                        if (typeof json.message !== 'undefined') {
                            new PNotify({
                                title: json.status === 'error' ? 'Помилка!' : 'Успіх!',
                                text: json.message,
                                icon: '',
                                type: json.status,
                                delay: 3000 //Show the notification 4sec
                            });
                        }

                        if (typeof json.blockFinishedUrl !== 'undefined') {
                            url += json.blockFinishedUrl;
                        }

                        if (typeof json.newQuestion !== 'undefined') {
                            newQuestion = true;
                            newQuestionHtml = json.newQuestion;
                        }

                        if (typeof json.isCorrect !== 'undefined' && typeof json.answerCorrectId !== 'undefined') {
                            var $answers = $(selectors.answer);

                            if (json.isCorrect === true) {
                                ($answers) && $.each($answers, function (index, value) {
                                    if ($(value).data('id') === answerId) {
                                        visualizeCorrectAnswer(value);
                                    }
                                });
                            }
                            else {
                                var correctAnswerId = json.answerCorrectId;
                                if (correctAnswerId !== -1) {
                                    ($answers) && $.each($answers, function (index, value) {
                                        if ($(value).data('id') === answerId) {
                                            $(value).addClass('wrong');
                                        }
                                        else if ($(value).data('id') === correctAnswerId) {
                                            visualizeCorrectAnswer(value);
                                        }
                                    });
                                }
                            }
                        }
                    }
                })
            ).then(function (data, textStatus, jqXHR) {
                if (!newQuestion) {
                    setTimeout (function(){
                        $(location).attr('href', url);
                    }, 4000);
                }
                else {
                    setTimeout (function(){
                        $(selectors.questionWrapper).html(newQuestionHtml);
                    }, 4000);
                }
            });
        }
    });

    var countDownDate = new Date(pageOptions.expiringAt).getTime();

    var x = setInterval(function () {
        var now = Math.floor(new Date().getTime() / 1000);
        var distance = countDownDate - now;

        var minutes = Math.floor((distance % (60 * 60)) / 60);
        var seconds = Math.floor(distance % (60));

        document.getElementById("time-value").innerHTML = (minutes < 10 ? '0' + minutes : minutes) + ":"
            + (seconds < 10 ? '0' + seconds : seconds);

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("time-value").innerHTML = "Час вийшов!";
        }
    }, 1000);

    function visualizeCorrectAnswer(value) {
        var count = 0,
            $div = $(value),
            interval = setInterval(function () {
                if ($div.hasClass('correct')) {
                    $div.removeClass('correct');
                    ++count;
                }
                else
                    $div.addClass('correct');

                if (count === 4) {
                    $div.addClass('correct');
                    clearInterval(interval);
                };
            }, 200);
    }
};
