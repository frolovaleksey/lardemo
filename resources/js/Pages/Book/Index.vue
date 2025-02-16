<script setup>
import { ref, watch } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    canAddBook: Boolean,
    books: Object,
    filters: Object,
    translations: Object,
});

const page = usePage();
const successMessage = ref(page.props.flash?.success || '');

const id = ref(props.filters.id || '');
const title = ref(props.filters.title || '');

const showModal = ref(false);
const bookToDelete = ref(null);

watch(title, (value) => {
    router.get('/book', { id: id.value, title: value }, { preserveState: true, replace: true });
});

watch(id, (value) => {
    router.get('/book', { id: value, title: title.value }, { preserveState: true, replace: true });
});

function confirmDelete(book) {
    bookToDelete.value = book;
    showModal.value = true;
}

function deleteBook() {
    if (bookToDelete.value) {
        router.delete(route('book.destroy', bookToDelete.value.id), {
            onSuccess: () => {
                showModal.value = false;
                successMessage.value = "Book deleted successfully!";
            },
        });
    }
}

function editBook(book) {
    router.get(route('book.edit', book.id));
}

</script>

<template>
    <AppLayout>
        <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{ props.translations.book_list }}</h1>

            <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ successMessage }}
            </div>

            <div class="mb-6 flex flex-col md:flex-row gap-4" v-if="canAddBook">
                <a :href="route('book.create')"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    {{ props.translations.add_book }}
                </a>
            </div>

            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <input v-model="id" type="number" :placeholder="props.translations.filter_id" class="w-full md:w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" />
                <input v-model="title" type="text" :placeholder="props.translations.filter_title" class="w-full md:w-2/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="book in books.data" :key="book.id" class="bg-gray-100 p-4 rounded-lg shadow-lg">
                    <img :src="book.image_url" alt="Book Cover" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ book.title }}</h3>
                    <p class="text-gray-600">{{ props.translations.price }}: {{ book.price }} Kč</p>
                    <p class="text-gray-500 text-sm">{{ props.translations.authors }}: {{ book.authors.map(a => a.first_name+' '+a.last_name).join(', ') }}</p>
                    <div class="mt-4 flex gap-2">
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">{{ props.translations.buy }}</button>

                        <button @click="editBook(book)" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </button>
                        <button @click="confirmDelete(book)" class="text-red-600 hover:text-red-800">
                            Delete
                        </button>
                    </div>

                    <!-- Modal for confirmation -->
                    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                            <h2 class="text-lg font-semibold mb-4">Are you sure you want to delete this book?</h2>
                            <div class="flex justify-end gap-4">
                                <button @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                                <button @click="deleteBook" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button v-if="books.prev_page_url" @click="router.get(books.prev_page_url)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Prev</button>
                <button v-if="books.next_page_url" @click="router.get(books.next_page_url)" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Next</button>
            </div>
        </div>
    </AppLayout>
</template>
