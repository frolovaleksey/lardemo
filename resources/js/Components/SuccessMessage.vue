<script setup>
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const page = usePage();
const successMessage = ref(page.props.flash?.success || '');
</script>

<template>
    <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ successMessage }}
    </div>
</template>
