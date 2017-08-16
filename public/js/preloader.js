
var requestAnimationFrame =
    window.requestAnimationFrame || window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame || window.msRequestAnimationFrame ||
    window.oRequestAnimationFrame;

/* Animation Utilities */
/* CallbackList object */
function CallbackList() {
    this.cbMap = [];
    this.callbacks = [];
};

CallbackList.prototype = {
    add: function(callback) {
        this.cbMap.push(true);
        this.callbacks.push(callback);

        return this.cbMap.length - 1;
    },

    setIterable: function(idx, itr) { this.cbMap[idx] = itr; },
    getList: function() { return this.callbacks; },

    execList: function(duration) {
        for (var i = this.cbMap.length - 1; i >= 0; i--) {
            if (this.cbMap[i])
                (this.callbacks[i])(duration);
        }
    }
};

var cbackList = new CallbackList();

function Animator(vi, vf, duration, callback, inp) {
    this.vi = vi; this.vf = vf;
    this.duration = duration; this.callback = callback;
    this.elapsedTime = 0; this.progress = 0;
    this.animIdx = 0;

    this.tick = function(dur) {
        this.elapsedTime += dur;
        this.progress = this.elapsedTime / this.duration;

        var outProgress = inp ? inp(this.progress) : this.progress;
        var val = vi + (vf - vi) * outProgress;
        var loopOver = outProgress >= 0.9833;
        
        if (loopOver) this.elapsedTime = 0;
        this.callback(val, dur, outProgress, loopOver);
    };

    this.registerWithLoop = function(cbList) {
        this.animIdx = cbList.add(this.tick.bind(this));
    };

    this.stop = function(cbList) { cbList.setIterable(this.animIdx, false); };
    this.resume = function(cbList) { cbList.setIterable(this.animIdx, true); };
}

var triggerAnimationLoop = function() {
    var ctime, ptime;
    ctime = ptime = Date.now();

    var step = function() {
        ctime = Date.now();
        cbackList.execList(ctime - ptime);
        ptime = ctime;

        requestAnimationFrame(step);
    };

    step();
};

/* Interpolators */
const LOG_16 = Math.log(16);
/* @param progress = [0, 1] */
var easeOutInterpolator = function(progress) {
    return Math.log(15 * progress + 1) / LOG_16;
};
var easeInInterpolator = function(progress) {
    return 1 - easeOutInterpolator(progress);
};
/* End of Animation Utilities */

var loader = null, loaderText, lCtxt;
var cirConfig = new Array(5);
var config = {
    'rad': 0.7, 'drad': 0.1,
    'progress': 0, 'cx': 0, 'cy': 0,
    'line-width': 2
}, loadAnimator;

const PI_TWO = 2 * Math.PI;
const PI_OVER_TWO = Math.PI / 2;

var initLoader = function() {
    if (!loader.getContext) return;

    lCtxt = loader.getContext("2d");
    config['cx'] = loader.width / 2; config['cy'] = loader.height / 2;
    loaderText = document.getElementById('loader-text');

    var minav = PI_TWO / 6000.0; maxav = PI_TWO / 2000.0;
    for (var i = 0; i < cirConfig.length; ++i) {
        cirConfig[i] = new Object();
        cirConfig[i]['theta0'] = i * PI_TWO / cirConfig.length;
        cirConfig[i]['angv'] = (maxav - minav) * Math.random() + minav;
        cirConfig[i]['theta'] = cirConfig[i]['theta0'];
    }
};

var loaderRender = function(duration, progress) {
    var lwidth = config['line-width'];
    var cx = config['cx'], cy = config['cy'];
    var rad = config['rad'] * cx, drad = config['drad'] * cx;
    lCtxt.clearRect(0, 0, loader.width, loader.height);

    var cxn, cyn;
    for (var i = 0; i < cirConfig.length; ++i) {
        lCtxt.beginPath();
        lCtxt.lineWidth = lwidth; lCtxt.globalAlpha = 0.5;
        lCtxt.strokeStyle = '#ffffff';
        lCtxt.shadowBlur = i % 2 == 0 ? 20 : 0;
        lCtxt.shadowColor = lCtxt.strokeStyle;

        cirConfig[i]['theta'] = 
            (cirConfig[i]['theta'] + cirConfig[i]['angv'] * duration) % PI_TWO;
        cxn = cx + drad * Math.cos(cirConfig[i]['theta']);
        cyn = cy + drad * Math.sin(cirConfig[i]['theta']);

        lCtxt.arc(cxn, cyn, rad, 0, PI_TWO, true);
        lCtxt.stroke();
    }
};

const lText = 'Jñānāgni';
var updateLoad = function(val, duration, progress) {
    var idx0 = parseInt(progress * lText.length);

    var text = '';
    for (var i = 0; i < lText.length; ++i) {
        text += i <= idx0 ? lText.charAt(i) :
                String.fromCharCode(26 * Math.random() + 65);
    }

    loaderText.innerHTML = text;
    loaderRender(duration, progress);
};

var startLoader = function() {
    initLoader();
    loadAnimator = new Animator(0, PI_TWO, 5000, updateLoad);
    loadAnimator.registerWithLoop(cbackList);

    triggerAnimationLoop();
};

var stopLoader = function() { loadAnimator.stop(cbackList); };

(function monitor() {
    setTimeout(function() {
        loader = document.getElementById("loader");
        if (!loader) monitor();
        else startLoader();
    }, 250);
})();
