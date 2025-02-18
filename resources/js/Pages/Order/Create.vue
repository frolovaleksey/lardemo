<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import EmailInput from "@/Components/EmailInput.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    email: '',
    comment: '',
});

function saveOrder() {
    form.post(route('order.store'));
}
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Save Order</h1>

            <form @submit.prevent="saveOrder" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Email</label>
                    <EmailInput v-model="form.email" class="w-full mt-1" placeholder="Email" />
                    <InputError :message="form.errors.email" class="mt-2" />
                </div>
                <div>
                    <label class="block text-gray-700">Comment</label>
                    <textarea v-model="form.comment" class="w-full p-2 border rounded"></textarea>
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Order</button>
            </form>
        </div>
    </AppLayout>
</template>
