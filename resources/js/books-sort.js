document.addEventListener('DOMContentLoaded', function() {
    const handleSortClick = (event) => {
        event.preventDefault();
        const sortLink = event.currentTarget;
        
        const spinner = document.createElement('span');
        spinner.className = 'spinner-border spinner-border-sm ms-2';
        sortLink.appendChild(spinner);
        
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
                
                const newTableBody = doc.querySelector('tbody');
                const newPagination = doc.querySelector('.pagination');
                
                if (newTableBody) {
                    document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
                }
                
                if (newPagination) {
                    document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                }
                
                window.history.pushState({}, '', url.toString());
            })
            .catch(error => {
                console.error('Error fetching sorted data:', error);
                window.location.href = url.toString();
            })
            .finally(() => {
                spinner.remove();
            });
    };
    
    document.querySelectorAll('.sort-link').forEach(link => {
        link.addEventListener('click', handleSortClick);
    });
    
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