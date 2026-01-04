<template>
    <Head title="Shop" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Shop
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                         <h1 class="text-2xl font-bold mb-6">
                            Available products
                        </h1>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                            v-for="product in products"
                            :key="product.id"
                            class="border p-4 rounded shadow"
                            >
                            <h2 class="font-bold text-lg">
                                {{ product.name }}
                            </h2>

                            <p class="mt-1">
                                Price: <strong>${{ product.price }}</strong>
                            </p>

                            <p class="text-sm text-gray-500">
                                In stock: {{ product.stock }}
                            </p>

                            <button
                                @click="addToCart(product.id)"
                                class="mt-4 w-full bg-indigo-600 px-4 py-2 text-white rounded hover:bg-indigo-700"
                            >
                                Add to cart
                            </button>
                            </div>
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
import { ref, onMounted } from 'vue';
import axios from 'axios';

const { props } = usePage()
const products = ref([]);
const cartCount = ref(props.cart_count ?? 0);

onMounted(async () => {
  try {
    const response = await axios.get(route('products.json'));
    products.value = response.data.products;
  } catch (error) {
    console.log(error);
  }
});

const addToCart = (productId) => {
    router.post(route('cart.add'), {
        product_id: productId,
        quantity: 1
    }, {
        preserveState: true, 
        onSuccess: (page) => {
            cartCount.value = page.props.cart_count;
        },
    });
};
</script>
