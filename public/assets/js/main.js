var menu_btn = document.querySelector('.navbar-toggler');
menu_btn.onclick = e => document.querySelector(menu_btn.dataset.bsTarget).classList.toggle('show')