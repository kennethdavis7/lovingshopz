<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: (h, page) =>
        h(
            GuestLayout,
            {
                footerSpaceY: (user) =>
                    user?.role_id === 2 ? "8rem" : undefined,
            },
            () => page
        ),
};
</script>

<script setup>
import ProductCartAction from "@/Components/ProductCartAction.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Cart from "@/Components/Cart.vue";
import { Head } from "@inertiajs/vue3";
import formatCurrency from "@/utils/formatCurrency";
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    product: Object,
});

const currentWindowSize = ref(window.outerWidth);
const slider = ref(null);

const leftArrowVisible = ref(false);
const rightArrowVisible = ref(true);

const handleNext = () => {
    slider.value.scrollBy({
        left: 150,
        behavior: "smooth",
    });
};

const handlePrev = () => {
    slider.value.scrollBy({
        left: -150,
        behavior: "smooth",
    });
};

const handleThumbnailCarouselScroll = () => {
    if (!slider.value) return;

    // console.log(slider.value.scrollWidth, slider.value.scrollLeft);

    const isScrollable = slider.value.scrollWidth > slider.value.clientWidth;

    leftArrowVisible.value = slider.value.scrollLeft > 0;
    rightArrowVisible.value =
        isScrollable &&
        slider.value.scrollWidth - slider.value.scrollLeft >
            slider.value.clientWidth;
};

const currentImageIndex = ref(0);

const changeImageIndex = (id) => {
    currentImageIndex.value = id;
};

onMounted(() => {
    window.onresize = () => {
        currentWindowSize.value = window.outerWidth;
    };

    if (slider.value) {
        slider.value.addEventListener("scroll", handleThumbnailCarouselScroll);
        setTimeout(handleThumbnailCarouselScroll, 100);
    }
});

onUnmounted(() => {
    if (slider.value) {
        slider.value.removeEventListener(
            "scroll",
            handleThumbnailCarouselScroll
        );
    }
});
</script>

<template>
    <Head title="Detail" />
    <div class="w-full max-w-screen-xl mx-auto p-4 my-8 align-top">
        <Breadcrumb
            :level1="props.product.category"
            :level2="props.product"
            class="mb-10"
        />
        <div class="flex">
            <div class="box-1 w-2/6">
                <img
                    :src="'/' + props.product.images[currentImageIndex].url"
                    alt=""
                    class="w-full h-64 object-contain"
                />
                <div class="relative mt-4 w-full">
                    <div
                        class="next absolute cursor-pointer left-0 top-1/3 opacity-80 bg-gray-300"
                        @click="handlePrev"
                        v-if="leftArrowVisible"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 19.5 8.25 12l7.5-7.5"
                            />
                        </svg>
                    </div>
                    <div
                        class="slider overflow-x-hidden flex gap-4"
                        ref="slider"
                    >
                        <template
                            v-for="(image, index) in props.product.images"
                            :key="image.id"
                        >
                            <img
                                :src="'/' + image.url"
                                alt=""
                                class="carousel__item rounded-lg cursor-pointer border w-20 h-20 border-grey object-contain"
                                @click="() => changeImageIndex(index)"
                            />
                        </template>
                    </div>
                    <div
                        class="next absolute cursor-pointer right-0 top-1/3 opacity-80 bg-gray-300"
                        @click="handleNext"
                        v-if="rightArrowVisible"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m8.25 4.5 7.5 7.5-7.5 7.5"
                            />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="box-2 flex flex-col gap-4 w-4/6 px-10">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ props.product.name }}
                </h2>
                <h1 class="font-bold text-3xl text-green-800">
                    {{ formatCurrency(props.product.price) }}
                </h1>
                <div class="my-2">
                    <span
                        v-if="props.product.stock !== null"
                        id="badge-dismiss-default"
                        class="whitespace-nowrap inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 bg-green-100 rounded"
                        >Stock: {{ props.product.stock }}</span
                    >
                    <span
                        id="badge-dismiss-default"
                        class="whitespace-nowrap inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 bg-green-100 rounded"
                        >Min order: {{ props.product.min_order }}</span
                    >
                </div>
                <p v-html="props.product.description"></p>
            </div>
        </div>
    </div>

    <ProductCartAction
        :product="props.product"
        :carts="$page.props.carts"
        v-if="$page.props.auth.user?.role_id === 2"
    />

    <Cart v-if="$page.props.auth.user?.role_id == 2" />
</template>
