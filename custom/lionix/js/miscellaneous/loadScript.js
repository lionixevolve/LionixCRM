function loadScript(pathToScript, callback) {
    var head = document.getElementsByTagName("head")[0];
    var script = document.createElement("script");

    script.type = "text/javascript";
    script.src = pathToScript + "?t=" + new Date().getTime(); //prevent caching

    if (callback) {
        script.onload = callback;
    }

    head.appendChild(script);
};