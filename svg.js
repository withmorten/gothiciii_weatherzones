document.addEventListener("DOMContentLoaded", function() {
    var object = document.getElementById("svg");
    object.addEventListener("load", function() {
        var svg = object.contentDocument;
    }, false);
});

function toggle(event) {
    event.preventDefault();                                         // Fuck you JavaScript ... seriously, fuck you
    
    var input = event.srcElement;
    
    if(input.tagName === 'LABEL') {
        input = input.firstElementChild;
    }
    
    if(input.checked === true)  input.removeAttribute("checked");   // and go fuck yourself some more
    else input.setAttribute("checked", "");
}