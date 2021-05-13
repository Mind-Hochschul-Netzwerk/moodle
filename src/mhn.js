window.addEventListener('DOMContentLoaded', (event) => {

/* show the navigation when not logged in */
let el = document.querySelector("body.course-1.notloggedin");
if (el && window.innerWidth > 900 && !el.classList.contains("pagelayout-login")) {
    el.classList.add("drawer-open-left");
    el.querySelector("button[data-action='toggle-drawer']").setAttribute("aria-expanded", "true");
    el = el.querySelector("#nav-drawer");
    el.classList.remove("closed");
    el.setAttribute("aria-hidden", "false");
}

});

