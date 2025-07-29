document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.book-image');
    
    images.forEach(img => {
        if (img.complete) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
            img.addEventListener('error', function() {
                this.parentElement.innerHTML = `
                    <div class="text-center text-muted p-4">
                        <i class="bi bi-image fs-1"></i>
                        <p>Ошибка загрузки</p>
                    </div>
                `;
            });
        }
    });

    document.querySelectorAll('[data-sort]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const adminModeSwitch = document.getElementById('adminModeSwitch');
    
    if (adminModeSwitch) {
        adminModeSwitch.addEventListener('change', function() {
            const checkAdminModeUrl = `${window.location.origin}/admin/check-admin-mode`;
            const toggleAdminModeUrl = `${window.location.origin}/admin/toggle-admin-mode`;
            
            fetch(toggleAdminModeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    admin_mode: this.checked
                })
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        });

        setInterval(() => {
            fetch(`${window.location.origin}/admin/check-admin-mode`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const currentMode = sessionStorage.getItem('admin_mode') === 'true';
                    if (data.admin_mode !== currentMode) {
                        sessionStorage.setItem('admin_mode', data.admin_mode);
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error checking admin mode:', error));
        }, 2000);
    }
});
