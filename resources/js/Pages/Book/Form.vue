<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, defineProps, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    book: {
        type: Object,
        default: () => ({ title: '', price: undefined, authors: [], image_url: null })
    },
    authorOptions: {
        type: Object,
        required: true
    },
    submitAction: Function,
});


const form = useForm({
    title: props.book.title,
    price: props.book.price,
    authors: props.book.authors.map(author => author.id),
    image: null,
});

const imagePreview = ref(props.book.image_url);

watch(() => props.book, (newBook) => {
    form.title = newBook.title;
    form.price = newBook.price;
    form.authors = newBook.authors.map(author => author.id);
    imagePreview.value = newBook.image_url;
}, { deep: true });

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
    props.submitAction(form);
};
</script>

<template>
    <form @submit.prevent="submit" enctype="multipart/form-data" >
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
</template>
