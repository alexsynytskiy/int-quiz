var canvas;
var context;
var screenH;
var screenW;
var stars = [];
var fps = 10;
var numStars = 4000;
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

$(document).ready(function () {
    'use strict';

    windowSize();
    $(window).resize(function () {
        windowSize();
    });

    setInterval(updateGradient, 10);

    // Calculate the screen size
    screenH = $(document).height();
    screenW = $(document).width();

    // Get the canvas
    canvas = $('#space');

    // Fill out the canvas
    canvas.attr('height', screenH);
    canvas.attr('width', screenW);
    context = canvas[0].getContext('2d');

    // Create all the stars
    for (var i = 0; i < numStars; i++) {
        var x = Math.round(Math.random() * screenW);
        var y = Math.round(Math.random() * screenH);
        var length = 1 + Math.random() * 2;
        var opacity = Math.random();

        // Create a new star and draw
        var star = new Star(x, y, length, opacity);

        // Add the the stars array
        stars.push(star);
    }

    setInterval(animate, 1000 / fps);
});

/**
 * Animate the canvas
 */
function animate() {
    context.clearRect(0, 0, screenW, screenH);
    $.each(stars, function () {
        this.draw(context);
    })
}

/**
 * Star
 *
 * @param x
 * @param y
 * @param length
 * @param opacity
 */
function Star(x, y, length, opacity) {
    this.x = parseInt(x);
    this.y = parseInt(y);
    this.length = parseInt(length);
    this.opacity = opacity;
    this.factor = 1;
    this.increment = Math.random() * .03;
}

/**
 * Draw a star
 *
 * This function draws a start.
 * You need to give the contaxt as a parameter
 *
 */
Star.prototype.draw = function () {
    context.rotate((Math.PI / 10));

    // Save the context
    context.save();

    // move into the middle of the canvas, just to make room
    context.translate(this.x, this.y);

    // Change the opacity
    if (this.opacity > 1) {
        this.factor = -1;
    }
    else if (this.opacity <= 0) {
        this.factor = 1;

        this.x = Math.round(Math.random() * screenW);
        this.y = Math.round(Math.random() * screenH);
    }

    this.opacity += this.increment * this.factor;

    context.beginPath();
    for (var i = 5; i--;) {
        context.lineTo(0, this.length);
        context.translate(0, this.length);
        context.rotate((Math.PI * 2 / 10));
        context.lineTo(0, -this.length);
        context.translate(0, -this.length);
        context.rotate(-(Math.PI * 6 / 10));
    }
    context.lineTo(0, this.length);
    context.closePath();
    context.fillStyle = "rgba(255, 255, 200, " + this.opacity + ")";
    context.shadowBlur = 5;
    context.shadowColor = '#ffff33';
    context.fill();

    context.restore();
};

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

    if (height < $(window).height()) {
        height = '100vh';
    }
    else {
        height += 'px';
    }

    $('#gradient, body').css({
        'min-height': height
    });
}