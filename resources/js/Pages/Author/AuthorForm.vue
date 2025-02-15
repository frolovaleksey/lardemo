<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    initialData: Object,
    submitAction: Function
});

const form = useForm({
    first_name: props.initialData.first_name,
    last_name: props.initialData.last_name
});

const submit = () => {
    props.submitAction(form);
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="mb-4">
            <label class="block text-gray-700">First Name</label>
            <TextInput v-model="form.first_name" class="w-full mt-1" placeholder="First Name" />
            <InputError :message="form.errors.first_name" class="mt-2" />
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Last Name</label>
            <TextInput v-model="form.last_name" class="w-full mt-1" placeholder="Last Name" />
            <InputError :message="form.errors.last_name" class="mt-2" />
        </div>

        <div class="flex justify-end">
            <PrimaryButton :disabled="form.processing" type="submit">
                Save
            </PrimaryButton>
        </div>
    </form>
</template>
