@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 pb-8" x-data="{ 
    cart: [],
    loading: true,
    init() {
        this.cart = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
        this.loading = false;
        window.addEventListener('cart-updated', () => {
            this.cart = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
        });
    },
    updateQty(id, delta) {
        let item = this.cart.find(i => i.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                this.removeItem(id);
            } else {
                this.saveCart();
            }
        }
    },
    removeItem(id) {
        this.cart = this.cart.filter(i => i.id !== id);
        this.saveCart();
    },
    saveCart() {
        localStorage.setItem(window.CART_KEY, JSON.stringify(this.cart));
        window.dispatchEvent(new CustomEvent('cart-updated'));
    },
    get total() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    }
}">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Your Cart 🛒</h1>
        <p class="text-gray-600 mt-2" x-show="cart.length > 0">Review your items before proceeding.</p>
    </div>

    <!-- Empty State -->
    <template x-if="!loading && cart.length === 0">
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100">
            <div class="w-24 h-24 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-8">Looks like you haven't added anything yet.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-full font-bold hover:opacity-90 transition-all">Start Shopping</a>
        </div>
    </template>

    <!-- Cart Items -->
    <div class="space-y-4" x-show="cart.length > 0">
        <template x-for="item in cart" :key="item.id">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-50 flex gap-4 items-center animate-in slide-in-from-bottom-4 duration-300">
                <img :src="item.thumbnail" class="w-20 h-20 rounded-xl object-cover bg-gray-50" :alt="item.title">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-gray-900 truncate" x-text="item.title"></h3>
                    <p class="text-primary font-bold mt-1" x-text="'₹' + item.price"></p>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <button @click="removeItem(item.id)" class="bg-red-50 text-red-500 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Static Bottom Checkout -->
    <div class="mt-8 mb-8 md:mb-0 md:mt-12" x-show="cart.length > 0" x-cloak>
        <div class="max-w-3xl mx-auto">
            <div class="bg-gray-900 text-white p-6 rounded-3xl shadow-2xl flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Amount</p>
                    <p class="text-3xl font-black" x-text="'₹' + total"></p>
                </div>
                <button id="rzp-cart-button" class="bg-white text-gray-900 px-8 py-4 rounded-2xl font-bold hover:bg-gray-100 transition-all shadow-lg border border-white/10 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-400"></i> Checkout Now
                </button>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('rzp-cart-button').onclick = function(e) {
            const cartItems = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
            if (cartItems.length === 0) {
                alert("Your cart is empty.");
                return;
            }

            fetch("{{ route('razorpay.cart.order') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    items: cartItems.map(item => ({
                        id: item.id,
                        price: item.price
                    }))
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                var options = {
                    "key": data.key,
                    "amount": data.amount,
                    "currency": "INR",
                    "name": "{{ $siteName }}",
                    "description": "Cart Checkout (" + cartItems.length + " items)",
                    "order_id": data.order_id,
                    "handler": function (response) {
                        // Verify payment
                        fetch("{{ route('razorpay.verify') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        })
                        .then(res => res.json())
                        .then(verifyData => {
                            if (verifyData.success) {
                                // Clear cart
                                localStorage.removeItem(window.CART_KEY);
                                window.dispatchEvent(new CustomEvent('cart-updated'));
                                window.location.href = "{{ route('library') }}";
                            } else {
                                alert("Payment verification failed. Please contact support.");
                            }
                        });
                    },
                    "prefill": {
                        "name": data.user_name,
                        "email": data.user_email
                    },
                    "theme": {
                        "color": "{{ $brandColor }}"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            });
            e.preventDefault();
        }
    </script>
</div>
@endsection
