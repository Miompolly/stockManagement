// DOM Elements
document.addEventListener('DOMContentLoaded', function() {
    // Product Form Handling
    const productForm = document.querySelector('#productForm');
    if(productForm) {
        productForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(productForm);
            try {
                const response = await fetch('actions/product_actions.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if(data.success) {
                    alert('Product saved successfully');
                    location.reload();
                }
            } catch(err) {
                console.error('Error:', err);
            }
        });
    }

    // Delete Product Handling
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            if(confirm('Are you sure you want to delete this item?')) {
                const id = e.target.dataset.id;
                try {
                    const response = await fetch(`actions/product_actions.php?delete=${id}`);
                    const data = await response.json();
                    if(data.success) {
                        e.target.closest('tr').remove();
                    }
                } catch(err) {
                    console.error('Error:', err);
                }
            }
        });
    });

    // Stock Movement Handling
    const stockForm = document.querySelector('#stockForm');
    if(stockForm) {
        stockForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(stockForm);
            try {
                const response = await fetch('actions/stock_actions.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if(data.success) {
                    alert('Stock updated successfully');
                    location.reload();
                }
            } catch(err) {
                console.error('Error:', err);
            }
        });
    }
});
