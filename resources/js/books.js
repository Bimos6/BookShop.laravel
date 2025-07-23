// Плавная загрузка изображений
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

    // Обработка сортировки
    document.querySelectorAll('[data-sort]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    });
});