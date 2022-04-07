var menu_btn = document.querySelector('.navbar-toggler');
menu_btn.onclick = e => {
    if(getComputedStyle(document.querySelector('#sidebarMenu')).display == 'none' ){
        menu_btn.dataset.bsTarget = '#sidebar-content'
     }


    document.querySelector(menu_btn.dataset.bsTarget).classList.toggle('show');
    menu_btn.firstElementChild.classList.toggle('mobile-none');
    menu_btn.lastElementChild.classList.toggle('mobile-none')
}

var dropdown_sidebars = document.querySelectorAll('.dropdown-sidebar a');
dropdown_sidebars.forEach(element => {
    element.onclick = e => {
        console.log(element);
        element.parentElement.classList.toggle('active');
    }
});
