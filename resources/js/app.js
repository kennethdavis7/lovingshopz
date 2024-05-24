import "./bootstrap";

import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimeVue from "primevue/config";
import Toast from "vue-toastification";
import BadgeDirective from "primevue/badgedirective";
import "primevue/resources/themes/aura-light-green/theme.css";
import "vue-toastification/dist/index.css";
import { registerRefreshRequestReceiver } from "./utils/broadcastRefresh";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

registerRefreshRequestReceiver();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const component = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );

        if (component.default.layout) return component;

        if (
            name.startsWith("Admin") ||
            name.startsWith("Auth/Profile") ||
            name.startsWith("Shopper/Orders/Index")
        ) {
            component.default.layout = AuthenticatedLayout;
        } else if (
            !name.startsWith("Auth") &&
            !name.startsWith("Shopper/Orders/Finish")
        ) {
            component.default.layout = GuestLayout;
        }

        return component;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast)
            .use(PrimeVue)
            .directive("badge", BadgeDirective)
            .mixin({ method: { route } })
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
