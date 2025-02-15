<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    authorOptions: {
        type: Object,
    },
})

const form = useForm({
    title: '',
    price: '',
    authors: [],
    image: null,
});

const imagePreview = ref(null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(route('book.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
            <h1 class="text-xl font-semibold mb-4">Add New Book</h1>

            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Title</label>
                    <TextInput v-model="form.title" class="w-full mt-1" placeholder="Title" />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Price</label>
                    <NumberInput v-model="form.price" class="w-full mt-1" placeholder="Price" />
                    <InputError :message="form.errors.price" class="mt-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Authors</label>
                    <SelectInput v-model="form.authors" :options="props.authorOptions" class="w-full mt-1" :multiple="true" />
                    <InputError :message="form.errors.authors" class="mt-2" />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Image</label>
                    <input type="file" @change="handleImageUpload" class="w-full mt-1" accept="image/*" />
                    <InputError :message="form.errors.image" class="mt-2" />
                    <div v-if="imagePreview" class="mt-2">
                        <img :src="imagePreview" alt="Preview" class="h-32 object-cover rounded-lg" />
                    </div>
                </div>

                <div class="flex justify-end">
                    <PrimaryButton :disabled="form.processing" type="submit">
                        Save
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
