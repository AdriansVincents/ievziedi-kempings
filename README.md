# Ievziedi Kempinga Mājaslapa — Versija 5.0

Šī versija ievieš pilnībā funkcionējošu rezervācijas sistēmu ar produktu izvēli, daudzumu, automātisku cenu aprēķinu, priekšapmaksu un uzlabotu lietotāja pieredzi.

---

## **Kas jauns versijā 5.0**

### 1. Pilnībā strādājoša rezervācijas forma
- Klients var ievadīt savus datus.
- Var izvēlēties produktus un to daudzumu.
- Pieejamais daudzums tiek kontrolēts jau frontend pusē.
- Visi aprēķini notiek automātiski.

### 2. Automātisks cenu aprēķins
- Sistēma rēķina:
  - Kopējo cenu
  - 50% priekšapmaksu
  - Atlikumu (jāmaksā uz vietas)
- Aprēķini atjaunojas līdzi katrai izmaiņai.

### 3. Uzlabota produktu atlase
- Katram produktam ir pieejams daudzums (`available_units` no DB).
- Klients nevar izvēlēties vairāk, nekā reāli pieejams.
- Izvēle kļuvusi vizuāli tīrāka un ērtāka.

### 4. UI / UX pilnveide
- Rezervācijas sadaļa pārstrādāta un sakārtota.
- Dizains modernizēts, ievērojot iepriekšējo stilu.
- Notīrīts liekais CSS un kods optimizēts.

### 5. Backend atjauninājumi
- Rezervācija veiksmīgi tiek saglabāta datubāzē.
- Skārieni nav vajadzīgi — dati nonāk DB automātiski.

---

## Datu bāzes izmaiņas (salīdzinot ar 4.1)

- Produkta daudzuma lauks: `available_units`
- Rezervācijas glabā:
  - klienta datus
  - izvēlētos produktus un to daudzumu
  - cenu aprēķinu
  - priekšapmaksas un atlikuma summas

Struktūra sagatavota nākamajiem soļiem:  
→ pieejamības pārbaude pa datumiem  
→ reālā laika atlikumu aprēķināšana  
→ gaidīšanas saraksti  
→ e-pastu un rēķinu ģenerēšana  

---

## Nākamie soļi
- E-pasta apstiprinājuma nosūtīšana klientam.  
- PDF rēķina ģenerēšana.  
- Produktu rezervēšana pa datumiem, lai nepieļautu pārdošanu.  
- Admin panelis rezervāciju apskatei.  
- Stripe vai Swedbank maksājumu integrācija priekšapmaksai.

---

## Autors
**Adrians Vincents Šuķevics**

**Datu bāze:** MySQL  
**Versija:** 5.0  
**Datums:** 2025-12-01  

