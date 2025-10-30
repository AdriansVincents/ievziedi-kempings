# Ievziedi Kempinga Datu Bāze — Versija 2.0

Šī ir **uzlabotā datubāzes versija** kempingam *“Ievziedi”*, kas paredzēta reālai tīmekļa vietnes lietošanai.

---

## Apraksts

Versija 2.0 pārveido sākotnējo struktūru, apvienojot pakalpojumus vienotā sistēmā, kas ir **elastīgāka un vieglāk uzturama**.  
Tagad datubāze ir pielāgota, lai pārvaldītu gan klientus, gan rezervācijas, gan visus pieejamos kempinga pakalpojumus.

---

## Datubāzes struktūra

### **Galvenās tabulas:**
1. **`products`** — satur visus kempinga pakalpojumus (mājiņas, pirts, kubli, laivas, SUP dēļi, velosipēdi u.c.)
   - `id` — unikāls produkta identifikators  
   - `name` — produkta nosaukums  
   - `type` — kategorija (piem., “house”, “sauna”, “boat”, “bike” u.c.)  
   - `price` — cena par vienību vai dienu  
   - `description` — īss apraksts  
   - `image` — attēla ceļš vai URL

2. **`clients`** — glabā klientu informāciju  
   - `id`, `name`, `email`, `phone`, `created_at`

3. **`bookings`** — rezervāciju pārvaldība  
   - `id`, `client_id`, `product_id`, `start_date`, `end_date`, `status`

4. **(Papildu)** `reviews`, `contact_messages` vai citas palīgtabulas var tikt pievienotas vēlāk.

---

## Galvenās izmaiņas salīdzinājumā ar versiju 1.0
- Visi pakalpojumi apvienoti vienā tabulā **`products`** (vienota struktūra)
- Pievienota **`clients`** tabula klientu pārvaldībai
- Pievienota **`bookings`** tabula rezervāciju glabāšanai
- Uzlabota relāciju struktūra (izmantojot ārējās atslēgas)
- Vieglāka paplašināšana un datu pārvaldība nākotnē

---

## Versiju vēsture

| Versija | Apraksts | Datums |
|----------|-----------|--------|
| 1.0 | Sākotnējā tabulu versija (atsevišķi produkti) | 2025-10-27 |
| 2.0 | Apvienota un uzlabota datubāze, pievienoti klienti un rezervācijas | 2025-10-28 |

---

**Autors:** Adrians Vincets Šuķevics 
**Versija:** 2.0  
**Datubāze:** MySQL  
**Projekts:** Ievziedi Kempings
