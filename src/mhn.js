window.addEventListener('DOMContentLoaded', (event) => {

let el = document.querySelector("body#page-site-index.notloggedin");
if (el && window.innerWidth > 900) {
    el.classList.add("drawer-open-left");
    el.querySelector("button[data-action='toggle-drawer']").setAttribute("aria-expanded", "true");
    el = el.querySelector("#nav-drawer");
    el.classList.remove("closed");
    el.setAttribute("aria-hidden", "false");
}

});

