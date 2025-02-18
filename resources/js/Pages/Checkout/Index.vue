<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessMessage from "@/Components/SuccessMessage.vue";

const page = usePage();
const cartItems = ref(page.props.cart || []);
const successMessage = ref(page.props.flash?.success || '');
const totalPrice = ref(page.props.totalPrice || 0);

function updateCart(item, quantity) {
    if (quantity < 1) return;
    router.post(route('cart.update'), { id: item.id, quantity }, {
        onSuccess: (response) => {
            cartItems.value = response.props.cart;
            totalPrice.value = response.props.totalPrice;
        }
    });
}

function removeFromCart(item) {
    router.post(route('cart.remove'), { id: item.id }, {
        onSuccess: (response) => {
            cartItems.value = response.props.cart;
            totalPrice.value = response.props.totalPrice;
        }
    });
}

/*
function checkout() {
    router.post(route('order.store'), {}, {
        onSuccess: (response) => {
            successMessage.value = "Order placed successfully!";
            cartItems.value = [];
            totalPrice.value = 0;
        }
    });
}

 */
</script>

<template>
    <AppLayout>
        <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Checkout</h1>
            <SuccessMessage v-if="successMessage">{{ successMessage }}</SuccessMessage>

            <div v-if="cartItems.length > 0">
                <div v-for="item in cartItems" :key="item.id" class="flex justify-between items-center border-b py-4">
                    <div class="flex items-center gap-4">
                        <img :src="item.image_url" alt="Book Cover" class="w-16 h-16 object-cover rounded-md">
                        <div>
                            <h3 class="text-lg font-bold">{{ item.title }}</h3>
                            <p class="text-gray-600">Price: {{ item.price }} Kč</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="() => updateCart(item, item.quantity - 1)" class="px-2 py-1 bg-gray-300 rounded">-</button>
                        <span class="text-lg font-bold">{{ item.quantity }}</span>
                        <button @click="() => updateCart(item, item.quantity + 1)" class="px-2 py-1 bg-gray-300 rounded">+</button>
                    </div>
                    <p class="text-lg font-bold">{{ item.total }} Kč</p>
                    <button @click="removeFromCart(item)" class="text-red-600 hover:text-red-800">Remove</button>
                </div>

                <div class="mt-6 text-right">
                    <h2 class="text-xl font-bold">Total: {{ totalPrice }} Kč</h2>
                    <a :href="route('order.create')" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-4">Place Order</a>
                </div>
            </div>
            <div v-else class="text-center text-gray-500">Your cart is empty.</div>
        </div>
    </AppLayout>
</template>
