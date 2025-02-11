<script setup>
import { ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    books: Object,
    filters: Object
});

const id = ref(props.filters.id || '');
const title = ref(props.filters.title || '');

// Следим за вводом в поле title и обновляем список
watch(title, (value) => {
    router.get('/books', { id: id.value, title: value }, { preserveState: true, replace: true });
});

// Следим за изменением id и обновляем список
watch(id, (value) => {
    router.get('/books', { id: value, title: title.value }, { preserveState: true, replace: true });
});
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Список книг</h1>

        <!-- Фильтры -->
        <div class="mb-4 flex gap-2">
            <input v-model="id" type="number" placeholder="Фильтр по ID" class="border p-2" />
            <input v-model="title" type="text" placeholder="Фильтр по Title" class="border p-2" />
        </div>

        <!-- Список книг -->
        <ul>
            <li v-for="book in books.data" :key="book.id" class="p-2 border-b">
                #{{ book.id }} - {{ book.title }}
            </li>
        </ul>

        <!-- Пагинация -->
        <div class="mt-4">
            <button v-if="books.prev_page_url" @click="router.get(books.prev_page_url)" class="mr-2 p-2 border">Назад</button>
            <button v-if="books.next_page_url" @click="router.get(books.next_page_url)" class="p-2 border">Вперёд</button>
        </div>
    </div>
</template>
