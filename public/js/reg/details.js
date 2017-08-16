
var catSel, evtSel, btnFetch;
var sortCheck, dataSection, evtTitle, expBtn;
var catIDMap = ['tevent', 'ntevent', 'sevent', 'fevent', 'cevent', 'workshop'];

var setEvtListFor = function(catID) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "evt-list",
        data: { id: catID },
        beforeSend: function() { evtSel.disabled = true; }
    }).done(function(response) {
        if (response['status'] == 0) {
            var opts = response['list'];
            evtSel.innerHTML = '';

            for (var i = 0; i < opts.length; i++)
                evtSel.appendChild(new Option(opts[i]));
            evtSel.selectedIndex = 0;
        }
    }).always(function(response) {
        evtSel.disabled = false;
    });
};

var setRegListFor = function(catID, evtID, _sort) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "reg-list",
        data: { catID: catID, evtIdx: evtID, sort: _sort },
        beforeSend: function() { btnFetch.classList.add('process-wait'); }
    }).done(function(response) {
        if (response['status'] != 0)
            return;

        if (response['count'] == 0) {
            dataSection.innerHTML = 'Sorry, no registrations yet.';
            expBtn.classList.add('hidden');
        } else {
            buildDataTable(response['count'], response['data']);
            expBtn.classList.remove('hidden');
        }
        evtTitle.innerHTML = evtSel.value;
    }).always(function(response) {
        evtSel.disabled = false;
        btnFetch.classList.remove('process-wait');
    });
};

var buildDataTable = function(count, data) {
    dataSection.innerHTML = '';

    var table = document.createElement('table');
    var tbody = document.createElement('tbody');
    table.classList.add('data-table');

    var cols = ['SI', 'Name', 'Phone', 'Email', 'College', 'Date / Time'];
    // For header
    var tr = document.createElement('tr');
    for (var i = 0; i < cols.length; i++) {
        var th = document.createElement('th');
        th.innerHTML = cols[i];

        tr.appendChild(th);
    }
    tbody.appendChild(tr);

    var keys = ['name', 'phone', 'email', 'college', 'time'];
    for (var i = 0; i < count; i++) {
        tr = document.createElement('tr');

        for (var j = 0; j < cols.length; j++) {
            var cell = document.createElement('td');
            if (j == 0)
                cell.innerHTML = "" + (i + 1);
            else {
                cell.innerHTML = (data[i])[keys[j-1]];
            }

            tr.appendChild(cell);
        }
        tbody.appendChild(tr);
    }

    table.appendChild(tbody); dataSection.appendChild(table);
};

var setupPage = function() {
    dataSection = document.getElementById('data-section-id');
    catSel = document.getElementById('cat-name');
    evtSel = document.getElementById('evt-name');
    btnFetch = document.getElementById('fetch-btn');
    evtTitle = document.getElementById('evt-title-id');
    expBtn = document.getElementById('export-btn');
    sortCheck = document.getElementById('_sort');

    catSel.addEventListener('change', function(event) {
        setEvtListFor(catIDMap[catSel.selectedIndex]);
    });

    btnFetch.addEventListener('click', function(event) {
        if (catSel.selectedIndex < 0 || evtSel.selectedIndex < 0)
            return;

        var catID = catIDMap[catSel.selectedIndex];
        setRegListFor(catID, evtSel.selectedIndex, sortCheck.checked);
    });

    setEvtListFor(catIDMap[catSel.selectedIndex]);

    expBtn.addEventListener('click', function(event) {
        event.preventDefault();

        var dataType = 'data:application/vnd.ms-excel';
        var tableHTML = dataSection.outerHTML.replace(/ /g, '%20');

        var a = document.createElement('a');
        a.href = dataType + ', ' + tableHTML;
        a.download = evtTitle.innerHTML + '.xls';
        a.click();
    });
}

window.addEventListener('load', setupPage, true);
