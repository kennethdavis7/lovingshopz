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
import PublishedToggle from "@/Components/PublishedToggle.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CancelButton from "@/Components/CancelButton.vue";
import { ref, computed } from "vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps(["categories"]);

const form = useForm({
    name: "",
    price: 0,
    qty: 0,
    min_order: 0,
    category: props.categories[0].name,
    description: "",
    images: [],
    status: true,
});

const handleImageUpload = (e) => {
    for (const file of e.target.files) {
        console.log(form.images.some((e) => e.name === file.name));
        if (form.images.some((e) => e.name === file.name)) {
            if (
                file.size >= 6 * 1000 * 1000 ||
                !file.type.startsWith("image/")
            ) {
                return;
            }
            return;
        }
    }

    form.images.push(...e.target.files);
    console.log(form.images);
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
    form.post(route("products.store"), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Create Product" />

    <h1 class="font-bold text-4xl text-gray-800">Create Product</h1>
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

                        <TextInput
                            id="price"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.price"
                            required
                            autofocus
                            autocomplete="price"
                        />
                    </div>
                    <div class="mb-5 flex">
                        <div class="qty mr-5">
                            <InputLabel for="qty" value="Quantity" />

                            <TextInput
                                id="qty"
                                type="number"
                                class="mt-1 block w-24"
                                v-model="form.qty"
                                required
                                autofocus
                            />
                        </div>

                        <div class="min_order">
                            <InputLabel for="min_order" value="Minimal Order" />

                            <TextInput
                                id="min_order"
                                type="number"
                                class="mt-1 block w-24"
                                v-model="form.min_order"
                                required
                                autofocus
                            />
                        </div>
                    </div>

                    <div class="mb-5">
                        <InputLabel for="category" value="Category" />
                        <SelectInput
                            id="category"
                            v-model="form.category"
                            :categories="props.categories"
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
                        <PublishedToggle v-model="form.status" />
                    </div>
                </div>

                <div class="mx-5"></div>

                <div class="left-form w-1/4">
                    <div>
                        <InputLabel for="image" class="mb-1" value="Image" />
                        <div class="flex flex-wrap gap-1 w-full">
                            <div
                                class="flex flex-wrap gap-2"
                                v-if="form.images.length !== 0"
                            >
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
                                <div>
                                    <UploadImage
                                        @imageUpload="handleImageUpload"
                                    />
                                </div>
                            </div>
                            <div v-else>
                                <UploadImage @imageUpload="handleImageUpload" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4 flex justify-end">
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
