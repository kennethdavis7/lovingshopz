<script setup>
import BoxShadow from "@/Components/BoxShadow.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputField from "@/Components/InputField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Toggle from "@/Components/Toggle.vue";
import { Head, router, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: null,
    status: true,
});

const submit = () => {
    form.post(route("categories.store"));
};

const handleCancel = () => {
    router.get(route("categories.index"));
};
</script>
<template>
    <Head title="Create Category" />

    <h1 class="font-bold text-4xl text-gray-800">Create Product</h1>
    <BoxShadow>
        <form
            class="w-full p-2"
            enctype="multipart/form-data"
            @submit.prevent="submit"
        >
            <div class="mb-5">
                <InputLabel for="name" value="Category Name" />

                <InputField
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                />
            </div>
            <div class="mb-5">
                <Toggle
                    v-model="form.status"
                    true="Published"
                    false="Unpublished"
                />
            </div>
            <div class="mt-8 mb-4 gap-4 flex justify-end">
                <SecondaryButton class="px-3" @click="handleCancel">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="px-6 py-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save & Close
                </PrimaryButton>
            </div>
        </form>
    </BoxShadow>
</template>
