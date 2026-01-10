
    // Biar bisa dipakai juga di luar IIFE (attend radios)
    const guestList = document.getElementById('guestList');
    const addGuestBtn = document.getElementById('addGuest');

    // Template bikin row guest
    function createGuestRow(index) {
        const row = document.createElement('div');
        row.className = 'guest-row';
        row.dataset.index = index;
        row.innerHTML = `
            <input name="guests[${index}][full_name]" placeholder="Guest full name" required>
            <input name="guests[${index}][email]" type="email" placeholder="Guest email (optional)">
            <input name="guests[${index}][dietary]" placeholder="Dietary Requirements (optional)">
            <button type="button" class="remove">Remove</button>
        `;
        return row;
    }

    // Bind tombol remove di 1 row
    function bindRemove(btn) {
        btn.addEventListener('click', () => {
            const rows = guestList.querySelectorAll('.guest-row');
            // Kalau mau boleh hapus semua, hapus aja tanpa limit:
            if (rows.length <= 1) {
                // Kalau masih mau sisain 1, uncomment ini:
                // return;
            }
            btn.closest('.guest-row').remove();
        });
    }

    (function () {
        // Bind remove ke row awal (yang udah ada di HTML)
        guestList.querySelectorAll('.guest-row .remove').forEach(bindRemove);

        // Tambah guest baru
        addGuestBtn.addEventListener('click', () => {
            const index = guestList.querySelectorAll('.guest-row').length;
            const row = createGuestRow(index);
            guestList.appendChild(row);
            bindRemove(row.querySelector('.remove'));
        });
    })();

    // Optional: if "Decline", clear all guests and disable section
    document.querySelectorAll('input[name="attend"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'no') {
                // Hapus semua guest
                guestList.innerHTML = '';
                // Matikan tombol tambah guest
                addGuestBtn.disabled = true;
                addGuestBtn.style.opacity = 0.5;
            } else {
                // Kalau yes: hidupkan lagi tombolnya
                addGuestBtn.disabled = false;
                addGuestBtn.style.opacity = 1;

                // Kalau sebelumnya kosong, bikin minimal 1 row
                if (guestList.querySelectorAll('.guest-row').length === 0) {
                    const row = createGuestRow(0);
                    guestList.appendChild(row);
                    bindRemove(row.querySelector('.remove'));
                }
            }
        });
    });
