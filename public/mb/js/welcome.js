var canvas = null, ctx = null;
var arc = { 'progress': 0.5, 'cx': 0, 'cy': 0 };
var sigProp = { 'rad': 0, 'alpha': 0.1 };

/* Constants */
const RAD_FAC = 0.7;
const RING_COL = '#fefefe';
/* Radar */
const ANIM_DUR = 2.0, FINAL_RAD_FAC = 1.0;
const RAD_ALPHA_INIT = 0.1, RAD_ALPHA_FINAL = 0.0;
/* Spikes */
const ANG_VEL = PI_OVER_TWO / 5000.0, SPIKE_CNT = 55;
const IN_RAD_FAC_0 = 0.58, OUT_RAD_FAC_0 = 0.63;
const IN_RAD_FAC_1 = 0.50, OUT_RAD_FAC_1 = 0.55;
var angOffset0 = 0, angOffset1 = 0;
var spikeAng = PI_TWO / SPIKE_CNT, angVel = ANG_VEL;

var initProgress = function() {
    canvas = document.getElementById('arc');
    if (canvas.getContext)
        ctx = canvas.getContext('2d');

    updateCanvasDimensions();
};

var render = function(duration) {
    /* Get vars */
    updateCanvasDimensions();
    cx = arc['cx']; cy = arc['cy'];
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    /* Render base ring */
    ctx.beginPath();
    ctx.lineWidth = 10.0; ctx.globalAlpha = 0.1;
    ctx.shadowBlur = 40; ctx.lineCap = 'square';
    ctx.shadowColor = RING_COL; ctx.strokeStyle = RING_COL;
    ctx.arc(cx, cy, RAD_FAC * cx, 0, PI_TWO, true);
    ctx.stroke();

    /* Render progress ring */
    ctx.beginPath();
    ctx.lineWidth = 12.0; ctx.globalAlpha = 1.0;
    ctx.arc(cx, cy, RAD_FAC * cx, -PI_OVER_TWO, arc['progress'] * PI_TWO - PI_OVER_TWO, false);
    ctx.stroke();

    /* Render radar */
    ctx.beginPath();
    ctx.lineWidth = 2.0; ctx.shadowBlur = 0.0;
    ctx.globalAlpha = sigProp['alpha'];
    ctx.arc(cx, cy, sigProp['rad'] * cx, 0, PI_TWO, true);
    ctx.stroke();

    /* Render spikes */
    var ang = 0.0, sa, ca;
    if (!duration) duration = 0.0;
    angOffset0 = (angOffset0 + angVel * duration) % PI_TWO;
    angOffset1 = (angOffset1 - ANG_VEL * duration) % PI_TWO;

    ctx.lineCap = 'round';
    for (var i = 0; i < SPIKE_CNT; ++i) {
        ctx.globalAlpha = 0.2;
        ang = angOffset0 + i * spikeAng;
        sa = Math.sin(ang) * cx; ca = Math.cos(ang) * cx;
        
        ctx.beginPath();
        ctx.moveTo(cx + IN_RAD_FAC_0 * ca, cy + IN_RAD_FAC_0 * sa);
        ctx.lineTo(cx + OUT_RAD_FAC_0 * ca, cy + OUT_RAD_FAC_0 * sa);
        ctx.stroke();

        ctx.globalAlpha = 0.1;
        ang = angOffset1 + i * spikeAng;
        sa = Math.sin(ang) * cx; ca = Math.cos(ang) * cx;
        
        ctx.beginPath();
        ctx.moveTo(cx + IN_RAD_FAC_1 * ca, cy + IN_RAD_FAC_1 * sa);
        ctx.lineTo(cx + OUT_RAD_FAC_1 * ca, cy + OUT_RAD_FAC_1 * sa);
        ctx.stroke();
    }
};

/* Day progress */
var daysDiff = function(date1, date2) {
    var ms_day = 1000 * 60 * 60 * 24;

    var ms_date1 = date1.getTime();
    var ms_date2 = date2.getTime();

    return parseInt(Math.abs(ms_date1 - ms_date2) / ms_day);
};

const festDate = new Date(2017, 2, 23);
const initDate = new Date(2017, 0, 1);

var dayProg = null, totDay = null, prevDay;

function recalculateDays() {
    prevDay = new Date();
    totDay = daysDiff(initDate, festDate);
    dayProg = daysDiff(prevDay, festDate);

    /* Get Objects */
    var spanProg = document.getElementById('prog');
    arc['progress'] = (totDay - dayProg) / totDay;
    spanProg.innerHTML = dayProg;
};

function triggerDateUpdate() {
    var curDay = new Date();

    if (curDay.getDay() != prevDay.getDay()) { recalculateDays(); }
}

function toggleSpikeVelocity() { angVel = angVel > 0.0 ? 0.0 : ANG_VEL; }

/* Panel Control */
var orbDtg;

var initPanelControl = function() {
    orbDtg = document.getElementById('orb-dtg');
};

var updateCanvasDimensions = function() {
    var sz = Math.min(orbDtg.clientWidth, orbDtg.clientHeight);
    if (canvas && (canvas.width != sz || canvas.height != sz)) {
        canvas.width = canvas.height = sz;
        arc['cx'] = canvas.width / 2; arc['cy'] = canvas.height / 2;
    }
};

// window.addEventListener('resize', updateCanvasDimensions);

window.addEventListener('load', function() {
    recalculateDays();
    initPanelControl(); initProgress();
    updateCanvasDimensions(); render();

    setInterval(triggerDateUpdate, 1000);
    animate(sigProp, 'rad', RAD_FAC, FINAL_RAD_FAC, 2000, render, toggleSpikeVelocity);
    animate(sigProp, 'alpha', RAD_ALPHA_INIT, RAD_ALPHA_FINAL, 2000, null);
});
