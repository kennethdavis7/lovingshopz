<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputField from "@/Components/InputField.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import UploadImage from "@/Components/UploadImage.vue";
import ImageDisplay from "@/Components/ImageDisplay.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    _method: "put",
    name: user.name,
    image: user.image,
    email: user.email,
});

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (!file.type.startsWith("image/")) {
        form.errors.image = "The file must be an image type";
        return;
    }

    if (file.size >= 100 * 1000) {
        form.errors.image = "The file must be below 100 KB";
        return;
    }
    form.image = file;
};

const handleDeleteImage = () => {
    form.image = null;
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>

            <p class="mt-1 text-sm text-gray-600">Ubah informasi profil Anda</p>
        </header>

        <form
            @submit.prevent="form.post(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="image" value="Profile Picture" />

                <ImageDisplay
                    v-if="form.image !== null"
                    :image="form.image"
                    @deleteImage="handleDeleteImage"
                />
                <UploadImage
                    id="image"
                    v-if="form.image == null"
                    @imageUpload="handleImageUpload"
                />
                <InputError class="mt-2" :message="form.errors.image" />
            </div>
            <div>
                <InputLabel for="name" value="Nama" />

                <InputField
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <InputField
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Email Anda belum terverifikasi
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        Tekan tombol ini untuk verifikasi
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    Link verifikasi telah dikirim
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton class="px-4 py-2" :disabled="form.processing"
                    >Simpan</PrimaryButton
                >

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Tersimpan.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
