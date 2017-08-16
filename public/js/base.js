/**
 * Page Driver
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

var contentPage = null;
var noClickOverlay = null, regDialog = null;
var ppopup = null;

/* Nav arrays */
var nID = [ "schedule", "sponsors", "about-us", "contact", "dev" ];
var navDialog = null, navIDMap = new Object();

/* Form elements */
var inputFields = null, inputLabels = null;

/* Event definitions */
var regDialogEvent = new CustomEvent("regDialogEvent", {
        detail: {
            mode: 0,  /* 0: Open, 1: Close */
            opened: false
        }
    }),
    navDialogEvent = new CustomEvent("navDialogEvent", {
        detail: {
            id: 0,
            mode: 0,  /* 0: Open, 1: Tabs, 2: Close */
            opened: false
        }
    }),
    evtRegisterEvent = new CustomEvent("evtRegisterEvent", {
        detail: { mode: 0, evtID: '' }
    });

/* Registration vars */
var regBtn, userBtn, userBtnText, userLogout, userLogoutIcon;

var toggleRegButton = function(loggedin) {
    if (loggedin)
        toggleBetween(regBtn, userBtn, 'remove-o');
    else
        toggleBetween(userBtn, regBtn, 'remove-o');
};

function User(fname, lname, email) {
    this.fname = fname; this.lname = lname;
    this.email = email;

    this.logout = function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST", url: 'logout',
            beforeSend: function() {
                userLogoutIcon.classList.remove('fa-power-off');
                userLogoutIcon.classList.add('fa-circle-o-notch');
                userLogoutIcon.classList.add('fa-spin');
            }
        }).done(function(response) {
            if (response['status'] == 0)
                toggleRegButton(false);
        }).always(function(response) {
            userLogoutIcon.classList.remove('fa-spin');
            userLogoutIcon.classList.remove('fa-circle-o-notch');
            userLogoutIcon.classList.add('fa-power-off');
        });
    }
};

function slideLabelUpNonEmpty(target) {
    if (target.value !== "")
        target.nextElementSibling.classList.add("input-div-label-up");
}

function slideLabelUpEmpty(target) {
    if (target.value === "")
        target.nextElementSibling.classList.add("input-div-label-up");
}

/* Validation */
var validationRex = {
    'name': /^[a-zA-Z]{2,}( [a-zA-Z]{2,})*$/,
    'name-nr': /^([a-zA-Z]{2,}( [a-zA-Z]{2,})*)*$/,
    'email': /^.*$/, 'phone': /^[0-9]{10}$/,
    'password': /^.*$/,
    'text': /^.*$/
};
var regPasswdInput, regInfoMsg, butReg, butMailResend;
var formErrMsg = {
    'name': 'Invalid First Name',
    'name-nr': 'Invalid Last Name',
    'email': 'That doesn\'t look like a valid email.',
    'phone': 'Don\'t think that\'s your number.',
    'password': 'Password should be within 6 to 100 characters in length.',
    'conf-password': 'Passwords do not match.'
};
var formBtnMap = { 'form-sh-reg': 'form-reg', 'form-sh-login': 'form-login' };
var formURLMap = { 'form-reg': 'register', 'form-login': 'login' };
var formWaitMsgMap = { 'form-reg': 'Registering', 'form-login': 'Logging In' };
var loggedUser = null;

var formDataMap = {
    'form-reg': function(formData) {
        var genRadio = document.querySelectorAll('#form-reg .input-div#gender > .ir-active')[0].id;
        formData['gender'] = (genRadio == 'ir-female') ? 'f' : 'm';

        return formData;
    }
};

var formCallback = {
    'form-login': function(response) {
        loggedUser = new User(response['fname'], response['lname'], response['email']);

        userBtnText.innerHTML = loggedUser.fname;
        toggleRegButton(true); setRegPanelVisible(false);
    }
};

var formResponseEvent = new CustomEvent("formResponseEvent", {
        detail: {
            mode: 0,  /* 0: Show, 1: Hide */
            status: 0,  /* 0: Success, 1: Error */
            msg: ''
        }
    });

var validateInput = function(input) {
    var validationType = input.getAttribute("data-validation") || "";
    var text = input.value, validated = true;

    if (validationType == 'email')
        validated = input.checkValidity();
    else if (validationType == 'password')
        validated =  (text.length >= 6 && text.length <= 100);
    else if (validationType == 'conf-password')
        validated = (text == regPasswdInput.value);
    else
        validated = validationRex[validationType].test(text);
    
    if (!validated && text.trim().length > 0)
        input.classList.add('input-err');
    else
        input.classList.remove('input-err');

    return validated;
};

var isFormValid = function(formID) {
    var valid = true, fkey = null;
    var inputs = document.querySelectorAll('#' + formID + ' .input-field');
    for (var i = 0; i < inputs.length; i++) {
        valid = valid && validateInput(inputs[i]);
        if (!valid) {
            fkey = inputs[i].getAttribute('data-validation');
            break;
        }
    }

    return [valid, fkey];
};

var notifyFormAction = function(mode, status, msg) {
    formResponseEvent.detail.mode = mode;
    formResponseEvent.detail.status = status;
    formResponseEvent.detail.msg = msg;

    document.dispatchEvent(formResponseEvent);
};

var dispatchFormData = function(form, _url, waitText, callback) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Get general form data
    var formData = new Object();
    var inpFields = document.querySelectorAll('#' + form.id + ' .input-field');
    var regBtn = document.querySelectorAll('#' + form.id + ' input[type=submit]')[0];
    var defRegBtnText = regBtn.value;

    for (var i = inpFields.length - 1; i >= 0; --i)
        formData[inpFields[i].name] = inpFields[i].value;

    // Append form specific data
    if (formDataMap[form.id])
        (formDataMap[form.id])(formData);

    $.ajax({
        type: "POST", url: _url,
        data: { regd: formData },
        beforeSend: function() {
            regBtn.classList.add("process-wait");
            regBtn.value = waitText;
        }
    }).done(function(response) {
        notifyFormAction(0, response['status'], response['msg']);
        if (callback && response['status'] == 0) callback(response);
    }).fail(function(response) {
        notifyFormAction(0, 1, "Network Error! :(");
    }).always(function(response) {
        regBtn.classList.remove("process-wait");
        regBtn.value = defRegBtnText;
    });
};

var configureMailResend = function() {
    butMailResend.addEventListener('click', function(event) {
        if (!validateInput(document.getElementById('_email'))) {
            notifyFormAction(0, 1, formErrMsg['email']);
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST", url: "mail/resend",
            data: { 'email': document.getElementById('_email').value }
        }).done(function(response) {
            notifyFormAction(0, response['status'], response['msg']);
        }).fail(function(response) {
            notifyFormAction(0, 1, "Network Error!");
        });
    });
};

var setRegPanelVisible = function(show) {
    regDialogEvent.detail.mode = show ? 0 : 1;
    document.dispatchEvent(regDialogEvent);
};

var radioButtonSelect = function(el, radioClass, activeClass) {
    var selected = document.querySelectorAll(radioClass + '.' + activeClass)[0];
    if (el === selected) return;

    selected.classList.remove(activeClass);
    el.classList.add(activeClass);
};

var singleElementSelect = function(id, elClass, removeClass) {
    var active = document.querySelectorAll('.' + elClass + ':not(.' + removeClass + ')')[0];
    hideObj(active, removeClass, true, function() {
        showObj(document.getElementById(id), removeClass);
    });
};

var toggleBetween = function(hide, show, className) {
    hideObj(hide, className, true, function() {
        showObj(show, className);
    });
};

var onFormSubmit = function(form, _url, waitText, callback) {
   var vdata = isFormValid(form.id);

   if (vdata[0])
      dispatchFormData(form, _url, waitText, callback);
   else
      notifyFormAction(0, 1, formErrMsg[vdata[1]]);
};

var showDialogModal = function(dialog, className, show) {
    if (show) {
        noClickOverlay.classList.remove('hidden');
        showObj(dialog, className);
    } else {
        hideObj(dialog, className);
        noClickOverlay.classList.add('hidden');
    }
};

function initPage() {
    if ($('meta[name="fritolay"]').attr('content') == 'true')
        loggedUser = new User('', '', '');
    else
        loggedUser = null;

    contentPage = document.getElementById("content-page");
    regDialog = document.getElementById("reg-dialog");

    /* Init registration vars */
    regBtn = document.getElementById('pre-reg-but');
    userBtn = document.getElementById('user-btn');
    userBtnText = document.getElementById('user-btn-text');
    userLogout = document.getElementById('user-logout');
    userLogoutIcon = document.querySelectorAll('#user-logout > i')[0];

    userLogout.addEventListener('click', function() {
        if (loggedUser) { loggedUser.logout(); loggedUser = null; }
    });
    regBtn.addEventListener('click', function(event) {
        event.preventDefault();
        setRegPanelVisible(true); return false;
    });

    noClickOverlay = document.getElementById("no-click-overlay");
    noClickOverlay.addEventListener("click", function(event) {
        /* Close navDialog if it has previously been opened */
        if (navDialogEvent.detail.opened) {
            navDialogEvent.detail.mode = 2;
            document.dispatchEvent(navDialogEvent);
        }

        /* Close regDialog if it has previously been opened */
        if (regDialogEvent.detail.opened)
            setRegPanelVisible(false);

        if (ppopup) {
            showDialogModal(ppopup, 'remove-o', false);
            setTimeout(function() {
                ppopup.remove(); ppopup = null;
            }, 500);
        }
    });

    // Register form events
    inputFields = document.querySelectorAll(".input-field");
    regInfoMsg = document.getElementById('reg-info-msg');
    butReg = document.getElementById('but-reg');
    butMailResend = document.getElementById('mail-resend');
    regPasswdInput = document.querySelectorAll('#form-reg .input-field[name="password"]')[0];

    for (var i = 0; i < inputFields.length; ++i) {
        slideLabelUpNonEmpty(inputFields[i]);
        inputFields[i].addEventListener("focus",
            function(event) { slideLabelUpEmpty(event.target); });

        inputFields[i].addEventListener("blur", function(event) {
            if (event.target.value === "")
                event.target.nextElementSibling.classList.remove("input-div-label-up");
        });

        inputFields[i].addEventListener("input", function(e) { validateInput(this); });
    }

    var forms = document.querySelectorAll('.form');
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener('submit', function(e) {
            e.preventDefault();
            onFormSubmit(this, formURLMap[this.id],
                formWaitMsgMap[this.id], formCallback[this.id]);
        });
    }

    /* Gender radio btn */
    var radios = document.querySelectorAll('#form-reg .input-radio');
    for (var i = 0; i < radios.length; i++) {
        radios[i].addEventListener('click', function(e) {
            radioButtonSelect(this, '#form-reg .input-radio', 'ir-active');
        });
    }

    /* Form section header radio btn */
    var fshBtns = document.querySelectorAll('.reg-dialog .form-sh-btn');
    for (var i = 0; i < fshBtns.length; i++) {
        fshBtns[i].addEventListener('click', function(e) {
            if (this.classList.contains('form-sh-active')) return;
            if (this.id == 'form-sh-reg')
                showObj(document.getElementById('resend-email'), 'remove-o');
            else
                hideObj(document.getElementById('resend-email'), 'remove-o', false);

            radioButtonSelect(this, '.reg-dialog .form-sh-btn', 'form-sh-active');
            singleElementSelect(formBtnMap[this.id], 'form', 'remove-o');
        });
    }

    var regBackBut = document.querySelectorAll('.section-header > span')[0];
    regBackBut.addEventListener('click', function(e) {
        setRegPanelVisible(false);
    });
    configureMailResend();

    /* Setup Nav */
    navDialog = document.getElementById('nav-dialog-menu');
    for (var i = 0; i < nID.length; ++i) {
        navIDMap["n-" + nID[i]] = i;

        var object = document.getElementById("n-" + nID[i]);
        object && object.addEventListener("click", function(event) {
            navDialogEvent.detail.mode = 0;
            navDialogEvent.detail.id = navIDMap[this.id];
            document.dispatchEvent(navDialogEvent);
        });

        object = document.getElementById("ni-" + nID[i]);
        object.addEventListener("click", function(event) {
            navDialogEvent.detail.mode = 1;
            navDialogEvent.detail.id = navIDMap[this.id.replace('ni-', 'n-')];
            document.dispatchEvent(navDialogEvent);
        });
    }

    // Piyush's popup
    ppopup = document.getElementById('piyush-popup');
    showDialogModal(ppopup, 'remove-o', true);
}

var navSwitchTab = function(id) {
    var activeObj = document.querySelectorAll(".nav-content .nav-header-menu > .active");
    if (activeObj)
        activeObj[0].classList.remove('active');

    activeObj = document.querySelectorAll(".pic-panel .nav-title:not(.hidden)");
    if (activeObj)
        activeObj[0].classList.add('hidden');

    activeObj = document.querySelectorAll(".nav-content .nav-info:not(.hidden)");
    if (activeObj)
        activeObj[0].classList.add('hidden');

    document.getElementById("ni-" + nID[id]).classList.add('active');
    document.getElementById("nf-" + nID[id]).classList.remove('hidden');
    document.getElementById("nt-" + nID[id]).classList.remove('hidden');
};

const transitionTimeout = {
    'remove-o': 400, 'remove-ot': 250,
    'remove-oh': 250, 'flex-zero': 300,
    'remove-slide': 600
};
/* Resist the urge to use 'transitionend' for now,
   deadline's approaching and you don't want to spend
   your time pulling your hair out over those bugs */
var hideObj = function(obj, className, detach, callback) {
    obj.classList.add(className);
    setTimeout(function() {
        if (detach === undefined || detach)
            obj.classList.add('hidden');
        if (callback) callback();
    }, transitionTimeout[className]);
};

var showObj = function(obj, className, callback) {
    obj.classList.remove('hidden');
    setTimeout(function() {
        obj.classList.remove(className);
        if (callback) {
            setTimeout(callback, transitionTimeout[className]);
        }
    }, 20);
};

/* Register for window events */
window.addEventListener('load', function() {
    initPage();
}, true);

/* Register for other events */
document.addEventListener("regDialogEvent", function(event) {
    event.preventDefault();
    switch (event.detail.mode) {
        case 0:
            noClickOverlay.classList.remove("hidden");
            showObj(regDialog, 'remove-o');
            regDialogEvent.detail.opened = true;
            break;

        case 1:
            hideObj(regDialog, 'remove-o', true, function() {
                notifyFormAction(1, 0, null);
            });
            noClickOverlay.classList.add("hidden");
            regDialogEvent.detail.opened = false;
            break;
    }
});
document.addEventListener("formResponseEvent", function(event) {
    event.preventDefault();
    switch (event.detail.mode) {
        case 0:
            /* Set appropriate msg */
            regInfoMsg.innerHTML = event.detail.msg;

            /* Set appropriate status color */
            if (event.detail.status == 0) {
                regInfoMsg.classList.remove('error-bc');
                regInfoMsg.classList.add('success-bc');
            } else {
                regInfoMsg.classList.remove('success-bc');
                regInfoMsg.classList.add('error-bc');
            }

            if (regInfoMsg.classList.contains('hidden'))
                showObj(regInfoMsg, 'remove-o');
            break;

        case 1:
            hideObj(regInfoMsg, 'remove-o');
            break;
    }
});

document.addEventListener("navDialogEvent", function(event) {
    event.preventDefault();
    switch (event.detail.mode) {
        case 0:
            noClickOverlay.classList.remove('hidden');
            navSwitchTab(event.detail.id);
            showObj(navDialog, 'remove-o');
            navDialogEvent.detail.opened = true;
            break;

        case 1:
            navSwitchTab(event.detail.id);
            break;

        case 2:
            hideObj(navDialog, 'remove-o');
            noClickOverlay.classList.add('hidden');
            navDialogEvent.detail.opened = false;
            break;
    }
});
