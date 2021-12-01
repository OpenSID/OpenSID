var HideDevelBar = function () {
        var develbar = document.getElementById('develbar-container');
        var develbar_off = document.getElementById('develbar-off');
        develbar.style.display = 'none';
        develbar_off.style.display = 'block';
    },
    ShowDevelBar = function () {
        var develbar = document.getElementById('develbar-container');
        var develbar_off = document.getElementById('develbar-off');
        develbar_off.style.display = 'none';
        develbar.style.display = 'block';
    },
    HideDevelBarSection = function () {
        var develbar = document.getElementById('develbar-container');
        var elements = develbar.getElementsByTagName('li');

        for (var i = 0; i < elements.length; i++) {
            elements[i].className = '';
        }
    },
    ShowViewVars = function (element) {
        var vars_detail = element.parentElement.parentElement.nextSibling;
        var vars_detail_ele = document.getElementsByClassName('develbar-detail-vars');

        for (var i = 0; i < vars_detail_ele.length; i++) {
            if (vars_detail != vars_detail_ele[i]) {
                vars_detail_ele[i].style.display = 'none';

                var children = document.getElementsByClassName('develbar-open-icon');
                for (var i = 0; i < children.length; i++) {
                    children[i].innerHTML = '+';
                }
            }
        }

        var displayed = vars_detail.style.display;
        if (displayed == 'block') {
            vars_detail.style.display = 'none';
        }
        else {
            vars_detail.style.display = 'block';
            element.getElementsByClassName('develbar-open-icon')[0].innerHTML = '-';
        }
    };