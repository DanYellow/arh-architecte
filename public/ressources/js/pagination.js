function ready(fn) {
    if (document.readyState != "loading") {
        fn();
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

ready(() => {
    document.querySelector("[data-pagination-select]").addEventListener('change', (e) => {
        window.location.href = `?page=${e.target.value}`;
    })
})