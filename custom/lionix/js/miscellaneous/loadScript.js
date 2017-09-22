function loadScript(pathToScript, cache, callback) {
    var head = document.getElementsByTagName("head")[0];
    var script = document.createElement("script");
    script.type = "text/javascript";
    if (cache) {
        script.src = pathToScript + "?t=" + new Date().getTime(); //prevent caching
    } else {
        script.src = pathToScript;
    }
    if (callback) {
        script.onload = callback;
    }
    head.appendChild(script);
};
