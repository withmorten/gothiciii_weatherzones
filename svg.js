function toggleCheckbox(event) {
    event.preventDefault();                                             // Fuck you JavaScript ... seriously, fuck you
    var label = event.target || event.srcElement;
    var input = label.firstElementChild;
    
    if(input.hasAttribute("checked") || input.getAttribute("checked") === "checked") {
        input.removeAttribute("checked");                               // and go fuck yourself some more
    } else {
        input.setAttribute("checked", "checked");                       // and even more
    }
    
    // now finally for the svg manipulation, because of course fucking checkboxes are the most complicated part
    
    var svg = document.getElementById("svg").contentDocument;
    var musiclocation = input.id;
    var nodes = svg.getElementsByClassName(musiclocation);
    var n = nodes.length;
    
    for(var i = 0; i < n; i++) {
        toggleNodeVisibilty(nodes[i]);
    }
}

function toggleNodeVisibilty(node) {
    if(!node.hasAttribute("visibility") || node.getAttribute("visibility") === 'visible') {
        node.setAttribute("visibility", "hidden");
    } else {
        node.setAttribute("visibility", "visible");
    }
}

function die() {
    throw new Error("");
}