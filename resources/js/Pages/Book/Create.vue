<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import BookForm from "@/Pages/Book/Form.vue";

const props = defineProps({
    book: {
        type: Object
    },
    authorOptions: {
        type: Object,
        required: true
    },
})

const form = useForm({
    title: '',
    price: '',
    authors: [],
    image: null,
});



const createBook = (form) => {
    form.post(route('book.store'));
};
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
            <h1 class="text-xl font-semibold mb-4">Add New Book</h1>

            <BookForm :book="book" :authorOptions="authorOptions" :submitAction="createBook" />

        </div>
    </AppLayout>
</template>
