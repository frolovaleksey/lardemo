<script setup>
import { ref, watch } from 'vue';
import {usePage, router, Link} from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    books:{
        type: Object,
    },
    filters:{
        type: Object,
    },
    translations:{
        type: Object,
    },
});

const id = ref(props.filters.id || '');
const title = ref(props.filters.title || '');

watch(title, (value) => {
    router.get('/books', { id: id.value, title: value }, { preserveState: true, replace: true });
});

watch(id, (value) => {
    router.get('/books', { id: value, title: title.value }, { preserveState: true, replace: true });
});
</script>

<template>
    <AppLayout>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{props.translations.book_list}}</h1>


        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <input
                v-model="id"
                type="number"
                :placeholder="props.translations.filter_id"
                class="w-full md:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <input
                v-model="title"
                type="text"
                :placeholder="props.translations.filter_title"
                class="w-full md:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
        </div>


        <div class="bg-gray-50 p-4 rounded-lg shadow-md">
            <ul>
                <li v-for="book in books.data" :key="book.id"
                    class="p-3 border-b last:border-b-0 flex justify-between items-center hover:bg-gray-100 transition">
                    <span class="text-lg font-semibold text-gray-700">#{{ book.id }}</span>
                    <span class="text-gray-800">{{ book.title }}</span>
                </li>
            </ul>
        </div>


        <div class="mt-6 flex justify-between">
            <button
                v-if="books.prev_page_url"
                @click="router.get(books.prev_page_url)"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
            >
                Prev
            </button>

            <button
                v-if="books.next_page_url"
                @click="router.get(books.next_page_url)"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
            >
                Next
            </button>
        </div>
    </div>
    </AppLayout>
</template>
