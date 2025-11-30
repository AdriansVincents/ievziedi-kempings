<?php
require_once "../connect/db_connect.php";
include '../includes/header.php';

// Ielādē visus produktus (izmanto price_per_day / is_routable / available_units)
$productQuery = $conn->query("SELECT id, name, price_per_day, is_routable, available_units FROM products ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Rezervācija – Ievziedi Kempings</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<main class="booking-container">

    <h1>Rezervācija</h1>

    <form id="bookingForm" method="POST" action="../ajax/booking_submit.php" novalidate>

        <!-- KLIENTA DATI -->
        <section>
            <h2>1. Jūsu dati</h2>
            <label>Vārds <input type="text" name="firstname" id="firstname" required></label><br>
            <label>Uzvārds <input type="text" name="lastname" id="lastname" required></label><br>
            <label>Telefons <input type="tel" name="phone" id="phone" required></label><br>
            <label>E-pasts <input type="email" name="email" id="email" required></label>
        </section>

        <hr>

        <!-- DATUMI -->
        <section>
            <h2>2. Datumi</h2>
            <label>No: <input type="date" name="date_from" id="date_from" required></label>
            <label>Līdz: <input type="date" name="date_to" id="date_to" required></label>
            <p class="muted">Ja atstāj tukšus datumus, tiks uzskatīts par 1 dienu rezervāciju.</p>
        </section>

        <hr>

        <!-- PRODUKTI + ROUTES + DAUDZUMS -->
        <section>
            <h2>3. Izvēlieties produktus</h2>
            <div class="product-list" id="product-list">
                <?php while ($p = $productQuery->fetch_assoc()):
                    $price = ($p['price_per_day'] === null) ? 0 : (float)$p['price_per_day'];
                    $is_routable = (int)$p['is_routable'];
                ?>
                    <div class="product-item" data-product-id="<?= $p['id'] ?>" data-available="<?= (int)$p['available_units'] ?>">
                        <label class="product-label">
                            <input type="checkbox"
                                   class="product-checkbox"
                                   data-product-id="<?= $p['id'] ?>"
                                   data-price="<?= htmlspecialchars($price, ENT_QUOTES) ?>"
                                   data-routable="<?= $is_routable ?>">
                            <strong><?= htmlspecialchars($p['name']) ?></strong>
                            <span class="product-price">(<?= number_format($price,2) ?> € / dienā)</span>
                            <?php if ($p['available_units'] !== null): ?>
                                <span class="muted">— Pieejami: <?= (int)$p['available_units'] ?></span>
                            <?php endif; ?>
                        </label>

                        <!-- daudzums kontrole -->
                        <div class="qty-controls" style="margin-left:12px;">
                            <button type="button" class="qty-minus" data-product-id="<?= $p['id'] ?>">−</button>
                            <input type="number" class="qty-input" data-product-id="<?= $p['id'] ?>" value="1" min="1" step="1" style="width:56px;">
                            <button type="button" class="qty-plus" data-product-id="<?= $p['id'] ?>">+</button>
                        </div>

                        <!-- ROUTE SELECT (parādās ja checked un routable) -->
                        <div class="route-box" id="route_box_<?= $p['id'] ?>" style="display:none; margin-left:12px;">
                            <label>Maršruts:
                                <select class="route-select" data-product-id="<?= $p['id'] ?>">
                                    <option value="">-- Ielādē maršrutus --</option>
                                </select>
                            </label>
                            <div class="muted route-price-inline" id="route_price_<?= $p['id'] ?>" style="display:none;">
                                Maršruta cena: <span class="amount">0.00</span> €
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <hr>

        <!-- KOPĒJĀ CENA -->
        <section>
            <h2>4. Kopējā summa</h2>
            <div class="price-summary">
                <p>Kopējā cena: <strong id="total_price">0.00 €</strong></p>
                <p>Priekšapmaksa (50%): <strong id="prepayment">0.00 €</strong></p>
                <p>Atlikums (uz vietas): <strong id="remaining">0.00 €</strong></p>
            </div>
        </section>

        <hr>

        <div style="margin-top:16px;">
            <button type="submit" id="submitBtn" class="btn" style="background:#2c6f4a;">Pabeigt rezervāciju</button>
        </div>

        <p id="response_message" style="margin-top:12px;"></p>
    </form>

</main>

<script>
/* HELPERS */
function parseFloatSafe(v){ v = parseFloat(v); return isNaN(v) ? 0 : v; }
function daysBetween(startStr, endStr){
    if(!startStr || !endStr) return 1;
    const s = new Date(startStr);
    const e = new Date(endStr);
    if(isNaN(s)||isNaN(e) || e <= s) return 1;
    const msPerDay = 1000*60*60*24;
    return Math.max(1, Math.ceil((e - s) / msPerDay));
}

/* UI: checkbox, qty controls, routes loader */
document.addEventListener('DOMContentLoaded', () => {
    // quantity controls
    document.querySelectorAll('.qty-plus').forEach(btn=>{
        btn.addEventListener('click', ()=> {
            const id = btn.dataset.productId;
            const input = document.querySelector(`.qty-input[data-product-id="${id}"]`);
            const available = parseInt(document.querySelector(`.product-item[data-product-id="${id}"]`).dataset.available || 0, 10);
            let val = parseInt(input.value || 1, 10);
            val = isNaN(val)?1:val;
            if(available && val >= available) { input.value = available; } else { input.value = val + 1; }
            calculateTotals();
        });
    });
    document.querySelectorAll('.qty-minus').forEach(btn=>{
        btn.addEventListener('click', ()=> {
            const id = btn.dataset.productId;
            const input = document.querySelector(`.qty-input[data-product-id="${id}"]`);
            let val = parseInt(input.value || 1, 10);
            if(isNaN(val) || val <= 1) { input.value = 1; } else { input.value = val - 1; }
            calculateTotals();
        });
    });
    document.querySelectorAll('.qty-input').forEach(inp=>{
        inp.addEventListener('change', ()=> {
            const id = inp.dataset.productId;
            const available = parseInt(document.querySelector(`.product-item[data-product-id="${id}"]`).dataset.available || 0, 10);
            let val = parseInt(inp.value || 1, 10);
            if(isNaN(val) || val < 1) val = 1;
            if(available && val > available) val = available;
            inp.value = val;
            calculateTotals();
        });
    });

    // checkbox handlers: show/hide route select and load routes if needed
    document.querySelectorAll('.product-checkbox').forEach(cb=>{
        cb.addEventListener('change', async function(){
            const pid = this.dataset.productId;
            const isRoutable = this.dataset.routable === "1";
            const routeBox = document.getElementById('route_box_' + pid);
            if(this.checked){
                const available = parseInt(document.querySelector(`.product-item[data-product-id="${pid}"]`).dataset.available || 0, 10);
                const qtyInput = document.querySelector(`.qty-input[data-product-id="${pid}"]`);
                if(available && parseInt(qtyInput.value) > available) qtyInput.value = available;

                if(isRoutable){
                    try {
                        const res = await fetch('../ajax/get_routes.php?product_id=' + pid);
                        const routes = await res.json();
                        const select = document.querySelector(`#route_box_${pid} .route-select`);
                        select.innerHTML = '<option value="">-- Izvēlies maršrutu --</option>';
                        routes.forEach(rt=>{
                            const opt = document.createElement('option');
                            opt.value = rt.id;
                            opt.text = `${rt.from_location} → ${rt.to_location} (${Number(rt.price).toFixed(2)} €)`;
                            opt.dataset.price = Number(rt.price).toFixed(2);
                            select.appendChild(opt);
                        });
                        routeBox.style.display = 'block';
                        select.addEventListener('change', () => {
                            const sel = select.options[select.selectedIndex];
                            const rp = document.getElementById('route_price_' + pid);
                            if(sel && sel.dataset && sel.dataset.price){
                                rp.style.display = 'block';
                                rp.querySelector('.amount').textContent = Number(sel.dataset.price).toFixed(2);
                            } else { rp.style.display = 'none'; }
                            calculateTotals();
                        });
                    } catch(err){
                        console.error('Route load error', err);
                        routeBox.style.display = 'none';
                    }
                }
            } else {
                routeBox.style.display = 'none';
            }
            calculateTotals();
        });
    });

    // recalc on date change
    document.getElementById('date_from').addEventListener('change', calculateTotals);
    document.getElementById('date_to').addEventListener('change', calculateTotals);

    // ALSO when checkbox or route-select change recalc
    document.addEventListener('change', (e) => {
        if(e.target.matches('.product-checkbox') || e.target.matches('.route-select')) calculateTotals();
    });
});

/* PRICE calculation */
function calculateTotals(){
    const start = document.getElementById('date_from').value;
    const end = document.getElementById('date_to').value;
    const days = daysBetween(start, end);

    let total = 0;

    document.querySelectorAll('.product-checkbox:checked').forEach(cb=>{
        const pid = cb.dataset.productId;
        const routable = cb.dataset.routable === "1";
        const qty = parseInt(document.querySelector(`.qty-input[data-product-id="${pid}"]`).value || 1, 10);

        if(routable){
            const select = document.querySelector(`#route_box_${pid} .route-select`);
            const sel = select ? select.options[select.selectedIndex] : null;
            if(sel && sel.dataset && sel.dataset.price){
                total += parseFloatSafe(sel.dataset.price) * qty;
            }
        } else {
            const basePrice = parseFloatSafe(cb.dataset.price);
            total += basePrice * days * qty;
        }
    });

    document.getElementById('total_price').textContent = total.toFixed(2) + ' €';
    const pre = total * 0.5;
    document.getElementById('prepayment').textContent = pre.toFixed(2) + ' €';
    document.getElementById('remaining').textContent = pre.toFixed(2) + ' €';
}

/* SUBMIT AJAX */
document.getElementById('bookingForm').addEventListener('submit', function(ev){
    ev.preventDefault();

    const fname = document.getElementById('firstname').value.trim();
    const lname = document.getElementById('lastname').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const startDate = document.getElementById('date_from').value;
    const endDate = document.getElementById('date_to').value;

    if(!fname || !lname || !phone || !email){
        showResponse(false, 'Lūdzu aizpildiet visus klienta datus.');
        return;
    }

    const products = [];
    document.querySelectorAll('.product-checkbox:checked').forEach(cb=>{
        const pid = parseInt(cb.dataset.productId);
        const qty = parseInt(document.querySelector(`.qty-input[data-product-id="${pid}"]`).value || 1, 10);
        const select = document.querySelector(`#route_box_${pid} .route-select`);
        const route_id = (select && select.value) ? select.value : null;
        products.push({ product_id: pid, route_id: route_id, quantity: qty });
    });

    if(products.length === 0){
        showResponse(false, 'Lūdzu izvēlieties vismaz vienu produktu.');
        return;
    }

    const payload = {
        first_name: fname,
        last_name: lname,
        phone: phone,
        email: email,
        start_date: startDate,
        end_date: endDate,
        products: products
    };

    document.getElementById('submitBtn').disabled = true;

    fetch(this.action, {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('submitBtn').disabled = false;
        if(data.success){
            showResponse(true, data.message + (data.booking_id ? ' (Rez. ID: ' + data.booking_id + ')' : ''));
        } else {
            showResponse(false, data.message || 'Radusies kļūda.');
        }
    })
    .catch(err=>{
        document.getElementById('submitBtn').disabled = false;
        console.error(err);
        showResponse(false, 'Tīkla kļūda — mēģiniet vēlreiz.');
    });
});

function showResponse(ok, msg){
    const el = document.getElementById('response_message');
    el.textContent = msg;
    el.style.color = ok ? 'green' : 'red';
}
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
