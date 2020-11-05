function openTab(tabId, element) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabId).style.display = "block";
    element.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("tab-1-btn").click();
});

