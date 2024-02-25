<script setup>
const getImageUrl = (file) => {
    if (typeof file === "object") {
        return URL.createObjectURL(file);
    }

    return "/" + file;
};

const revokeImageBlob = (src) => {
    return URL.revokeObjectURL(src);
};

const props = defineProps(["image"]);

defineEmits(["deleteImage"]);
</script>
<template>
    <div class="flex flex-wrap">
        <div
            class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-green-500 overflow-hidden"
            draggable="false"
        >
            <img
                :src="getImageUrl(image)"
                @load="(e) => revokeImageBlob(e.target.src)"
                class="max-w-full max-h-full"
                draggable="false"
            />
            <button
                class="absolute top-1 right-1 cursor-pointer"
                type="button"
                @click="() => $emit('deleteImage')"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    class="w-5 h-5"
                >
                    <path
                        d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"
                    ></path>
                </svg>
            </button>
        </div>
    </div>
</template>
