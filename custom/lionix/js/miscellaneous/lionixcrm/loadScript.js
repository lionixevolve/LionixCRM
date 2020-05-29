lx.lionixCRM.loadScript = function (pathToScript, noCache, callback) {
    let head = document.getElementsByTagName("head")[0];
    let script = document.createElement("script");
    script.type = "text/javascript";
    if (noCache) {
        script.src = pathToScript + "?t=" + new Date().getTime(); //prevent caching
    } else {
        script.src = pathToScript;
    }
    if (callback) {
        script.onload = callback;
    }
    head.appendChild(script);
};
