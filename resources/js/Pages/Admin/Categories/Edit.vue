<script setup>
import BoxShadow from "@/Components/BoxShadow.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputField from "@/Components/InputField.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Toggle from "@/Components/Toggle.vue";
import { Head, router } from "@inertiajs/vue3";

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    status: props.category.status,
});

const submit = () => {
    form.put(route("categories.update", props.category.id));
};

const handleCancel = () => {
    router.get(route("categories.index"));
};
</script>
<template>
    <Head title="Edit Category" />

    <h1 class="font-bold text-4xl text-gray-800">Edit Category</h1>

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
                <SecondaryButton class="px-3" @click="handleCancel"
                    >Cancel</SecondaryButton
                >

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
