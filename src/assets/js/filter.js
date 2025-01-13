document.addEventListener("DOMContentLoaded", () => {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const products = document.querySelectorAll(".product");

    filterButtons.forEach(button => {
        button.addEventListener("click", () => {
            const category = button.getAttribute("data-category");

            // Atualizar classes de botões
            filterButtons.forEach(btn => btn.classList.remove("btn-primary"));
            button.classList.add("btn-primary");

            // Mostrar ou esconder produtos com base na categoria
            products.forEach(product => {
                const productCategory = product.getAttribute("data-category");

                if (category === "all" || productCategory === category) {
                    product.style.display = "block"; // Mostrar
                } else {
                    product.style.display = "none"; // Esconder
                }
            });
        });
    });
});
