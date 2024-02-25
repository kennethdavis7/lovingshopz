<script setup>
import Card from "@/Components/CardTemplate.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import UploadImage from "@/Components/UploadImage.vue";
import ImageDisplay from "@/Components/ImageDisplay.vue";
import Toggle from "@/Components/Toggle.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CancelButton from "@/Components/CancelButton.vue";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import { Head, router } from "@inertiajs/vue3";
import { watch, ref, computed, reactive } from "vue";

const props = defineProps(["categories", "product"]);

const disabled = ref(!props.product.qty);

const temporaryQty = ref(props.product.qty);

const form = useForm({
    _method: "put",
    name: props.product.name,
    price: props.product.price,
    qty: props.product.qty,
    min_order: props.product.min_order,
    category_id: props.categories.at(0)?.id,
    description: props.product.description,
    new_images: [],
    images_to_delete: [],
    status: true,
});

const oldImages = reactive(props.product.images);

const handleImageUpload = (e) => {
    for (const file of e.target.files) {
        if (file.size >= 6 * 1000 * 1000 || !file.type.startsWith("image/")) {
            continue;
        }

        form.new_images.push(file);
    }
};

const handleDeleteImage = (idx, isOld) => {
    if (isOld) {
        const [image] = oldImages.splice(idx, 1);
        form.images_to_delete.push(image.url);
        return;
    }

    form.new_images.splice(idx, 1);
};

const toolbar = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ["bold", "italic", "underline"],
    [{ list: "ordered" }, { list: "bullet" }],
];

const submit = () => {
    form.post(route("products.update", { product: props.product.id }));
};

watch(disabled, (value) => {
    if (value === true) {
        form.qty = null;
    } else {
        form.qty = temporaryQty.value;
    }
});

watch(temporaryQty, (value) => {
    form.qty = temporaryQty.value;
});
</script>

<template>
    <Head title="Edit Product" />

    <h1 class="font-bold text-4xl text-gray-800">Edit Product</h1>
    <Card class="min-h-[600px]">
        <form
            class="w-full p-2"
            enctype="multipart/form-data"
            @submit.prevent="submit"
        >
            <div class="flex">
                <div class="right-form w-3/4">
                    <div class="mb-5">
                        <InputLabel for="name" value="Product Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="product"
                        />
                    </div>

                    <div class="mb-5">
                        <InputLabel for="price" value="Price" />

                        <CurrencyInput
                            id="price"
                            class="mt-1 block w-full"
                            v-model="form.price"
                            required
                        />
                    </div>

                    <div class="mb-5">
                        <InputLabel for="qty" value="Quantity" />
                        <div class="flex">
                            <div class="qty mr-5">
                                <TextInput
                                    :disabled="disabled"
                                    id="qty"
                                    type="number"
                                    :class="[
                                        'mt-1 block w-24',
                                        disabled
                                            ? 'bg-gray-100 appearance-none cursor-not-allowed text-gray-600'
                                            : '',
                                    ]"
                                    v-model="temporaryQty"
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
                    </div>

                    <div class="mb-5">
                        <InputLabel for="min_order" value="Minimal Order" />

                        <TextInput
                            id="min_order"
                            type="number"
                            class="mt-1 block w-24"
                            v-model="form.min_order"
                            required
                        />
                    </div>

                    <div class="mb-5">
                        <InputLabel for="category_id" value="Category" />
                        <SelectInput
                            id="category_id"
                            v-model="form.category_id"
                            :data="props.categories"
                            popupClass="w-full"
                            buttonClass="py-3"
                        />
                    </div>

                    <div class="mb-4">
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
                    </div>

                    <div class="mb-4">
                        <Toggle
                            v-model="form.status"
                            true="Published"
                            false="Unpublished"
                        />
                    </div>
                </div>

                <div class="mx-5"></div>

                <div class="left-form w-1/4">
                    <div>
                        <InputLabel for="image" class="mb-1" value="Image" />
                        <div class="flex flex-wrap gap-1 w-full">
                            <div class="flex flex-wrap gap-2">
                                <ImageDisplay
                                    v-for="(image, idx) in oldImages"
                                    :key="image.id"
                                    :image="image.url"
                                    @deleteImage="
                                        () => handleDeleteImage(idx, true)
                                    "
                                />

                                <ImageDisplay
                                    v-for="(image, idx) in form.new_images"
                                    :key="idx"
                                    :image="image"
                                    @deleteImage="
                                        () => handleDeleteImage(idx, false)
                                    "
                                />

                                <UploadImage
                                    :key="
                                        oldImages.length +
                                        form.new_images.length
                                    "
                                    @imageUpload="handleImageUpload"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 mb-4 flex justify-end">
                <CancelButton
                    class="mr-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Cancel
                </CancelButton>

                <PrimaryButton
                    class="px-6 py-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save & Close
                </PrimaryButton>
            </div>
        </form>
    </Card>
</template>
