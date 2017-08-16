
var requestAnimationFrame =
    window.requestAnimationFrame || window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame || window.msRequestAnimationFrame ||
    window.oRequestAnimationFrame;

var animate = function(key, val, vi, vf, duration, cbRenderFrame, cbProgressEnd) {
    var t0 = 0, progress, elapsed, tprev = 0;
    var step = function(timestamp) {
        elapsed = timestamp - t0;
        // Interpolation calculations here
        progress = elapsed / duration;

        key[val] = vi + progress * (vf - vi);
        if (progress >= 0.988) {
            t0 = timestamp;
            if (cbProgressEnd) cbProgressEnd();
        }

        if (cbRenderFrame)
            cbRenderFrame(timestamp - tprev, progress);
        if (key['stop'] === undefined || !key['stop'])
            requestAnimationFrame(step);
        tprev = timestamp;
    };

    requestAnimationFrame(step);
};

var loader = null, loaderText, lCtxt;
var config = { 'rad': 0.8, 'progress': 0, 'cx': 0, 'cy': 0, 'stop': false };

const PI_TWO = 2 * Math.PI;
const PI_OVER_TWO = Math.PI / 2;

var initLoader = function() {
    if (!loader.getContext) return;

    lCtxt = loader.getContext("2d");
    config['cx'] = loader.width / 2; config['cy'] = loader.height / 2;
    loaderText = document.getElementById('loader-text');
};

var stopLoader = function () { config['stop'] = true; };

var loaderRender = function(duration, progress) {
    var cx = config['cx'], cy = config['cy'];
    var rad = config['rad'] * cx;
    lCtxt.clearRect(0, 0, loader.width, loader.height);

    // Render faded ring
    lCtxt.beginPath();
    lCtxt.lineWidth = 1.5; lCtxt.globalAlpha = 0.1;
    lCtxt.strokeStyle = '#ffffff';
    lCtxt.arc(cx, cy, rad, 0, PI_TWO, true);
    lCtxt.stroke();

    // Render point
    var ang = progress * PI_TWO - PI_OVER_TWO;
    var sang = Math.sin(ang), cang = Math.cos(ang);

    lCtxt.beginPath(); lCtxt.strokeStyle = '#ffffff';
    lCtxt.globalAlpha = 0.9;
    lCtxt.arc(cx, cy, rad, -PI_OVER_TWO, ang, false);
    lCtxt.stroke();

    lCtxt.beginPath(); lCtxt.fillStyle = '#ffffff';
    lCtxt.globalAlpha = 1.0;
    lCtxt.arc(cx + cang * rad, cy + sang * rad, 5, 0, PI_TWO, true);
    lCtxt.fill();
};

const lText = 'Jñānāgni';
var updateLoad = function(duration, progress) {
    var idx0 = parseInt(progress * lText.length);

    var text = '';
    for (var i = 0; i < lText.length; ++i) {
        text += i <= idx0 ? lText.charAt(i) :
                String.fromCharCode(26 * Math.random() + 65);
    }

    loaderText.innerHTML = text;
    loaderRender(duration, progress);
};

function startLoader() {
    initLoader();
    animate(config, 'progress', 0, PI_TWO, 5000, updateLoad);
};

(function monitor() {
    setTimeout(function() {
        loader = document.getElementById("loader");
        if (!loader) monitor();
        else startLoader();
    }, 250);
})();
