<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div :style="{ visibility: message ? 'visible' : 'hidden' }" class="my-3">
        <p class="text-sm text-red-600">
            {{ message ? message : "error message" }}
        </p>
    </div>
</template>
