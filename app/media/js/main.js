$(document).ready(function () {
    'use strict';

    /**
     * updating gradient
     */
    function updateGradient() {
        if ($ === undefined) return;

        var c0_0 = colors[colorIndices[0]],
         c0_1 = colors[colorIndices[1]],
         c1_0 = colors[colorIndices[2]],
         c1_1 = colors[colorIndices[3]];

        var istep = 1 - step,
         r1 = Math.round(istep * c0_0[0] + step * c0_1[0]),
         g1 = Math.round(istep * c0_0[1] + step * c0_1[1]),
         b1 = Math.round(istep * c0_0[2] + step * c0_1[2]),
         color1 = "rgb(" + r1 + "," + g1 + "," + b1 + ")";

        var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]),
         g2 = Math.round(istep * c1_0[1] + step * c1_1[1]),
         b2 = Math.round(istep * c1_0[2] + step * c1_1[2]),
         color2 = "rgb(" + r2 + "," + g2 + "," + b2 + ")";

        $('#gradient').css({
            background: "-webkit-gradient(linear, left bottom, right top, from(" + color1 + "), to(" + color2 + "))"
        }).css({
            background: "-moz-linear-gradient(left, " + color1 + " 0%, " + color2 + " 100%)"
        });

        step += gradientSpeed;
        if (step >= 1) {
            step %= 1;
            colorIndices[0] = colorIndices[1];
            colorIndices[2] = colorIndices[3];

            colorIndices[1] = (colorIndices[1] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;
            colorIndices[3] = (colorIndices[3] + Math.floor(1 + Math.random() * (colors.length - 1))) % colors.length;

        }
    }

    /**
     * window with gradient size preparing
     */
    function windowSize() {
        var height = 80 + $('.steps-block').height() + 310;

        if(height < $( window ).height()) {
            height = '100vh';
        }
        else {
            height += 'px';
        }

        $('#gradient, body').css({
            'min-height': height
        });
    }

    var colors = [
            [159, 108, 199],
            [65, 84, 100],
            [65, 84, 100],
            [69, 214, 173],
            [65, 84, 100],
            [65, 84, 100],
            [159, 108, 199],
            [65, 84, 100],
            [65, 84, 100],
            [69, 214, 173],
            [65, 84, 100],
            [65, 84, 100]
        ],
        step = 0,
        colorIndices = [0, 1, 2, 3],
        gradientSpeed = 0.0005;

    windowSize();
    $(window).resize(function () {
        windowSize();
    });

    setInterval(updateGradient, 10);
});