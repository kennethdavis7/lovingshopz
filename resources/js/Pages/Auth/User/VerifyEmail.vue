<script setup>
import { computed } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import BoxShadow from "@/Components/BoxShadow.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Logo from "@/Components/Logo.vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);
</script>

<template>
    <Head title="Email Verification" />

    <div class="flex flex-col items-center p-0 my-28 mx-auto">
        <Logo imageClass="w-80" />

        <BoxShadow class="w-96">
            <div class="mb-4 text-sm text-gray-600">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>

            <div
                class="mb-4 font-medium text-sm text-green-600"
                v-if="verificationLinkSent"
            >
                A new verification link has been sent to the email address you
                provided during registration.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        class="p-2"
                        :disabled="form.processing"
                    >
                        Resend Verification Email
                    </PrimaryButton>

                    <Link
                        :href="
                            $page.props.auth.clientUser
                                ? route('logout')
                                : route('logout')
                        "
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Log Out
                    </Link>
                </div>
            </form>
        </BoxShadow>
    </div>
</template>
