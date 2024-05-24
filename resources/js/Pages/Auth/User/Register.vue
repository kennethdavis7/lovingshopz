<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import BoxShadow from "@/Components/BoxShadow.vue";
import InputField from "@/Components/InputField.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import Logo from "@/Components/Logo.vue";
import Select from "@/Components/Select.vue";
import { watch, computed } from "vue";

const UNSELECTED_CITY = -1;
const UNSELECTED_PROVINCE = -1;

const props = defineProps({
    cities: Object || Null,
    provinces: Object,
});

const form = useForm({
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
    city_id: UNSELECTED_CITY,
    province_id: UNSELECTED_PROVINCE,
    address: "",
});

const provinces = computed(() => [
    {
        id: UNSELECTED_PROVINCE,
        name: "Pilih Provinsi",
    },
    ...props.provinces,
]);

const isDisabled = computed(() => form.province_id === UNSELECTED_PROVINCE);

const filteredCities = computed(() => {
    return [
        {
            id: UNSELECTED_CITY,
            name: "Pilih Kota",
        },
        ...props.cities.filter((city) => city.province_id === form.province_id),
    ];
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

watch(
    () => form.province_id,
    () => {
        form.city_id = UNSELECTED_CITY;
    }
);

const handleChangeCity = (id) => {
    form.city_id = id;
};

const handleChangeProvince = (id) => {
    form.province_id = id;
};
</script>

<template>
    <Head title="Register" />
    <form
        class="flex flex-col items-center p-0 my-28 mx-auto"
        @submit.prevent="submit"
    >
        <Logo imageClass="w-80" />
        <BoxShadow class="w-96">
            <div class="mb-1">
                <InputLabel for="name" value="Name" />

                <InputField
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                />

                <InputError class="mt-1" :message="form.errors.name" />
            </div>

            <div class="mb-1 flex gap-4">
                <div>
                    <InputLabel for="province" value="Province" />

                    <div class="w-28">
                        <Select
                            id="province"
                            :model-value="form.province_id"
                            @update:model-value="handleChangeProvince"
                            :data="provinces"
                            buttonClass="py-2"
                        />
                    </div>

                    <InputError class="mt-1" :message="form.errors.city" />
                </div>

                <div>
                    <InputLabel for="city" value="Cities" />

                    <div class="w-28">
                        <Select
                            id="city"
                            :model-value="form.city_id"
                            @update:model-value="handleChangeCity"
                            :data="filteredCities"
                            buttonClass="py-2"
                            :disabled="isDisabled"
                        />
                    </div>

                    <InputError class="mt-1" :message="form.errors.province" />
                </div>
            </div>

            <div class="mb-1">
                <InputLabel for="address" value="Address" />

                <InputField
                    id="address"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address"
                    required
                />
                <InputError class="mt-1" :message="form.errors.address" />
            </div>

            <div class="mb-1">
                <InputLabel for="email" value="Email" />

                <InputField
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                />

                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div class="mb-1">
                <InputLabel for="password" value="Password" />

                <InputField
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                />

                <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <div class="mb-1">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <InputField
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                />

                <InputError
                    class="mt-1"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="flex items-center justify-end">
                <Link
                    :href="route('user.login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4 px-4 py-2"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </BoxShadow>
    </form>
</template>
