<script setup>
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";
import { computed } from "vue";

const selectModel = defineModel({
    type: [String, Number],
    required: true,
});

const props = defineProps(["data", "popupClass", "buttonClass", "disabled"]);

const selectedDatum = computed(() =>
    props.data.find((d) => d.id === selectModel.value)
);
</script>

<template>
    <Listbox v-model="selectModel" :disabled="disabled">
        <div class="relative bg-opacity-100">
            <ListboxButton
                :class="[
                    'relative w-full focus:outline-green-500 rounded-lg pl-3 pr-10 text-left border border-gray-300 sm:text-sm cursor-pointer',
                    disabled
                        ? '!cursor-not-allowed text-gray-600 bg-gray-100'
                        : 'bg-white',
                    props.buttonClass,
                ]"
                id="product_data"
            >
                <span class="block truncate">{{ selectedDatum?.name }}</span>
                <span
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronUpDownIcon
                        class="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                    />
                </span>
            </ListboxButton>

            <transition
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <ListboxOptions
                    :class="[
                        'absolute mt-1 max-h-60 overflow-auto rounded-md bg-white py-1 z-50 text-base border border-gray-300 ring-1 ring-black/5 focus:outline-none sm:text-sm',
                        popupClass,
                    ]"
                >
                    <ListboxOption
                        v-slot="{ active, selected }"
                        v-for="data in props.data"
                        :key="data.id"
                        :value="data.id"
                        as="template"
                    >
                        <li
                            :class="[
                                active
                                    ? 'bg-green-100 text-green-900'
                                    : 'text-gray-900',
                                'relative cursor-pointer select-none py-2 pl-10 pr-4',
                            ]"
                        >
                            <span
                                :class="[
                                    selected ? 'font-medium' : 'font-normal',
                                    'block truncate',
                                ]"
                                >{{ data.name }}</span
                            >
                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-600"
                            >
                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
