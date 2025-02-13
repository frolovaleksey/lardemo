<script setup>
import { ref, watch } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    canAddAuthor: {
        type: Boolean,
    },
    authors: {
        type: Object,
    },
    filters: {
        type: Object,
    },
    translations: {
        type: Object,
    },
});

const page = usePage();
const successMessage = ref(page.props.flash?.success || '');
console.log(page.props.flash.success);
const id = ref(props.filters.id || '');
const title = ref(props.filters.title || '');

watch(title, (value) => {
    router.get('/authors', { id: id.value, title: value }, { preserveState: true, replace: true });
});

watch(id, (value) => {
    router.get('/authors', { id: value, title: title.value }, { preserveState: true, replace: true });
});
</script>

<template>
    <AppLayout>
        <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{ props.translations.title }}</h1>

            <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ successMessage }}
            </div>

            <div class="mb-6 flex flex-col md:flex-row gap-4" v-if="canAddAuthor">
                <a
                    :href="route('author.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    Add Author
                </a>
            </div>

            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <input
                    v-model="id"
                    type="text"
                    :placeholder="props.translations.first_name"
                    class="w-full md:w-1/2 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <input
                    v-model="title"
                    type="text"
                    :placeholder="props.translations.last_name"
                    class="w-full md:w-1/2 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                <ul>
                    <li v-for="author in authors.data" :key="author.id"
                        class="p-3 border-b last:border-b-0 flex justify-between items-center hover:bg-gray-100 transition">
                        <span class="text-lg font-semibold text-gray-700">#{{ author.id }}</span>
                        <span class="text-gray-800">{{ author.first_name }}</span>
                        <span class="text-gray-800">{{ author.last_name }}</span>
                    </li>
                </ul>
            </div>

            <div class="mt-6 flex justify-between">
                <button
                    v-if="authors.prev_page_url"
                    @click="router.get(authors.prev_page_url)"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                >
                    Prev
                </button>

                <button
                    v-if="authors.next_page_url"
                    @click="router.get(authors.next_page_url)"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                >
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>
