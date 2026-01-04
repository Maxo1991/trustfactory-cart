<template>  
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 text-center">
        Orders
      </h2>
    </template>

    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">All Orders</h1>

      <div v-if="isAdmin">
        <div v-if="orders.length === 0" class="text-gray-500">
          No orders found.
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="order in orders" :key="order.id" class="border p-4 rounded shadow">
            <h2 class="font-bold mb-2">Order #{{ order.id }}</h2>
            <p><strong>User:</strong> {{ order.user.name }} ({{ order.user.email }})</p>
            <p><strong>Total:</strong> ${{ order.total }}</p>
            <p><strong>Status:</strong> {{ order.status }}</p>
            <p><strong>Created at:</strong> {{ new Date(order.created_at).toLocaleString() }}</p>

            <div class="mt-2 flex gap-2">
              <button 
                v-if="order.status === 'pending'" 
                @click="markAsCompleted(order.id)"
                class="bg-green-500 px-2 py-1 text-white rounded hover:bg-green-600"
              >
                Mark as Completed
              </button>
              <button
                v-if="order.status === 'pending'"
                @click="markAsCancelled(order.id)"
                class="bg-red-500 px-2 py-1 text-white rounded"
              >
                Cancel Order
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-red-500">
        You do not have access to view orders.
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const { props } = usePage()
const orders = props.orders || []
const isAdmin = props.auth.user.role === 'admin'

const markAsCompleted = async (id) => {
  if(!confirm('Mark this order as completed?')) return

  try {
    await axios.put(route('orders.update', { order: id }), { status: 'completed' })
    
    const index = orders.value.findIndex(o => o.id === id)
    if (index !== -1) {
      orders.value[index].status = 'completed'
    }

  } catch (error) {
    console.log(error)
  } finally {
    window.location.reload()
  }
}

const markAsCancelled = async (id) => {
  if(!confirm('Mark this order as Cancelled?')) return

  try {
    await axios.put(route('orders.update', { order: id }), { status: 'cancelled' })
    
    const index = orders.value.findIndex(o => o.id === id)
    if (index !== -1) {
      orders.value[index].status = 'cancelled'
    }

  } catch (error) {
    console.log(error)
  } finally {
    window.location.reload()
  }
}
</script>
