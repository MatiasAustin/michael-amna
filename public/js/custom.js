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


// Photo upload preview and validation
  const input = document.getElementById('photoInput');
  const preview = document.getElementById('previewContainer');
  const errorMsg = document.getElementById('errorMsg');
  const maxSize = 5 * 1024 * 1024; // 5MB

  input.addEventListener('change', function () {
    preview.innerHTML = '';
    errorMsg.textContent = '';

    const file = this.files[0];
    if (!file) return;

    // Validasi ukuran
    if (file.size > maxSize) {
      errorMsg.textContent = 'Ukuran file terlalu besar. Maksimal 5MB.';
      this.value = ''; // reset input
      return;
    }

    // Validasi tipe
    const allowedTypes = ['image/jpeg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
      errorMsg.textContent = 'Format file tidak didukung. Hanya JPG dan PNG.';
      this.value = '';
      return;
    }

    // Tampilkan preview
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.style.maxWidth = '300px';
      img.style.marginTop = '10px';
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
