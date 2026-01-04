<template>
    <Head title="Cart" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Your Cart
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow rounded">

                    <div v-if="cart.length === 0">
                        Your cart is empty.
                    </div>

                    <table v-else class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left p-2">Product</th>
                                <th class="text-left p-2">Price</th>
                                <th class="text-left p-2">Qty</th>
                                <th class="text-left p-2">Subtotal</th>
                                <th class="text-left p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in cart"
                                :key="item.id"
                                class="border-b"
                            >
                                <td class="p-2">{{ item.name }}</td>
                                <td class="p-2">${{ item.price }}</td>
                                <td class="p-2">
                                    <div class="flex items-center gap-2">
                                        <button
                                            @click="updateQty(item.id, item.quantity - 1)"
                                            class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                            :disabled="item.quantity <= 1"
                                        >
                                            −
                                        </button>

                                        <span class="w-6 text-center">
                                            {{ item.quantity }}
                                        </span>

                                        <button
                                            @click="updateQty(item.id, item.quantity + 1)"
                                            class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"
                                        >
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="p-2">
                                    ${{ item.price * item.quantity }}
                                </td>
                                <td class="p-2">
                                    <button
                                        @click="removeItem(item.id)"
                                        class="text-red-600 hover:underline"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div
                        v-if="cart.length"
                        class="mt-6 text-right font-bold"
                    >
                        Total: ${{ total }}
                    </div>

                    <!-- Dugme za checkout -->
                    <div v-if="cart.length" class="mt-4 text-right">
                        <button
                            @click="goToCheckout"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                        >
                            Proceed to Checkout
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3';

defineProps({
    cart: Array,
    total: Number
})

const updateQty = (productId, quantity) => {
    router.patch(route('cart.update', { product: productId }), {
        quantity: quantity
    });
};

const removeItem = (productId) => {
    router.delete(route('cart.remove', { product: productId }));
};

const goToCheckout = () => {
    router.get(route('checkout.index'));
};
</script>
