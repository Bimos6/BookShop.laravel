document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для сортировки
    const handleSortClick = (event) => {
        event.preventDefault();
        const sortLink = event.currentTarget;
        
        // Показываем индикатор загрузки
        const spinner = document.createElement('span');
        spinner.className = 'spinner-border spinner-border-sm ms-2';
        sortLink.appendChild(spinner);
        
        // Получаем URL из href ссылки
        const url = new URL(sortLink.href);
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Обновляем только таблицу и пагинацию
                const newTableBody = doc.querySelector('tbody');
                const newPagination = doc.querySelector('.pagination');
                
                if (newTableBody) {
                    document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
                }
                
                if (newPagination) {
                    document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                }
                
                // Обновляем URL в браузере без перезагрузки
                window.history.pushState({}, '', url.toString());
            })
            .catch(error => {
                console.error('Error fetching sorted data:', error);
                // В случае ошибки выполняем обычный переход
                window.location.href = url.toString();
            })
            .finally(() => {
                // Убираем индикатор загрузки
                spinner.remove();
            });
    };
    
    // Назначаем обработчики на все ссылки сортировки
    document.querySelectorAll('.sort-link').forEach(link => {
        link.addEventListener('click', handleSortClick);
    });
    
    // Обработчик для кнопок пагинации (опционально)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const paginationLink = e.target.closest('a');
            
            fetch(paginationLink.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    document.querySelector('tbody').innerHTML = doc.querySelector('tbody').innerHTML;
                    document.querySelector('.pagination').innerHTML = doc.querySelector('.pagination').innerHTML;
                    window.history.pushState({}, '', paginationLink.href);
                });
        }
    });
});