
var requestAnimationFrame =
    window.requestAnimationFrame || window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame || window.msRequestAnimationFrame ||
    window.oRequestAnimationFrame;

const PI_TWO = 2 * Math.PI;
const PI_OVER_TWO = Math.PI / 2;

var animate = function(key, val, vi, vf, duration, cbRenderFrame, cbProgressEnd) {
    var t0 = 0, progress, elapsed, tprev = 0;
    var step = function(timestamp) {
        elapsed = timestamp - t0;
        // Interpolation calculations here
        progress = elapsed / duration;

        key[val] = vi + progress * (vf - vi);
        if (progress >= 0.9833) {
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
		
var sideNavEvent = new CustomEvent('sideNavEvent', {
	detail: {
		mode: 0  // 0: Open, 1: Close
	}
});

function toggleSideNav() {
	document.dispatchEvent(sideNavEvent);
	sideNavEvent.detail.mode = 1 - sideNavEvent.detail.mode;
}

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
    'password': 'Password should be at least 4 characters in length.',
    'conf-password': 'Passwords do not match.'
};
var formBtnMap = { 'form-sh-reg': 'form-reg', 'form-sh-login': 'form-login' };
var formURLMap = { 'form-reg': 'pre-register', 'form-login': 'login' };
var formWaitMsgMap = { 'form-reg': 'Registering', 'form-login': 'Logging In' };

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

var dispatchFormData = function(form, _url, waitText) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var formData = new Object();
    var inpFields = document.querySelectorAll('#' + form.id + ' .input-field');
    var regBtn = document.querySelectorAll('#' + form.id + ' input[type=submit]')[0];
    var defRegBtnText = regBtn.value;

    for (var i = inpFields.length - 1; i >= 0; --i)
        formData[inpFields[i].name] = inpFields[i].value;

    $.ajax({
        type: "POST", url: _url,
        data: { regd: formData },
        beforeSend: function() {
            regBtn.classList.add("process-wait");
            regBtn.value = waitText;
        }
    }).done(function(response) {
        notifyFormAction(0, response['status'], response['msg']);
    }).fail(function(response) {
        notifyFormAction(0, 1, "Network Error! :(");
    }).always(function(response) {
        regBtn.classList.remove("process-wait");
        regBtn.value = defRegBtnText;
    });
};

var onFormSubmit = function(form, _url, waitText) {
   var vdata = isFormValid(form.id);

   if (vdata[0])
      dispatchFormData(form, _url, waitText);
   else
      notifyFormAction(0, 1, formErrMsg[vdata[1]]);
};

function setupPage() {
	document.getElementById('login-btn').addEventListener("click", function(){
		var ls = document.getElementById('login-section');
		ls.classList.toggle('hidden');
		var icon = ls.classList.contains('hidden') ? "fa-sign-in" : "fa-times";
		this.innerHTML = '<i class="fa '+ icon +' " aria-hidden="true" onclick></i>';
	});

	document.getElementById('open-sidenav').addEventListener('click', function() { toggleSideNav(); });
	document.getElementById('overlay').addEventListener('click', function() { toggleSideNav(); });

	/* Setup Forms */
	var inputFields = document.querySelectorAll('.input-field');
	for (var i = 0; i < inputFields.length; ++i) {
		slideLabelUpNonEmpty(inputFields[i]);
		inputFields[i].addEventListener('focus',
			function(e) { slideLabelUpEmpty(e.target); });

		inputFields[i].addEventListener('blur', function(e) {
			if (e.target.value === "")
				e.target.nextElementSibling.classList.remove("input-div-label-up");
		});

        inputFields[i].addEventListener("input", function(e) { validateInput(this); });
	}

    regInfoMsg = document.getElementById('reg-info-msg');
    var forms = document.querySelectorAll('.form');
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener('submit', function(e) {
            e.preventDefault();
            onFormSubmit(this, formURLMap[this.id], formWaitMsgMap[this.id]);
        });
    }
}

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

// Add custom listeners
document.addEventListener('sideNavEvent', function(e) {
	var ls = document.getElementById('mySidenav');
	var overlay= document.getElementById('overlay');

	switch (e.detail.mode) {
		case 0:
		overlay.classList.remove('hidden');
		ls.classList.remove('hidden');
		break;

		case 1:
		overlay.classList.add('hidden');
		ls.classList.add('hidden');
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

window.addEventListener('load', setupPage);

