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
import { Head, router } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { watch, ref, computed, reactive } from "vue";

const props = defineProps(["categories", "product"]);

const disabled = ref(!props.product.stock);

const temporaryStock = ref(props.product.stock);

const oldImages = reactive(props.product.images);

const form = useForm({
    _method: "put",
    name: props.product.name,
    price: props.product.price,
    stock: props.product.stock,
    min_order: props.product.min_order,
    category_id: props.categories.at(0)?.id,
    description: props.product.description,
    new_images: [],
    images_to_delete: [],
    status: props.product.status,
    total_images: oldImages.length,
});

const imageErrors = computed(() => {
    return [
        form.errors.new_images,
        Object.entries(form.errors)
            .find(([key, val]) => key.startsWith("new_images."))
            ?.at(1),
        form.errors.total_images,
    ].filter(Boolean);

    // obj = {
    //     a: '1',
    //     b: '2',
    // }

    // Object.entries(obj) === [
    //     ['a', '1'],
    //     ['b', '2'],
    // ],
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

        form.new_images.push(file);
        form.total_images += 1;
    }
};

const handleDeleteImage = (idx, isOld) => {
    form.total_images -= 1;
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
    <Head title="Edit Product" />
    <h1 class="font-bold text-4xl text-gray-800">Edit Product</h1>
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
                            autocomplete="product"
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
                        <InputLabel for="stock" value="Quantity" />
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

                    <div>
                        <InputLabel value="Category" />
                        <Select
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
                        <InputLabel class="mb-1" value="Description" />
                        <QuillEditor
                            :toolbar="toolbar"
                            v-model:content="form.description"
                            contentType="html"
                            theme="snow"
                            class="min-h-[200px]"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.description"
                        />
                    </div>

                    <div class="mb-4">
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

                        <InputError
                            class="mt-2"
                            v-for="error in imageErrors"
                            :key="error"
                            :message="error"
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
