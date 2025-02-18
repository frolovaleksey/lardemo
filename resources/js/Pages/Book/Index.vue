<script setup>
import { ref, watch } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SuccessMessage from "@/Components/SuccessMessage.vue";
import PaginationSimple from "@/Components/PaginationSimple.vue";

const props = defineProps({
    canAddBook: Boolean,
    canEditBook: Boolean,
    canDeleteBook: Boolean,
    items: Object,
    filters: Object,
    translations: Object,
    cartCount: Number,
});

const page = usePage();
const successMessage = ref(page.props.flash?.success || '');
const cartCount = ref(props.cartCount || 0);

const id = ref(props.filters.id || '');
const title = ref(props.filters.title || '');
const last_name = ref(props.filters.last_name || '');

const showModal = ref(false);
const bookToDelete = ref(null);

watch(title, (value) => {
    router.get('/book', { id: id.value, title: value, last_name: last_name.value }, { preserveState: true, replace: true });
});

watch(id, (value) => {
    router.get('/book', { id: value, title: title.value, last_name: last_name.value }, { preserveState: true, replace: true });
});

watch(last_name, (value) => {
    router.get('/book', { id: id.value, title: title.value, last_name: value }, { preserveState: true, replace: true });
});

function confirmDelete(book) {
    bookToDelete.value = book;
    showModal.value = true;
}



function editBook(book) {
    router.get(route('book.edit', book.id));
}

function addToCart(book) {
    router.post(route('cart.addItem'), { id: book.id }, {
        onSuccess: (response) => {
            cartCount.value = response.props.cartCount;
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{ props.translations.book_list }}</h1>

            <SuccessMessage></SuccessMessage>

            <div class="flex justify-between mb-4">
                <a :href="route('checkout')" class="text-lg font-semibold">{{ props.translations.cart }}: {{ cartCount }}</a>
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
                <input v-model="last_name" type="text" :placeholder="props.translations.filter_last_name" class="w-full md:w-2/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <span>Items total count: {{ props.items.total }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="book in items.data" :key="book.id" class="bg-gray-100 p-4 rounded-lg shadow-lg">
                    <img :src="book.image_url" alt="Book Cover" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ book.title }}</h3>
                    <p class="text-gray-600">{{ props.translations.price }}: {{ book.price }} Kč</p>
                    <p class="text-gray-500 text-sm">{{ props.translations.authors }}: {{ book.authors.map(a => a.first_name+' '+a.last_name).join(', ') }}</p>
                    <div class="mt-4 flex gap-2">
                        <button @click="addToCart(book)" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            {{ props.translations.add_to_cart }}
                        </button>

                        <button v-if="canEditBook" @click="editBook(book)" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </button>

                        <button v-if="canDeleteBook"  @click="confirmDelete(book)" class="text-red-600 hover:text-red-800">
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <PaginationSimple :items="props.items"></PaginationSimple>
        </div>
    </AppLayout>
</template>
