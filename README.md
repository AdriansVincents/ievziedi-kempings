# Ievziedi Kempinga Mājaslapa — Versija 4.1

Šajā versijā veikti būtiski datu bāzes un sistēmas uzlabojumi, kas sagatavo projektu nākamajam lielajam solim — pilnai rezervēšanas sistēmai ar pieejamības pārbaudi, priekšapmaksu, gaidīšanas sarakstu un produktu statusiem.

---

## **Kas jauns versijā 4.1**

### 1. Uzlabots datu bāzes modelis
Pievienoti jauni lauki un uzlabota sistēma rezervācijām, produktiem, maksājumiem un gaidīšanas sarakstam.

### Uzlabojumi `bookings` tabulā:
- `prepayment_amount`
- `remaining_amount`
- `cancelled_at`
- `created_at`, `updated_at`
- `route_id` padarīts par NULL (lai atbalstītu produktus bez maršrutiem)

### Uzlabojumi `booking_products` tabulā:
- `product_price_at_booking`
- `product_name_snapshot`

### Uzlabojumi `prepayment` tabulā:
- `transaction_id` lauks maksājumu identifikācijai

### Uzlabota `statuses` tabula:
Ievesta **status_type** sistēma:
- 1 – bookings
- 2 – products
- 3 – payments
- 4 – clients
- 5 – waitlist

Pievienoti arī **jauni statusi** gaidīšanas sarakstam.

### Jaunumi `waitlist` tabulā:
- `status_id`
- `notes`
- `email` un `product_id` indeksi

---

## **2. Fundamentāls pamats rezervēšanas sistēmai**
Versija 4.1 sagatavo mājaslapu nākamajiem soļiem:

- Produktu pieejamības pārbaude pa datumiem
- Automātiska kopējās cenas aprēķināšana
- Priekšapmaksas (50%) aprēķins un sūtīšana klientam
- Atlaižu vai cita veida papildus maksājumu atbalsts
- Gaidīšanas saraksta sistēma
- Paziņojumu nosūtīšana, kad produkts atkal ir pieejams

---

## Autors
**Adrians Vincents Šuķevics**

**Datubāze:** MySQL  
**Versija:** 4.1  
**Datums:** 2025-11-22
