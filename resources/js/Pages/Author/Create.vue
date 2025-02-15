<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AuthorForm from "@/Pages/Author/AuthorForm.vue";
import {useForm} from '@inertiajs/vue3';

const createAuthor = (form) => {
    form.post(route('author.store'));
};
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
            <h1 class="text-xl font-semibold mb-4">Add New Author</h1>
            <AuthorForm :initialData="{ first_name: '', last_name: '' }" :submitAction="createAuthor"/>
        </div>
    </AppLayout>
</template>
