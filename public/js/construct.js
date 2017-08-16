
/**
 * Driver for handling page SVGs
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

var RingParams = {
    maxAngleSweep: 90,
    animDuration: 3000,
};

var logo = null, logoRing = null;
var loadEvent = new CustomEvent("loadedEvent");

function init() {
    var svg = Snap("#svg");
    logo = svg.group(); logoRing = svg.group();

    Snap.load("res/images/logo.svg", function(fragment) {
        logoRing = fragment.select("#gring");
        logo.append(fragment);

        // Done loading
        document.dispatchEvent(loadEvent);
    });
}

function rotateLogoRing(ccw) {
    var sAng = ccw ? 0 : RingParams.maxAngleSweep;

    logoRing.transform('r' + sAng + 't0,0');
    logoRing.animate({ transform: 'r' + (RingParams.maxAngleSweep - sAng) + 't0,0' },
        RingParams.animDuration, mina.bounce, function() { rotateLogoRing(!ccw); });
}

document.addEventListener("loadedEvent",
    function(event) { rotateLogoRing(true); }, false);
window.addEventListener("load", init, true);
