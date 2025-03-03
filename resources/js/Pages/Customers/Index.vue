<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import CustomerForm from './Partials/CustomerForm.vue';
import { ref } from 'vue';
import axios from 'axios';

defineProps({
  customers: {
    type: Array,
    required: true,
  },
  categories: {
    type: Array,
    required: false,
  },
});

const showForm = ref(false);
const customer = ref(null);

const closeModal = () => {
  showForm.value = false;
}

const openModal = async (customerId) => {
  if (customerId) {
    const response = await axios.get(`/api/customers/${customerId}`)
    customer.value = response.data;
  } else {
    customer.value = null;
  }

  showForm.value = true;
}

const deleteCustomer = (customerId) => {
  if (confirm('Are you sure you want to delete this customer?')) {
    router.delete(`/customers/${customerId}`);
  }
}
</script>

<template>

  <Head title="Customers" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Customers
        </h2>
        <button @click="() => openModal()" class="px-4 py-2 text-sm text-white bg-blue-500 rounded-md">
          Create
        </button>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mb-4" v-if="$page.props.flash.success">
          <div class="p-4 text-green-500 bg-green-100">
            {{ $page.props.flash.success }}
          </div>
        </div>
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <table class="w-full text-center">
              <thead>
                <tr>
                  <th class="py-4">Name</th>
                  <th class="py-4">Reference</th>
                  <th class="py-4">Category</th>
                  <th class="py-4">No of Contacts</th>
                  <th class="py-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="customer in customers" :key="customer.id">
                  <td class="py-4 border-t">{{ customer.name }}</td>
                  <td class="py-4 border-t">{{ customer.reference }}</td>
                  <td class="py-4 border-t">{{ customer.category?.name }}</td>
                  <td class="py-4 border-t">{{ customer.contacts_count }}</td>
                  <td class="py-4 border-t">
                    <div class="flex divide-x mx-auto justify-center">
                      <button @click="() => openModal(customer.id)" class="px-2">
                        Edit
                      </button>
                      <button @click="() => deleteCustomer(customer.id)" class="px-2">
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <CustomerForm :show="showForm" :categories="categories" :customer="customer" @close="closeModal()" />
  </AuthenticatedLayout>
</template>
