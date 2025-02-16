<script setup>
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
    modelValue: Array,
    options: Array,
    multiple: Boolean
});

defineEmits(['update:modelValue']);

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
        select.value.focus();
    }
});

defineExpose({ focus: () => select.value.focus() });
</script>

<template>
    <select
        ref="select"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :multiple="multiple"
        @change="$emit('update:modelValue', Array.from($event.target.selectedOptions, option => option.value))"
    >
        <option
            v-for="option in options"
            :key="option.value"
            :value="option.value"
            :selected="modelValue.includes(option.value)"
        >
            {{ option.label }}
        </option>
    </select>
</template>
