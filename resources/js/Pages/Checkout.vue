<template>
    <Head title="Checkout" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Checkout
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-6">Your Order</h1>

                        <div v-if="cart.length">
                            <table class="w-full mb-6 border">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left p-2">Product</th>
                                        <th class="p-2">Quantity</th>
                                        <th class="p-2">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in cart" :key="item.id" class="border-b">
                                        <td class="p-2">{{ item.name }}</td>
                                        <td class="text-center p-2">{{ item.quantity }}</td>
                                        <td class="text-right p-2">${{ item.price * item.quantity }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="text-right font-bold mb-4">
                                Total: ${{ total }}
                            </div>

                            <button
                                @click="pay"
                                class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700"
                            >
                                Pay
                            </button>
                        </div>

                        <div v-else>
                            <p>Your cart is empty.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';

const { props } = usePage()
const cart = props.cart ?? [];
const total = props.total ?? 0;

const pay = () => {
    router.post(route('checkout.store'));
}
</script>
