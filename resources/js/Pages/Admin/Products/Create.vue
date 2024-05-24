<script setup>
import BoxShadow from "@/Components/BoxShadow.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputField from "@/Components/InputField.vue";
import { useForm } from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import UploadImage from "@/Components/UploadImage.vue";
import ImageDisplay from "@/Components/ImageDisplay.vue";
import Toggle from "@/Components/Toggle.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { Head, router } from "@inertiajs/vue3";
import { watch, ref } from "vue";

const props = defineProps(["categories"]);

const disabled = ref(false);

const temporaryStock = ref(null);

const form = useForm({
    name: "",
    price: 0,
    stock: 0,
    min_order: 0,
    category_id: props.categories.at(0)?.id,
    description: "",
    images: [],
    status: true,
});

const handleImageUpload = (e) => {
    for (const file of e.target.files) {
        if (!file.type.startsWith("image/")) {
            form.errors.new_images = "The file must be an image type";
            continue;
        }

        if (file.size >= 8 * 1000 * 1000) {
            form.errors.new_images = "The file must be below 8 MB";
            continue;
        }
        form.images.push(...e.target.files);
    }
};

const handleDeleteImage = (idx) => {
    form.images.splice(idx, 1);
};

const toolbar = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ["bold", "italic", "underline"],
    [{ list: "ordered" }, { list: "bullet" }],
];

const submit = () => {
    form.post(route("products.store"));
};

const handleCancel = () => {
    router.get(route("products.index"));
};

watch(disabled, (value) => {
    if (value === true) {
        form.stock = null;
    } else {
        form.stock = temporaryStock.value;
    }
});

watch(temporaryStock, (value) => {
    form.stock = temporaryStock.value;
});
</script>

<template>
    <Head title="Create Product" />

    <h1 class="font-bold text-4xl text-gray-800">Create Product</h1>
    <BoxShadow class="min-h-[600px]">
        <form
            class="w-full p-2"
            enctype="multipart/form-data"
            @submit.prevent="submit"
        >
            <div class="flex">
                <div class="right-form w-3/4">
                    <div>
                        <InputLabel for="name" value="Product Name" />

                        <InputField
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="price" value="Price" />

                        <CurrencyInput
                            id="price"
                            class="mt-1 block w-full"
                            v-model="form.price"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.price" />
                    </div>

                    <div>
                        <InputLabel for="stock" value="Stock" />
                        <div class="flex">
                            <div class="stock mr-5">
                                <InputField
                                    :disabled="disabled"
                                    id="stock"
                                    type="number"
                                    :class="[
                                        'mt-1 block w-24',
                                        disabled
                                            ? 'bg-gray-100 appearance-none cursor-not-allowed text-gray-600'
                                            : '',
                                    ]"
                                    v-model="temporaryStock"
                                    required
                                />
                            </div>

                            <div class="stock_toggle flex items-center">
                                <Toggle
                                    v-model="disabled"
                                    true="&infin;"
                                    false="&infin;"
                                />
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.stock" />
                    </div>

                    <div>
                        <div class="min_order">
                            <InputLabel for="min_order" value="Minimal Order" />

                            <InputField
                                id="min_order"
                                type="number"
                                class="mt-1 block w-24"
                                v-model="form.min_order"
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.min_order"
                            />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="category_id" value="Category" />
                        <Select
                            id="category_id"
                            v-model="form.category_id"
                            :data="props.categories"
                            popupClass="w-full"
                            buttonClass="py-3"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.category_id"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="description"
                            class="mb-1"
                            value="Description"
                        />
                        <QuillEditor
                            :toolbar="toolbar"
                            v-model:content="form.description"
                            contentType="html"
                            theme="snow"
                            id="description"
                            class="min-h-[200px]"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.description"
                        />
                    </div>

                    <div>
                        <Toggle
                            v-model="form.status"
                            true="Published"
                            false="Unpublished"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.status"
                        />
                    </div>
                </div>

                <div class="mx-5"></div>

                <div class="left-form w-1/4">
                    <div>
                        <InputLabel class="mb-1" value="Image" />
                        <div class="flex flex-wrap gap-1 w-full">
                            <div class="flex flex-wrap gap-2">
                                <template
                                    v-for="(image, idx) in form.images"
                                    :key="image"
                                >
                                    <ImageDisplay
                                        :idx="idx"
                                        :image="image"
                                        @deleteImage="handleDeleteImage"
                                    />
                                </template>

                                <UploadImage @imageUpload="handleImageUpload" />
                            </div>
                        </div>
                        <InputError
                            class="mt-2"
                            :message="form.errors.images"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors['images.0']"
                        />
                    </div>
                </div>
            </div>
            <div class="mt-3 mb-4 gap-4 flex justify-end">
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
