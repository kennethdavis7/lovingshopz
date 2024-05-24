<script setup>
defineEmits(["imageUpload"]);
</script>
<template>
    <div
        class="relative w-[120px] h-[120px] rounded border border-dashed flex items-center justify-center hover:border-green-500 overflow-hidden"
    >
        <span> Upload </span
        ><input
            @input="(event) => $emit('imageUpload', event)"
            type="file"
            class="absolute left-0 top-0 bottom-0 right-0 w-full h-full opacity-0 cursor-pointer"
            accept="image/*"
        />
    </div>
</template>
