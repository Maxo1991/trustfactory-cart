<template>
  <Head title="Products" />

  <AuthenticatedLayout>
    <template #header>
      <div class="w-full flex justify-center">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Products
        </h2>
      </div>
    </template>

    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">
        {{ editingProduct ? 'Edit Product' : 'Create Product' }}
      </h1>

      <div v-if="isAdmin" class="mb-6 flex flex-wrap items-end gap-4">
        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Name</label>
          <input
            v-model="form.name"
            type="text"
            class="border p-2 rounded w-48"
            placeholder="Product name"
          />
          <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Price</label>
          <input
            v-model.number="form.price"
            type="number"
            class="border p-2 rounded w-32"
            placeholder="0.00"
          />
          <span v-if="form.errors.price" class="text-red-500 text-sm">{{ form.errors.price }}</span>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Stock</label>
          <input
            v-model.number="form.stock"
            type="number"
            class="border p-2 rounded w-32"
            placeholder="0"
          />
          <span v-if="form.errors.stock" class="text-red-500 text-sm">{{ form.errors.stock }}</span>
        </div>

        <button
          @click="submitProduct"
          :disabled="form.processing"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          {{ editingProduct ? 'Update Product' : 'Add Product' }}
        </button>
        <button
          v-if="editingProduct"
          @click="cancelEdit"
          class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
          Cancel
        </button>
      </div>
      <div class="grid grid-cols-3 gap-4">
        <div v-for="product in products" :key="product.id" class="border p-4 rounded shadow">
          <h2 class="font-bold">{{ product.name }}</h2>
          <p>Price: ${{ product.price }}</p>
          <p>Stock: {{ product.stock }}</p>
          <div v-if="isAdmin" class="mt-2 flex gap-2">
            <button @click="startEdit(product)" class="bg-yellow-500 px-2 py-1 text-white rounded">
              Edit
            </button>
            <button @click="deleteProduct(product.id)" class="bg-red-500 px-2 py-1 text-white rounded">
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from 'axios'

const { props } = usePage()
const products = props.products
const isAdmin = props.auth.user.role === 'admin'

const form = useForm({
  name: '',
  price: 0,
  stock: 0
})
let editingProduct = null

const submitProduct = async () => {
  try {
    if (editingProduct) {
      await axios.put(route('products.update', editingProduct.id), {
        name: form.name,
        price: form.price,
        stock: form.stock
      })
    } else {
      await axios.post(route('products.store'), {
        name: form.name,
        price: form.price,
        stock: form.stock
      })
    }

    form.name = ''
    form.price = 0
    form.stock = 0
    editingProduct = null
    window.location.reload()
  } catch (error) {
    console.log(error.response?.data?.errors || error)
  }
}

const startEdit = (product) => {
  editingProduct = product
  form.name = product.name
  form.price = product.price
  form.stock = product.stock
}

const cancelEdit = () => {
  editingProduct = null
  form.name = ''
  form.price = 0
  form.stock = 0
}

const deleteProduct = async (id) => {
  if (!confirm('Are you sure?')) return
  try {
    await axios.delete(route('products.destroy', id))
    window.location.reload()
  } catch (error) {
    console.log(error.response?.data?.errors || error)
  }
}
</script>
