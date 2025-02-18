<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BookForm from "@/Pages/Book/Form.vue";

const props = defineProps({
    book: {
        type: Object,
        required: true
    },
    authorOptions: {
        type: Object,
        required: true
    }
});

const form = useForm({
    title: props.book.title,
    price: props.book.price,
    authors: props.book.authors.map(author => author.id),
    image: null,
});


const updateBook = (form) => {
    form.post(route('book.update_post', props.book.id));
};

</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
            <h1 class="text-xl font-semibold mb-4">Edit Book</h1>

            <BookForm :book="book" :authorOptions="authorOptions" :submitAction="updateBook" />

        </div>
    </AppLayout>
</template>
