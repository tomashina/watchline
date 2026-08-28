var w = document.createElement("script");
w.type = "text/javascript";
w.src = trustpilot_widget_script_url;
w.async = true;
document.head.appendChild(w);

function inIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return false;
    }
}

if (inIframe()) {
    window.addEventListener('message', function(e) {
        var adminOrign = new URL(window.location).hostname;
        if (!e.data || e.origin.indexOf(adminOrign) === -1) {
            return;
        }
        var parsedData = {};
        if (typeof TrustpilotPreview !== 'undefined') {
            if (typeof e.data === 'string' && e.data === 'submit') {
                TrustpilotPreview.sendTrustboxes();
            } else if (tryParseJson(e.data, parsedData)) {
                if (parsedData.trustbox) {
                    TrustpilotPreview.setSettings(parsedData.trustbox);
                } else if (parsedData.customised) {
                    TrustpilotPreview.updateActive(parsedData.customised);
                }
            }
        } else if (tryParseJson(e.data, parsedData)) {
            var p = document.createElement("script");
            p.type = "text/javascript";
            p.onload = function () {
                const iFrame = e.source.parent.document.getElementById('configuration_iframe').contentWindow;
                TrustpilotPreview.init([trustpilot_preview_css_url], parsedData, iFrame, e.source);
            };
            p.src = trustpilot_preview_script_url;
            document.head.appendChild(p);
        }
    });
}

function tryParseJson(str, out) {
    try {
        out = Object.assign(out, JSON.parse(str));
    } catch (e) {
        return false;
    }
    return true;
}
