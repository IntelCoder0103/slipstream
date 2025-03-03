<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import Select from '@/Components/Select.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import ContactForm from './ContactForm.vue';

const props = defineProps({
  categories: {
    type: Array,
    required: true,
  },
  customer: {
    type: Object,
    required: false,
  },
  show: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(['close']);
const { customer, categories } = props;

const showContactForm = ref(false);
const contactForm = ref(null);
const form = useForm({
  name: customer?.name ?? '',
  reference: customer?.reference ?? '',
  category_id: customer?.category_id ?? '',
  start_date: customer?.start_date ?? '',
  description: customer?.description ?? '',
  contacts: customer?.contacts ?? [],
})

watch(() => props.customer?.id, () => {
  const { customer } = props;
  console.log({ customer });
  form.name = customer?.name ?? '';
  form.reference = customer?.reference ?? '';
  form.category_id = customer?.category_id ?? '';
  form.start_date = customer?.start_date ?? '';
  form.description = customer?.description ?? '';
  form.contacts = customer?.contacts ?? [];
  form.clearErrors();
});

const options = categories.map(category => ({
  label: category.name,
  value: category.id,
}))

const closeModal = () => {
  emit('close');
}

const openContactModal = (contact) => {
  showContactForm.value = true;
  contactForm.value = contact;
}

const closeContactModal = () => {
  showContactForm.value = false;
}

const removeContact = (index) => {
  form.contacts.splice(index, 1);
}

const saveContact = (contactForm) => {
  console.log({ contactForm });
  if (contactForm.id) {
    const index = form.contacts.findIndex(contact => contact.id === contactForm.id);
    form.contacts[index] = contactForm;
  } else {
    form.contacts.push(contactForm);
  }
}

const saveCustomer = () => {
  const { customer } = props;
  if (!customer?.id) {
    form.post(route('customers.store'), {
      preserveScroll: true,
      preserveState: "errors",
      onSuccess: () => closeModal(),
      onError: (err) => {
        form.setError(err);
      },
    });
  } else {
    form.put(route('customers.update', customer.id), {
      preserveScroll: true,
      preserveState: "errors",
      onSuccess: () => closeModal(),
      onError: (err) => form.setError(err),
    });
  }
}

const goBack = () => {
  if (form.isDirty) {
    if (confirm('Are you sure you want to leave?')) {
      closeModal();
    }
  } else {
    closeModal();
  }
}

</script>

<template>
  <section>
    <Modal :show="show" @close="closeModal">
      <div class="p-6">
        <div>
          <button @click="goBack" class="px-4 py-2 text-sm bg-gray-200 rounded-md mb-4 me-2">
            Back
          </button>
          <button class="px-4 py-2 text-sm text-white bg-blue-500 rounded-md mb-4" @click="saveCustomer">
            Save
          </button>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <fieldset class="border p-4 rounded-lg flex flex-col gap-4">
            <legend>General</legend>
            <div>
              <InputLabel for="name" value="Name" />

              <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                autocomplete="name" />

              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
              <InputLabel for="reference" value="Reference" />

              <TextInput id="reference" type="text" class="mt-1 block w-full" v-model="form.reference" required
                autofocus autocomplete="reference" />

              <InputError class="mt-2" :message="form.errors.reference" />
            </div>

            <div>
              <InputLabel for="category" value="Category" />

              <Select id="category" class="mt-1 block w-full" v-model="form.category_id" :options="options" required
                autofocus autocomplete="category" />

              <InputError class="mt-2" :message="form.errors.category_id" />
            </div>
          </fieldset>
          <fieldset class="border p-4 rounded-lg flex flex-col gap-4">
            <legend>Details</legend>
            <div>
              <InputLabel for="start_date" value="Start Date" />

              <TextInput id="start_date" type="date" class="mt-1 block w-full" v-model="form.start_date" required
                autofocus autocomplete="start_date" />

              <InputError class="mt-2" :message="form.errors.start_date" />
            </div>

            <div>
              <InputLabel for="description" value="Description" />

              <TextArea id="description" type="text" class="mt-1 block w-full" v-model="form.description" required
                autofocus autocomplete="description" rows="4" />

              <InputError class="mt-2" :message="form.errors.description" />
            </div>
          </fieldset>
          <fieldset class="border p-4 rounded-lg col-span-2">
            <legend>Contacts</legend>
            <div>
              <button @click="() => openContactModal({})" class="px-4 py-2 text-sm text-white bg-blue-500 rounded-md ml-auto mb-2 block">Create</button>
            </div>
            <div>
              <table class="w-full text-center">
                <thead>
                  <tr>
                    <th class="py-4">First Name</th>
                    <th class="py-4">Last Name</th>
                    <th class="py-4">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(contact, index) in form.contacts" :key="index">
                    <td class="py-4 border-t">{{ contact.first_name }}</td>
                    <td class="py-4 border-t">{{ contact.last_name }}</td>
                    <td class="py-4 border-t">
                      <div class="flex divide-x mx-auto justify-center">
                        <button @click="() => openContactModal(contact)" class="px-2">
                          Edit
                        </button>
                        <button @click="() => removeContact(index)" class="px-2">
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="form.contacts.length === 0">
                    <td class="py-4 border-t" colspan="3">No contacts found</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </fieldset>
        </div>
      </div>
    </Modal>

    <ContactForm :show="showContactForm" @close="closeContactModal" :contact="contactForm" @submit="saveContact"/>
  </section>
</template>
