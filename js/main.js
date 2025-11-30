document.getElementById("reservation_form").addEventListener("submit", function (e) {
    e.preventDefault();

    const data = {
        first_name: document.getElementById("first_name").value.trim(),
        last_name: document.getElementById("last_name").value.trim(),
        phone: document.getElementById("phone").value.trim(),
        email: document.getElementById("email").value.trim(),
        start_date: document.getElementById("start_date").value,
        end_date: document.getElementById("end_date").value,
        products: []
    };

    // savācam produktus
    document.querySelectorAll(".product-checkbox").forEach(ch => {
        if (ch.checked) {
            const productId = ch.dataset.id;
            const routeSelect = document.querySelector(`.route-select[data-product="${productId}"]`);

            let routeId = null;
            if (routeSelect) {
                routeId = routeSelect.value !== "" ? parseInt(routeSelect.value) : null;
            }

            data.products.push({
                product_id: parseInt(productId),
                quantity: 1,
                route_id: routeId
            });
        }
    });

    // sūtam AJAX pieprasījumu
    fetch("/ajax/booking_submit.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
        .then(res => res.json())
        .then(response => {
            if (response.error) {
                alert("Kļūda: " + response.error);
                return;
            }

            alert(
                "Rezervācija veiksmīgi izveidota!\n" +
                "Kopējā summa: " + response.total + " €\n" +
                "Priekšapmaksa (50%): " + response.prepayment + " €"
            );
        })
        .catch(err => {
            console.error(err);
            alert("Radās kļūda, mēģini vēlreiz.");
        });
});
