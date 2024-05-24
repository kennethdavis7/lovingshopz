<script setup>
import InputField from "@/Components/InputField.vue";
import { onMounted, onUnmounted, ref, watch } from "vue";
import { useToast } from "vue-toastification";

const qtyInput = ref(null);
const errorMessage = ref("");

const props = defineProps({
    modelValue: Number,
    productId: { type: Number, default: null },
    handleQty: Function,
    maxQty: { type: Number, required: true },
    minQty: { type: Number, required: true },
});

const emit = defineEmits([
    "handleQty",
    "update:hasError",
    "update:errorMessage",
]);

const toast = useToast();

function handleQtyInput(e) {
    if (/^\d*$/.test(this.value)) {
        // Accepted value.
        if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
            this.setCustomValidity("");
            errorMessage.value = "";
        }

        let hasError = true;

        if (Number(this.value) < props.minQty) {
            if (["focusout", "mousedown"].indexOf(e.type) >= 0) {
                this.value = props.minQty;
                errorMessage.value = "";
                hasError = false;
            }
        } else if (props.maxQty > 0 && Number(this.value) > props.maxQty) {
            errorMessage.value = "Kuantitas harus di bawah " + props.maxQty;

            if (["focusout", "mousedown"].indexOf(e.type) >= 0) {
                this.value = props.maxQty;
                errorMessage.value = "";
                hasError = false;
            }
        } else {
            errorMessage.value = "";
            hasError = false;
        }

        emit("update:hasError", hasError);

        if (
            this.value.toString() !== this.oldValue?.toString() &&
            ["input", "focusout", "mousedown"].indexOf(e.type) >= 0
        ) {
            emit("handleQty", this.value);
            // handleChangeQty(this.value);
        }

        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
    } else if (this.hasOwnProperty("oldValue")) {
        // Rejected value: restore the previous one.
        const numericErrorMessage = "Kuantitas harus berupa angka";

        this.setCustomValidity(numericErrorMessage);
        this.reportValidity();

        this.value = this.oldValue;

        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
    } else {
        // Rejected value: nothing to restore.
        this.value = Number.parseInt(this.value, 10);
    }
}

const QTY_INPUT_EVENTS = [
    "input",
    "keydown",
    "keyup",
    "mousedown",
    "mouseup",
    "select",
    "contextmenu",
    "drop",
    "focusout",
];

onMounted(() => {
    if (qtyInput.value == null) return;
    QTY_INPUT_EVENTS.forEach((event) => {
        qtyInput.value.input.addEventListener(event, handleQtyInput);
    });
});

onUnmounted(() => {
    if (qtyInput.value == null) return;
    QTY_INPUT_EVENTS.forEach((event) => {
        qtyInput.value.input.removeEventListener(event, handleQtyInput);
    });
});

watch(errorMessage, (val) => {
    emit("update:errorMessage", val);
});
</script>

<style>
.Vue-Toastification__container.bottom-center {
    margin-bottom: 4rem;
}
</style>

<template>
    <InputField
        :model-value="props.modelValue"
        type="text"
        pattern="^\d+$"
        inputmode="numeric"
        class="mt-1 block w-24"
        ref="qtyInput"
    />
</template>
