<script setup>
import { onMounted, ref } from "vue";
import { useCurrencyInput } from "vue-currency-input";

const model = defineModel({
    type: [String, Number],
    required: true,
});

const { inputRef } = useCurrencyInput({
    locale: "id-ID",
    currency: "IDR",
    precision: 2,
});

defineEmits(["change"]);

onMounted(() => {
    if (inputRef.value?.hasAttribute("autofocus")) {
        inputRef.value.focus();
    }
});

defineExpose({ focus: () => inputRef.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm"
        v-model="model"
        ref="inputRef"
    />
</template>
