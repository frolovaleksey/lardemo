<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AuthorForm from "@/Pages/Author/AuthorForm.vue";
import {useForm} from '@inertiajs/vue3';

const props = defineProps({
    author: Object
});

const updateAuthor = (form) => {
    form.put(route('author.update', props.author.id));
};
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
            <h1 class="text-xl font-semibold mb-4">Edit Author</h1>
            <AuthorForm :initialData="props.author" :submitAction="updateAuthor"/>
        </div>
    </AppLayout>
</template>
