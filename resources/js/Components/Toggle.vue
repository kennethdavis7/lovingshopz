<script setup>
const toggleModel = defineModel({
    type: Boolean,
    required: true,
});

const props = defineProps({
    true: String,
    false: String,
});

defineEmits(["change"]);
</script>

<template>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" v-model="toggleModel" class="sr-only peer" />
        <div
            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"
        ></div>
        <span
            v-if="toggleModel"
            class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
            >{{ props.true }}</span
        ><span
            v-else
            class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
        >
            {{ props.false }}
        </span>
    </label>
</template>
