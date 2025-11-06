# Ievziedi Kempinga Datu Bāze — Versija 3.0

Šī ir jaunākā, uzlabotā datubāzes versija projektam **“Ievziedi Kempings”**, kas izstrādāta, lai atvieglotu produktu, rezervāciju un maksājumu pārvaldību kempinga mājaslapā.

---

## Apraksts

Versija **3.0** būtiski uzlabo iepriekšējo datu modeli, pievienojot vairāk reālas rezervācijas funkcionalitātes un elastību:

- Statusi tagad tiek glabāti atsevišķā tabulā `statuses`;
- Izveidota `routes` tabula laivu un SUP dēļu maršrutu un cenu pārvaldībai;
- Produktu un rezervāciju sasaistīšana caur `bookings_products` tabulu (daudz–daudz relācija);
- Pievienota `prepayments` tabula priekšapmaksas pārvaldībai;
- Papildināta produktu struktūra ar `available_units`, kas ļauj uzskaitīt pieejamos eksemplārus (piemēram, 2 mājiņas, 5 SUP dēļi utt.);
- Pievienota `images` mape ar visiem attēliem.

---

## Datu bāzes struktūra

### Galvenās tabulas

| Tabula | Apraksts |
|--------|-----------|
| **products** | Visi kempinga pakalpojumi (mājiņas, pirts, kubuls, laivas, SUP dēļi, velosipēdi utt.) |
| **routes** | Maršruti laivām un SUP dēļiem ar attālumiem, dienām un cenām |
| **clients** | Klientu dati (vārds, e-pasts, telefons, statuss) |
| **bookings** | Rezervāciju pamatinformācija (klients, datumi, statuss) |
| **bookings_products** | Saite starp rezervācijām un izvēlētajiem produktiem |
| **prepayments** | Priekšapmaksas informācija par rezervācijām |
| **statuses** | Visu iespējamo statusu saraksts (piemēram: Pieejams, Rezervēts, Aizņemts, Apmaksāts) |

---

## Galvenās izmaiņas salīdzinājumā ar versiju 2.0

| Izmaiņa | Apraksts |
|----------|-----------|
| **Statusi** pārvietoti uz atsevišķu tabulu `statuses` | Nodrošina elastīgāku statusu pārvaldību |
| **Maršruti (`routes`)** | Cenas un ilgumi vairs nav tieši produktos |
| **`bookings_products`** | Tagad iespējams rezervēt vairākus produktus vienā pasūtījumā |
| **`prepayments`** | Priekšapmaksas apstrāde |
| **`available_units`** | Norāda, cik vienību no konkrētā produkta ir pieejams |
| **Attēlu mape** | Projekts satur `images/` direktoriju ar visiem produktu attēliem |

---

## Datubāzes fails

Faila atrašanās vieta:
database/ievziedi_db.sql

---

## Attēli

Visi produktu attēli tiek glabāti mapē:
images/

---

Datubāze: MySQL
Autors: Adrians Vincets Šuķevics
Versija: 3.0
Projekts: Ievziedi Kempings