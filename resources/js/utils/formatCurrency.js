const moneyFormatter = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
});

const formatCurrency = (num) => {
    return moneyFormatter.format(num);
};

export default formatCurrency;
