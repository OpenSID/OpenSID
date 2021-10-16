CIjs = (function () {
    "use strict";

    var hasClass = function (el, cssClass) {
        return el.className.match(new RegExp('\\b' + cssClass + '\\b'));
    };
    var removeClass = function (el, cssClass) {
        el.className = el.className.replace(new RegExp('\\b' + cssClass + '\\b'), ' ');
    };
    var addClass = function (el, cssClass) {
        if (!hasClass(el, cssClass)) {
            el.className += " " + cssClass;
        }
    };
    var requestStack = [];

    var successStreak = 4;
    var pendingRequests = 0;
    var renderAjaxRequests = function () {
        var requestCounter = document.querySelector('.ci-toolbar-ajax-requests');
        if (!requestCounter) {
            return;
        }
        requestCounter.textContent = '(' + requestStack.length + ')';
        requestCounter.className = 'ci-toolbar-ajax-requests';

        var infoSpan = document.querySelector(".ci-toolbar-ajax-info");
        if (infoSpan) {
            infoSpan.textContent = requestStack.length + ' AJAX request' + (requestStack.length > 1 ? 's' : '');
        }

        var ajaxToolbarPanel = document.querySelector('.ci-toolbar-block-ajax');
        if (requestStack.length) {
            addClass(ajaxToolbarPanel.parentNode, 'active');
        } else {
            removeClass(ajaxToolbarPanel.parentNode, 'active');
        }
        if (pendingRequests > 0) {
            addClass(ajaxToolbarPanel, 'ci-ajax-request-loading');
        } else if (successStreak < 4) {
            addClass(ajaxToolbarPanel, 'ci-toolbar-status-red');
            removeClass(ajaxToolbarPanel, 'ci-ajax-request-loading');
        } else {
            removeClass(ajaxToolbarPanel, 'ci-ajax-request-loading');
            removeClass(ajaxToolbarPanel, 'ci-toolbar-status-red');
        }
    };

    var startAjaxRequest = function (index) {
        var request = requestStack[index];
        pendingRequests++;
        var row = document.createElement('tr');
        request.DOMNode = row;

        var tbody = document.querySelector('.ci-toolbar-ajax-request-list');

        if (!tbody) {
            return;
        }

        var methodCell = document.createElement('td');
        methodCell.textContent = request.method;
        row.appendChild(methodCell);

        var statusCodeCell = document.createElement('td');
        var statusCode = document.createElement('span');
        statusCode.textContent = '-';
        statusCodeCell.appendChild(statusCode);
        row.appendChild(statusCodeCell);

        var pathCell = document.createElement('td');
        pathCell.className = 'ci-ajax-request-url';
        if ('GET' === request.method) {
            var pathLink = document.createElement('a');
            pathLink.setAttribute('href', request.url);
            pathLink.setAttribute('target', '_blank');
            pathLink.textContent = request.url.split('?')[0];
            pathCell.appendChild(pathLink);
        } else {
            pathCell.textContent = request.url;
        }
        pathCell.setAttribute('title', request.url);
        row.appendChild(pathCell);

        var durationCell = document.createElement('td');
        durationCell.className = 'ci-ajax-request-duration';
        durationCell.textContent = '-';
        row.appendChild(durationCell);

        var profilerCell = document.createElement('td');
        profilerCell.className = 'ci-ajax-profiler-url';
        if ('' != request.profiler) {
            profilerLink.textContent = 'profil';
            profilerCell.appendChild(profilerCell);
        }

        row.appendChild(profilerCell);

        row.className = 'ci-ajax-request ci-ajax-request-loading';
        tbody.insertBefore(row, tbody.firstChild);
        renderAjaxRequests();
    };

    var finishAjaxRequest = function (index) {
        var request = requestStack[index];
        pendingRequests--;
        var row = request.DOMNode;
        var methodCell = row.children[0];
        var statusCodeCell = row.children[1];
        var statusCodeElem = statusCodeCell.children[0];
        var durationCell = row.children[3];
        var profilerCell = row.children[4];

        if (request.error) {
            row.className = 'ci-ajax-request ci-ajax-request-error';
            methodCell.className = 'ci-ajax-request-error';
            successStreak = 0;
        } else {
            row.className = 'ci-ajax-request ci-ajax-request-ok';
            successStreak++;
        }

        if (request.statusCode) {
            if (request.statusCode < 300) {
                statusCodeElem.setAttribute('class', 'ci-toolbar-status');
            } else if (request.statusCode < 400) {
                statusCodeElem.setAttribute('class', 'ci-toolbar-status ci-toolbar-status-yellow');
            } else {
                statusCodeElem.setAttribute('class', 'ci-toolbar-status ci-toolbar-status-red');
            }
            statusCodeElem.textContent = request.statusCode;
        }

        if (request.duration) {
            durationCell.textContent = request.duration + 'ms';
        }

        if (request.profiler) {
            var profilerLink = document.createElement('a');
            profilerLink.setAttribute('href', '/index.php/develbarprofiler/profil/' + request.profiler);
            profilerLink.setAttribute('target', '_blank');
            profilerLink.textContent = 'profil';
            profilerCell.appendChild(profilerLink);
        }

        renderAjaxRequests();
    };

    var addEventListener;

    var el = document.createElement('div');
    if (!('addEventListener' in el)) {
        addEventListener = function (element, eventName, callback) {
            element.attachEvent('on' + eventName, callback);
        };
    } else {
        addEventListener = function (element, eventName, callback) {
            element.addEventListener(eventName, callback, false);
        };
    }

    if (window.fetch && window.fetch.polyfill === undefined) {
        var oldFetch = window.fetch;
        window.fetch = function () {
            var promise = oldFetch.apply(this, arguments);
            var url = arguments[0];
            var params = arguments[1];
            var paramType = Object.prototype.toString.call(arguments[0]);
            if (paramType === '[object Request]') {
                url = arguments[0].url;
                params = {
                    method: arguments[0].method,
                    credentials: arguments[0].credentials,
                    headers: arguments[0].headers,
                    mode: arguments[0].mode,
                    redirect: arguments[0].redirect
                };
            }
            var method = 'GET';
            if (params && params.method !== undefined) {
                method = params.method;
            }

            var stackElement = {
                error: false,
                url: url,
                method: method,
                type: 'fetch',
                start: new Date()
            };

            var idx = requestStack.push(stackElement) - 1;
            promise.then(function (r) {
                stackElement.duration = new Date() - stackElement.start;
                stackElement.error = r.status < 200 || r.status >= 400;
                stackElement.statusCode = r.status;
                finishAjaxRequest(idx);
            }, function (e) {
                stackElement.error = true;
            });
            startAjaxRequest(idx);

            return promise;
        };
    }

    if (window.XMLHttpRequest && XMLHttpRequest.prototype.addEventListener) {
        var proxied = XMLHttpRequest.prototype.open;
        var idx = 0;

        XMLHttpRequest.prototype.open = function (method, url, async, user, pass) {
            var self = this;

            var stackElement = {
                error: false,
                url: url,
                method: method,
                profiler: '',
                start: new Date()
            };

            idx = requestStack.push(stackElement) - 1;

            this.addEventListener('readystatechange', function () {
                if (self.readyState == 4) {
                    stackElement.duration = new Date() - stackElement.start;
                    stackElement.error = self.status < 200 || self.status >= 400;
                    stackElement.statusCode = self.status;
                    stackElement.profiler = self.getResponseHeader('X-CI-Toolbar-Profiler');

                    finishAjaxRequest(idx);
                }
            }, false);

            startAjaxRequest(idx);
            proxied.apply(this, Array.prototype.slice.call(arguments));
        };
    }

    return {
        hasClass: hasClass,
        removeClass: removeClass,
        addClass: addClass,
        addEventListener: addEventListener,
        renderAjaxRequests: renderAjaxRequests
    };
})();

CIjs.addEventListener(window, 'load', function () {
    CIjs.renderAjaxRequests();
});
