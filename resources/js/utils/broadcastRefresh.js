const REQUEST_REFRESH_KEY = "request_refresh";

const handleRefreshRequest = (e) => {
    if (e.key !== REQUEST_REFRESH_KEY) return;
    window.location.reload();
};

export const broadcastRefresh = () => {
    localStorage.setItem(REQUEST_REFRESH_KEY, "yes");
    localStorage.removeItem(REQUEST_REFRESH_KEY);
};

export const registerRefreshRequestReceiver = () => {
    window.addEventListener("storage", handleRefreshRequest);
};
