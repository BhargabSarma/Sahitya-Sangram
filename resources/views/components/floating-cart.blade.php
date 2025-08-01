<div id="floating-cart"
    class="fixed bottom-6 right-6 z-50 flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-full shadow-lg cursor-pointer transition hover:bg-indigo-700"
    onclick="window.location.href='{{ route('cart.index') }}'">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4" stroke-linecap="round" stroke-linejoin="round" />
        <circle cx="9" cy="21" r="1" />
        <circle cx="20" cy="21" r="1" />
    </svg>
    <span class="font-bold text-lg" id="cart-count">{{ session('cart_count', 0) }}</span>
</div>
<script>
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const bookId = this.dataset.bookId;
            const type = this.dataset.type;   // Make sure your button has data-type
            const price = this.dataset.price; // Make sure your button has data-price

            fetch(`/cart/add/${bookId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ type, price })
            })
                .then(res => {
                    if (res.status === 401) {
                        window.location.href = '/login';
                        return;
                    }
                    return res.json();
                })
                .then(data => {
                    if (data && data.cart_count !== undefined) {
                        document.getElementById('cart-count').textContent = data.cart_count;
                    }
                });
        });
    });
</script>