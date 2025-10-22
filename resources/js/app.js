import './bootstrap';

let lastScroll = 0;
const nav = document.querySelector('.nav');

window.addEventListener('scroll', () => {
  let currentScroll = window.pageYOffset;

  if (currentScroll > lastScroll) {
    // scroll down → hide nav
    nav.classList.add('hidden');
  } else {
    // scroll up → show nav
    nav.classList.remove('hidden');
  }

  lastScroll = currentScroll;
});


// Add active class to the current button (highlight it)
const header = document.getElementById("myDIV");
const btns = header.getElementsByClassName("nav-link");
for (let i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    const current = document.getElementsByClassName("active");
    if (current.length > 0) {
      current[0].className = current[0].className.replace(" active", "");
    }
    this.className += " active";
  });
}
