<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import Select from '@/Components/Select.vue';
import Modal from '@/Components/Modal.vue';
import { watch } from 'vue';

const props = defineProps({
  contact: {
    type: Object,
    required: false,
  },
  show: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(['close', 'submit']);
const { contact } = props;

const form = useForm({
  first_name: contact?.first_name ?? '',
  last_name: contact?.last_name ?? '',
})

watch(() => props.contact, () => {
  const { contact } = props;

  form.first_name = contact?.first_name ?? '';
  form.last_name = contact?.last_name ?? '';
  form.clearErrors();
});

const closeModal = () => {
  emit('close');
}

const submit = () => {
  const data = form.data();
  const { contact } = props;
  form.clearErrors();
  if(!data.first_name) {
    form.errors.first_name = 'First name is required';
    return;
  }
  if(!data.last_name) {
    form.errors.last_name = 'Last name is required';
    return;
  }
  emit('submit', { ...data, id: contact?.id });
  closeModal();
}
</script>

<template>
  <section>
    <Modal :show="show" @close="closeModal" max-width="lg">
      <div class="p-6">
        <div>
          <button @click="closeModal" class="px-4 py-2 text-sm bg-gray-200 rounded-md mb-4 me-2">
            Back
          </button>
          <button @click="submit" class="px-4 py-2 text-sm text-white bg-blue-500 rounded-md mb-4">
            Save
          </button>
        </div>
        <div class="">
          <fieldset class="border p-4 rounded-lg flex flex-col gap-4">
            <legend>General</legend>
            <div>
              <InputLabel for="first_name" value="First Name" />

              <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required
                autofocus autocomplete="first_name" />

              <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div>
              <InputLabel for="last_name" value="Last Name" />

              <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required
                autofocus autocomplete="last_name" />

              <InputError class="mt-2" :message="form.errors.last_name" />
            </div>
          </fieldset>

        </div>
      </div>
    </Modal>
  </section>
</template>
