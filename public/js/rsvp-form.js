(() => {
  const list = document.getElementById('guestList');
  const add  = document.getElementById('addGuest');

  function rowTemplate(i){
    return `
    <div class="guest-row" data-index="${i}">
      <input name="guests[${i}][first_name]"    placeholder="First name" required>
      <input name="guests[${i}][last_name]"     placeholder="Last name">
      <input name="guests[${i}][dietary]"       placeholder="Dietary (optional)">
      <input name="guests[${i}][accessibility]" placeholder="Accessibility needs (optional)">
      <button type="button" class="remove">Remove</button>
    </div>`;
  }

  add.addEventListener('click', () => {
    const i = list.children.length;
    list.insertAdjacentHTML('beforeend', rowTemplate(i));
  });

  list.addEventListener('click', (e) => {
    if(e.target.classList.contains('remove')){
      const row = e.target.closest('.guest-row');
      row.remove();
      // reindex names
      [...list.children].forEach((el, idx) => {
        el.dataset.index = idx;
        el.querySelectorAll('input').forEach(inp => {
          inp.name = inp.name.replace(/guests\[\d+\]/, `guests[${idx}]`);
        });
      });
    }
  });

  // Optional: if "Decline", keep only contact + clear guests
  document.querySelectorAll('input[name="attend"]').forEach(r => {
    r.addEventListener('change', () => {
      if(r.value === 'no'){
        list.innerHTML = rowTemplate(0);
      }
    });
  });
})();
