<script setup>
const props = defineProps({
    status: Boolean | Number,
});
</script>
<template>
    <div class="flex items-center">
        <template v-if="props.status">
            <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
            Published
        </template>
        <template v-else>
            <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>
            Unpublished
        </template>
    </div>
</template>
