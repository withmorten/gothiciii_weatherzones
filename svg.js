document.addEventListener("DOMContentLoaded", function() {
    var object = document.getElementById("svg");
    object.addEventListener("load", function() {
        var svg = object.contentDocument;
    }, false);
});

function toggle(event) {
    event.stopPropagation();
    event.preventDefault();
    var input = event.target || event.srcElement;
    
    if(input.tagName.toLowerCase() === 'label') {
        event.preventDefault();                                         // Fuck you JavaScript ... seriously, fuck you
        input = input.firstElementChild;
    }
    
    checked = input.attributes["checked"];

    if(typeof checked !== 'undefined' && checked.textContent === "checked") {
        input.removeAttribute("checked");                               // and go fuck yourself some more
    } else {
        input.setAttribute("checked", "checked");                       // and even more
    }
}

function die() {
    throw new Error("");
}