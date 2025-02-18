<script setup>

import {router} from "@inertiajs/vue3";


const props = defineProps({
    items: Object,
});
</script>

<template>
    <div class="mt-6 flex justify-between">
        <button
            v-if="items.prev_page_url"
            @click="router.get(items.prev_page_url)"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
        >
            Prev
        </button>

        <button
            v-if="items.next_page_url"
            @click="router.get(items.next_page_url)"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
        >
            Next
        </button>
    </div>
</template>
