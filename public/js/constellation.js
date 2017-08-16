/**
 * Constellation (Star cluster) Animator
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

// Point object
function Point(px, py, dx, dy) {
    this.x = px; this.y = py;
    this.dx = dx; this.dy = dy;
};

Point.prototype = {
    move: function(duration, domain) {
        this.x += this.dx * duration;
        this.y += this.dy * duration;

        if (this.x < 0) { this.x = 0; this.dx = -this.dx; }
        else if (this.x > domain.width) { this.x = domain.width; this.dx = -this.dx; }
        if (this.y < 0) { this.y = 0; this.dy = -this.dy; }
        else if (this.y > domain.height) { this.y = domain.height; this.dy = -this.dy; }
    }
};
 
function Constellation(quantity, canvas, context) {
    this.domain = canvas;
    this.quantity = quantity;
    this.renderContext = context;
    this.config = {
        'maxVelocity': 0.03, 'minRadarRange': 80,
        'maxRadarRange': 180, 'opacity': 0.08
    };
    this.radarRange = this.config['maxRadarRange'] - this.config['minRadarRange'];

    this.points = new Array(quantity);
    for (var i = quantity - 1; i >= 0; --i) {
        this.points[i] = new Point(
            Math.random() * this.domain.width,
            Math.random() * this.domain.height,
            Math.random() * this.config['maxVelocity'] * (Math.random() < 0.5 ? -1 : 1),
            Math.random() * this.config['maxVelocity'] * (Math.random() < 0.5 ? -1 : 1)
        );
    }
};

Constellation.prototype = {
    constelRender: function(ctxt, duration) {
        var opacity = this.config['opacity'];
        ctxt.clearRect(0, 0, this.domain.width, this.domain.height);

        for (var i = this.quantity - 1; i >= 0; --i) {
            ctxt.beginPath(); ctxt.fillStyle = '#ffffff';
            ctxt.globalAlpha = opacity;
            ctxt.arc(this.points[i].x, this.points[i].y, 3, 0, PI_TWO);
            ctxt.fill();
        }

        // Radar lines
        var dist = 0, pdx, pdy;
        var maxr = this.config['maxRadarRange'];

        for (var i = this.quantity - 1; i >= 1; --i) {
            for (var j = i - 1; j >= 0; --j) {
                pdx = Math.abs(this.points[i].x - this.points[j].x);
                pdy = Math.abs(this.points[i].y - this.points[j].y);

                // Soft test
                if (pdx <= maxr && pdy <= maxr) {
                    // Hard test
                    dist = Math.sqrt(pdx*pdx + pdy*pdy);
                    if (dist > maxr) continue;

                    ctxt.beginPath(); ctxt.strokeStyle = '#ffffff';
                    ctxt.globalAlpha =
                        Math.min(Math.max((maxr - dist) / this.radarRange, 0), 1) * opacity;
                    ctxt.lineCap = 'round'; ctxt.lineWidth = 1.0;
                    ctxt.moveTo(this.points[i].x, this.points[i].y);
                    ctxt.lineTo(this.points[j].x, this.points[j].y);
                    ctxt.stroke();
                }
            }
        }
    },

    update: function(duration) {
        for (var i = this.quantity - 1; i >= 0; --i)
            this.points[i].move(duration, this.domain);
        this.constelRender(this.renderContext, duration);
    }
};

var constellation;
var constelCanvas, constelCtxt;
const PARTICLE_COUNT = 70;

var initConstellation = function() {
    constelCanvas = document.getElementById('constellation');
    constelCtxt = constelCanvas.getContext('2d');

    constelCanvas.width = window.innerWidth;
    constelCanvas.height = window.innerHeight;

    constellation = new Constellation(PARTICLE_COUNT, constelCanvas, constelCtxt);
    cbackList.add(constellation.update.bind(constellation));
};

window.addEventListener('resize', function() {
    if (constelCanvas) {
        constelCanvas.width = window.innerWidth;
        constelCanvas.height = window.innerHeight;
    }
});

window.addEventListener('load', initConstellation);
