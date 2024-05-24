import { broadcastRefresh } from "@/utils/broadcastRefresh";
import { router } from "@inertiajs/vue3";

const logout = () => {
    router.post(route("logout"), undefined, {
        onSuccess: () => {
            broadcastRefresh();
        },
    });
};

export default logout;
