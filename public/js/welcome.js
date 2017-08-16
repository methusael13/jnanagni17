/**
 * Progress Canvas driver
 * 
 * The MIT License (MIT)
 * 
 * Copyright (c) 2016-17 Methusael Murmu
 * Authors:
 *     Methusael Murmu <blendit07@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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

var spanProg, timerText = null;

var initProgress = function() {
    canvas = document.getElementById('arc');
    if (canvas.getContext)
        ctx = canvas.getContext('2d');

    arc['cx'] = canvas.width / 2; arc['cy'] = canvas.height / 2;
    spanProg = document.getElementById('prog');
    timerText = document.querySelectorAll('.progress#text > .bottom')[0];
    console.log(timerText);
};

var render = function(duration) {
    /* Get vars */
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

    return parseInt((ms_date1 - ms_date2) / ms_day);
};

const festDate = new Date(2017, 2, 23);
const initDate = new Date(2017, 0, 1);
const regCloseTime = new Date(2017, 2, 22, 23, 59, 59);

var dayProg = null, totDay = null, prevDay;

function recalculateDays() {
    prevDay = new Date();
    totDay = daysDiff(festDate, initDate);
    dayProg = daysDiff(festDate, prevDay);

    arc['progress'] = Math.min(Math.max((totDay - dayProg) / totDay, 0), 1);
    if (prevDay.getTime() < festDate.getTime()) {
        if (dayProg == 0) {
            var diffDate = new Date(festDate.getTime() - prevDay.getTime());
            var mins = diffDate.getMinutes(); var hrs = diffDate.getHours();
            if (mins < 10) { mins = '0' + mins; }
            if (hrs < 10) { hrs = '0' + hrs; }

            spanProg.innerHTML = hrs + ':' + mins;
            timerText.innerHTML = 'Hours to go';
        } else {
            spanProg.innerHTML = dayProg;
            timerText.innerHTML = 'Days to go';
        }
    } else {
        switch (dayProg) {
            case 0: case -1: case -2:
                spanProg.innerHTML = 'Day ' + (Math.abs(dayProg) + 1);
                timerText.innerHTML = 'In progress';
                break;
            default:
                spanProg.innerHTML = 'Day 0';
                timerText.innerHTML = '';
        }
    }
};

var disableEventRegistrations = function() {
    if (evtRegBtn.classList.contains('btn-disabled'))
        return;

    evtRegBtn.classList.add('btn-disabled');
    notice.innerHTML =
        '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' +
        '&nbsp&nbspWe\'re no longer accepting event registrations';
    $('#evtd-reg-btn').off();
};

function triggerDateUpdate() {
    if (Date.now() >= regCloseTime.getTime())
        disableEventRegistrations();
    // if (curDay.getDay() != prevDay.getDay())
    recalculateDays();
}

function toggleSpikeVelocity() { angVel = angVel > 0.0 ? 0.0 : ANG_VEL; }

/* Panel Control */
var orbDtg, epCards, titlePanel, evtRegBtn;
var butLaunch, bltp, orbPanel, title, subtitle, linkArea, notice;
var launchEvent = new CustomEvent("launchEvent", {
    detail: { mode: 0  /* 0: Open, 1: Close */ }
});
var launchHTML = [ "translate-right fa-play", "fa-arrow-left" ];

var altEvtPanel;
var altBtnMap = { "flashback-btn": "flashback-alt", "social-btn": "social-alt" };

var setButLaunchHTML = function(mode) {
    butLaunch.innerHTML = "<i class=\"fa " + launchHTML[mode] + "\" " +
                          "aria-hidden=\"true\"></i>";
};

var positionLaunchTooltip = function() {
    // Position tooltip for Launch Button
    bltp = document.getElementById('btn-launch-tooltip');
    if (!bltp) return;

    var blbcr = butLaunch.getBoundingClientRect();
    var bltpbcr = bltp.getBoundingClientRect();
    bltp.style.left = ((blbcr.left + blbcr.width / 2) - bltpbcr.width / 2) + "px";
    bltp.style.top = (blbcr.top - (bltpbcr.height + 15)) + "px";
};

var initPanelControl = function() {
    butLaunch = document.querySelectorAll('.button-launch')[0];
    butLaunch.addEventListener('click', function(event) {
        if (bltp) bltp.remove();
        document.dispatchEvent(launchEvent);
        launchEvent.detail.mode = 1 - launchEvent.detail.mode;
        setButLaunchHTML(launchEvent.detail.mode);
    });
    positionLaunchTooltip();

    orbDtg = document.getElementById('orb-dtg');
    epCards = document.getElementById('ep-cards');
    orbPanel = document.getElementById('orb-panel');
    title = document.getElementById('t-jnanagni');
    subtitle = document.getElementById('t-tfow');
    linkArea = document.querySelectorAll('.link-area')[0];
    titlePanel = document.getElementById('title-panel');
    notice = document.getElementById('evt-notice');

    altEvtPanel = document.querySelectorAll('.alt-evt-panel')[0];
    var lineBtns = document.querySelectorAll('.line-btn');
    for (var i = 0; i < lineBtns.length; i++) {
        lineBtns[i].addEventListener('click', function(e) {
            // If Modal is not active
            if (noClickOverlay.classList.contains('hidden')) {
                // Show modal panel
                noClickOverlay.classList.remove('hidden');
                showObj(altEvtPanel, 'remove-o');
            }

            singleElementSelect(altBtnMap[this.id], 'alt-evt-cnt', 'remove-o');
        });
    }

    evtRegBtn = document.getElementById('evtd-reg-btn');
    evtRegBtn.addEventListener('click', function(e) {
        document.dispatchEvent(evtRegisterEvent);
    });
    evtRegBtn.addEventListener('mouseover', function(e) { updateRegisterBtn(true); });
    evtRegBtn.addEventListener('mouseout', function(e) { updateRegisterBtn(); });

    document.getElementById('alt-evt-back').addEventListener('click', function(e) {
        hideObj(altEvtPanel, 'remove-o');
        noClickOverlay.classList.add('hidden');
    });

    setButLaunchHTML(launchEvent.detail.mode);
};

/* Event Panel Controls */
var epcLeft, epcRight;
var catIDs = [ 'tevent', 'ntevent', 'sevent', 'fevent', 'cevent', 'workshop' ];
var categoryEvent = new CustomEvent("categoryEvent", {
    detail: {
        mode: 0,  /* 0: Open  1: Back to Cat */
        catID: 'tevent',
        imagesLoaded: false
    }
});
var evtDialogEvent = new CustomEvent("evtDialogEvent", {
    detail: { mode: 0, idx: 0 }
});

var evtCardEvents = new Object();
var navData = { idx: 0, length: 0 };

var initCatPanelControl = function() {
    for (var i = catIDs.length - 1; i >= 0; i--) {
        var obj = document.getElementById(catIDs[i]);
        obj.addEventListener('click', function(event) {
            categoryEvent.detail.mode = 0;
            categoryEvent.detail.catID = this.id;

            document.dispatchEvent(categoryEvent);
        });

        evtCardEvents[catIDs[i]] = new CustomEvent("evtCardEvent", {
            detail: {
                mode: 0,
                evtID: catIDs[i] + '-0',
                pEvtID: catIDs[i] + '-0'
            }
        });
    }

    epcLeft = document.getElementById('epc-left'); epcRight = document.getElementById('epc-right');
    epcLeft.addEventListener('click', function(event) {
        if (epcLeft.classList.contains('epc-nav-disable'))
            return;
        document.getElementById('ep-0').classList.remove('remove-ff');
        document.getElementById('ep-1').classList.remove('remove-bf');
        epcLeft.classList.add('epc-nav-disable');
        epcRight.classList.remove('epc-nav-disable');
    });
    epcRight.addEventListener('click', function(event) {
        if (epcRight.classList.contains('epc-nav-disable'))
            return;
        document.getElementById('ep-0').classList.add('remove-ff');
        document.getElementById('ep-1').classList.add('remove-bf');
        epcRight.classList.add('epc-nav-disable');
        epcLeft.classList.remove('epc-nav-disable');
    });

    var edpNavBack = document.querySelectorAll('.edp-nav-but > #nb-back');
    for (var i = edpNavBack.length - 1; i >= 0; i--) {
        edpNavBack[i].addEventListener('click', function(event) {
            categoryEvent.detail.mode = 1;
            document.dispatchEvent(categoryEvent);
        });
    }
};

var initEvtInfoControl = function(id) {
    document.getElementById(id).addEventListener('click', function() {
        var pevdm = document.querySelectorAll('.evtd-menu .active-evdm')[0];
        if (pevdm.id === this.id)
            return;

        pevdm.classList.remove('active-evdm');
        this.classList.add('active-evdm');

        var current = document.getElementById('evti-' + this.id.split('-')[1]);
        var prev = document.querySelectorAll('.evtd-info-content:not(.hidden)')[0];

        hideObj(prev, 'remove-o', true, function() {
            showObj(current, 'remove-o');
        });
    });
};

var initEventPanelControl = function() {
    var evtCards = document.querySelectorAll('.evt-card');

    for (var i = evtCards.length - 1; i >= 0; i--) {
        evtCards[i].addEventListener('click', function(event) {
            var catID = categoryEvent.detail.catID;
            var evtID = this.id.slice(this.id.indexOf('-') + 1);
            if (evtID === evtCardEvents[catID].detail.evtID)
                return;

            evtCardEvents[catID].detail.pEvtID = evtCardEvents[catID].detail.evtID;
            evtCardEvents[catID].detail.evtID = evtID;
            document.dispatchEvent(evtCardEvents[catID]);
        });
    }

    for (var i = catIDs.length - 1; i >= 0; --i) {
        document.getElementById('edb-' + catIDs[i]).addEventListener('click', function(event) {
            evtDialogEvent.detail.mode = 0;
            document.dispatchEvent(evtDialogEvent);
        });
    }

    document.getElementById('evtd-close').addEventListener('click', function() {
        evtDialogEvent.detail.mode = 1;
        document.dispatchEvent(evtDialogEvent);
    });

    document.getElementById('evtdn-left').addEventListener('click', function() {
        evtDialogEvent.detail.mode = 2;
        evtDialogEvent.detail.idx = navData.idx - 1;
        document.dispatchEvent(evtDialogEvent);
    });
    document.getElementById('evtdn-right').addEventListener('click', function() {
        evtDialogEvent.detail.mode = 2;
        evtDialogEvent.detail.idx = navData.idx + 1;
        document.dispatchEvent(evtDialogEvent);
    });

    initEvtInfoControl('evdm-desc'); initEvtInfoControl('evdm-time');
    initEvtInfoControl('evdm-cont'); initEvtInfoControl('evdm-judg');
};

var loadEventImages = function(event) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "evt-img",
        data: { id: event.detail.catID }
    }).done(function(response) {
        $.each(response, function(id, urlStr) {
            document.getElementById(id).style.backgroundImage = 'url(' + urlStr + ')';
        });
    });
};

var revalidateEventNav = function() {
    var el = document.getElementById('evtdn-left'),
        er = document.getElementById('evtdn-right');

    if (navData.idx == 0)
        el.classList.add('epc-nav-disable');
    else if (el.classList.contains('epc-nav-disable'))
        el.classList.remove('epc-nav-disable');

    if (navData.idx == navData.length - 1)
        er.classList.add('epc-nav-disable');
    else if (er.classList.contains('epc-nav-disable'))
        er.classList.remove('epc-nav-disable');
};

var updateRegisterBtn = function(hover) {
    /* Hover state */
    if (hover === true) {
        if (evtRegisterEvent.detail.mode == 2) {
            evtRegBtn.innerHTML =
                '<i class="fa fa-times" aria-hidden="true"></i>&nbsp&nbspUnregister Event';
        }

        return;
    }

    /* Unhovered state */
    if (evtRegisterEvent.detail.mode == 2) {
        evtRegBtn.classList.add('btn-registered');
        evtRegBtn.innerHTML =
            '<i class="fa fa-check" aria-hidden="true"></i>&nbsp&nbspAlready Registered';
    } else {
        evtRegBtn.classList.remove('btn-registered');
        evtRegBtn.innerHTML = 'Registrations closed';
    }
};

var loadEventData = function(cID, eID) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "evt-info",
        data: { catID: cID, evtID: eID },
        beforeSend: function() {
            navData.idx = 0; navData.length = 0;
            revalidateEventNav();
            showObj(document.getElementById('evtd-img-loader'), 'remove-o');
        }
    }).done(function(response) {
        document.getElementById('evtd-title-id').innerHTML = '_' + response['title'] + '_';
        document.getElementById('evta-id').innerHTML = response['title'].charAt(0);
        document.getElementById('evtd-idx-id').innerHTML = response['idx'];
        document.getElementById('evti-desc').innerHTML = response['desc'];
        document.getElementById('evti-judg').innerHTML = response['judg'];
        document.getElementById('evti-time').innerHTML = response['time'];
        document.getElementById('evti-cont').innerHTML = response['cont'];
        document.getElementById('evtd-img').style.backgroundImage = 'url(' + response['img'] + ')';
        evtRegisterEvent.detail.mode = response['regstatus'];
        evtRegisterEvent.detail.evtID = eID;

        navData.idx = response['int-idx'];
        navData.length = response['length'];
        revalidateEventNav(); updateRegisterBtn();
        hideObj(document.getElementById('evtd-img-loader'), 'remove-o');
    });
};

document.addEventListener('evtDialogEvent', function(event) {
    switch (event.detail.mode) {
        case 0:
            navData.idx = 0; navData.length = 0;
            revalidateEventNav();

            loadEventData(
                categoryEvent.detail.catID,
                evtCardEvents[categoryEvent.detail.catID].detail.evtID
            );
            showObj(document.getElementById('evt-detail-dialog-id'), 'remove-slide');
            break;

        case 1:
            hideObj(document.getElementById('evt-detail-dialog-id'), 'remove-slide');
            break;

        case 2:
            loadEventData(
                categoryEvent.detail.catID,
                categoryEvent.detail.catID + '-' + event.detail.idx
            );
            break;
    }
});

document.addEventListener('launchEvent', function(event) {
    switch (event.detail.mode) {
        case 0: /* Open */
            orbDtg.classList.add('animatable-ot');
            hideObj(orbDtg, 'remove-ot', true, function() {
                showObj(epCards, 'remove-ot');
                showObj(document.getElementById('ep-control-id'), 'remove-ot');
            });

            orbPanel.classList.add('grow-flex');
            title.classList.add('no-letter-spacing');
            subtitle.classList.add('no-letter-spacing');
            break;

        case 1: /* Close */
            hideObj(document.getElementById('ep-control-id'), 'remove-ot');
            hideObj(epCards, 'remove-ot', true, function() {
                showObj(orbDtg, 'remove-ot', function() {
                    orbDtg.classList.remove('animatable-ot');
                });
            });

            orbPanel.classList.remove('grow-flex');
            title.classList.remove('no-letter-spacing');
            subtitle.classList.remove('no-letter-spacing');
            break;
    }
});

document.addEventListener('categoryEvent', function(event) {
    switch (event.detail.mode) {
        case 0:
            hideObj(title, 'remove-ot'); hideObj(linkArea, 'remove-ot');
            hideObj(notice, 'remove-ot');
            hideObj(subtitle, 'remove-ot', true, function() {
                hideObj(titlePanel, 'flex-zero', false);
            });

            hideObj(document.getElementById('ep-control-id'), 'remove-ot');
            hideObj(epCards, 'remove-ot', true, function() {
                setTimeout(function() {
                    showObj(document.getElementById('edp-' + event.detail.catID), 'remove-o');
                }, 200);
            });
            hideObj(butLaunch, 'remove-o');

            if (!event.detail.imagesLoaded)
                loadEventImages(event);
            break;

        case 1:
            hideObj(document.getElementById('edp-' + event.detail.catID),
                'remove-o', true, function() {
                showObj(titlePanel, 'flex-zero');
                showObj(epCards, 'remove-ot', function() {
                    showObj(title, 'remove-ot'); showObj(linkArea, 'remove-ot');
                    showObj(notice, 'remove-ot'); showObj(subtitle, 'remove-ot');
                    showObj(butLaunch, 'remove-o');
                    showObj(document.getElementById('ep-control-id'), 'remove-ot');
                });
            });
            break;
    }
});

document.addEventListener('evtCardEvent', function(event) {
    switch (event.detail.mode) {
        case 0:
            hideObj(document.getElementById('et-' + event.detail.pEvtID), 'remove-o', true,
                function() {
                    showObj(document.getElementById('et-' + event.detail.evtID), 'remove-o');
                }
            );

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "evt-story",
                data: { id: event.detail.evtID }
            }).done(function(data) {
                document.getElementById('edp-es-' + categoryEvent.detail.catID).innerHTML = data;
            }).fail(function() {
                document.getElementById('edp-es-' + categoryEvent.detail.catID).innerHTML = data;
            });
            break;
    }
});

var evtRegisterAction = function(_url, evtID) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method: "POST", url: _url,
        data: { id: evtID },
        beforeSend: function() {
            evtRegBtn.classList.add('process-wait');
        }
    }).done(function(data) {
        evtRegisterEvent.detail.mode = data['regstatus'];
        updateRegisterBtn();
    }).always(function(data) {
        evtRegBtn.classList.remove('process-wait');
    });
};

document.addEventListener('evtRegisterEvent', function(event) {
    switch (event.detail.mode) {
        case 0:
            regDialogEvent.detail.mode = 0;
            document.dispatchEvent(regDialogEvent);
            
            evtDialogEvent.detail.mode = 1;
            document.dispatchEvent(evtDialogEvent);
            break;

        case 1:
            evtRegisterAction('evt-reg', event.detail.evtID);
            break;

        case 2:
            evtRegisterAction('evt-unreg', event.detail.evtID);
            break;
    }
});

const zDepth = 2000;
var forward = new Vector3(0, 0, 1);
var mousePos = new Vector3(0, 0, 0);
var transformRegistry = [];

var addMouseMotionControl = function(obj) { transformRegistry.push(obj); };

var rotateTransform = function(obj, mouseVec) {
    var bcr = obj.getBoundingClientRect();
    var center = [
        bcr.left + bcr.width / 2, bcr.top + bcr.height / 2
    ];

    var objPos = new Vector3(center[0], center[1], -zDepth);
    var target = Vector3.sub(mouseVec, objPos);
    target.normalize();

    var axis = Vector3.cross(forward, target);
    var angle = Math.atan2(axis.length(), Vector3.dot(forward, target));
    axis.normalize(); angle *= 180.0 / Math.PI;

    var args = '' + axis.x + ',' + axis.y + ',' + axis.z + ',' + angle + 'deg';
    obj.style.transform = 'rotate3d(' + args + ')';
};

window.addEventListener('resize', positionLaunchTooltip);
window.addEventListener('load', function() {
    initProgress();
    recalculateDays(); render();
    setInterval(triggerDateUpdate, 1000);

    var radAnimator = new Animator(RAD_FAC, FINAL_RAD_FAC, 2000,
        function(val, duration, progress, loopOver) {
            sigProp['rad'] = val; render(duration);
            if (loopOver) toggleSpikeVelocity();
        }, easeOutInterpolator
    );
    var alphaAnimator = new Animator(RAD_ALPHA_INIT, RAD_ALPHA_FINAL, 2000,
        function(val) { sigProp['alpha'] = val; }, easeOutInterpolator
    );
    radAnimator.registerWithLoop(cbackList);
    alphaAnimator.registerWithLoop(cbackList);

    initPanelControl(); initCatPanelControl();
    initEventPanelControl();

    addMouseMotionControl(title); addMouseMotionControl(orbDtg);
    addMouseMotionControl(subtitle);
    addMouseMotionControl(document.getElementById('evtd-menu-id'));

    for (var i = catIDs.length - 1; i >= 0; --i)
        addMouseMotionControl(document.getElementById('edp-es-' + catIDs[i]));

    window.addEventListener('mousemove', function(event) {
        mousePos.x = event.pageX; mousePos.y = event.pageY;
        for (var i = transformRegistry.length - 1; i >= 0; i--)
            rotateTransform(transformRegistry[i], mousePos);
    });

    setTimeout(function() {
        hideObj(document.getElementById('loader-wrapper'), 'remove-o', true, stopLoader);
        document.getElementById('pre-load-bg').remove();
    }, 3000);

    // Renderer-specific behaviours for non-webkit browsers
    if (/(AppleWebKit)\/\d+(\.\d+)*/.exec(navigator.userAgent) === null) {
        // Disable scale transform animations (slow on FF)
        var slides = document.querySelectorAll('.crossfade > .slide');
        console.log(slides.length);
        for (var i = slides.length - 1; i >= 0; --i) {
            if (slides[i].classList.contains('slideshow-anim'))
                slides[i].classList.remove('slideshow-anim');
            slides[i].classList.add('op-slideshow-anim');
        }
    }
}, true);
