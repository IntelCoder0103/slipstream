<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import Select from '@/Components/Select.vue';
import Modal from '@/Components/Modal.vue';
import { watch } from 'vue';

const props = defineProps({
  categories: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(['submit']);

const form = useForm({
  category_id: '',
  search: '',
});

const { categories } = props;
const options = categories.map(category => ({
  label: category.name,
  value: category.id,
}))

const applyFilters = () => {
  router.get("/customers", form.data(), { preserveState: true, replace: true });
};

const clear = () => {
  form.reset();
}
</script>

<template>
  <section>
    <div class="flex gap-4 mb-4 items-center">
      <div>
        <InputLabel for="search" value="Search" />

        <TextInput id="search" type="text" class="mt-1 block w-full" v-model="form.search" required autofocus placeholder="Search"
          autocomplete="search" />

        <InputError class="mt-2" :message="form.errors.search" />
      </div>
      <div>
        <InputLabel for="category_id" value="Category" />

        <Select id="category_id" class="mt-1 block w-full" v-model="form.category_id" required autofocus :options="options"
          autocomplete="category_id" />

        <InputError class="mt-2" :message="form.errors.category_id" />
      </div>
      <div class="grow"></div>
      <button @click="clear" class="px-4 py-2 text-sm bg-gray-200 rounded-md mb-4 me-2">
        Clear
      </button>
      <button @click="applyFilters" class="px-4 py-2 text-sm text-white bg-blue-500 rounded-md mb-4">
        Apply
        </button>
    </div>
  </section>
</template>
