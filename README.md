# 🏕️ Ievziedi Kempinga Datu Bāze — Versija 1.0

Šis ir **projekta “Ievziedi” kempinga datubāzes pirmais izlaidums**.  
Datubāze izstrādāta, lai glabātu informāciju par kempinga piedāvātajiem pakalpojumiem, piemēram — mājiņām, pirtīm, kubliem, laivām un SUP dēļiem.

## 📘 Apraksts
Datubāze izveidota, izmantojot **phpMyAdmin** un **MySQL**.  
Tajā ietilpst vairākas tabulas, kas sākotnēji bija sadalītas pa atsevišķiem pakalpojumiem.

Šī versija paredz:
- Pamata tabulu struktūras izveidi
- Katrs pakalpojuma veids ir atsevišķā tabulā (piem., `Boats`, `Sup_boards`, `Cottages`, `Sauna`, `Hot_tub`)
- Primārie atslēgas lauki (`id`) un pamata datu tipi
- Sākotnējā relāciju plānošana ER diagrammai

## 🔄 Nākamā versija (plāns)
Nākamajā versijā datubāze tiks uzlabota:
- Visi pakalpojumi tiks apvienoti vienā **`products`** tabulā
- Tiks pievienotas tabulas **`clients`** un **`bookings`**, lai pārvaldītu rezervācijas
- Plānots uzlabot relācijas un datu strukturējumu

---

👩‍💻 Autors: *Adrians Vincents Šuķevics*  
📅 Versija: 1.0  
🛠️ Datubāzes tips: MySQL
