# 🇵🇭 PilaFree - Rural Health Unit & Public Clinic Virtual Queue System

> **Makataong Serbisyong Pangkalusugan para sa Bawat Pilipino.**
> Isang solusyon para mawala ang 3:00 AM na pila sa mga pampublikong health center, may live TV waiting board, automated SMS updates, at Libreng Gamot inventory tracker.

---

## 🌟 Pangunahing Katangian (Features)

1. **Digital Kiosk & Online Ticket (`/`)**
   - Pagkuha ng queue ticket gamit ang cellphone o web.
   - **Priority Lane Support:** Automatic na pinapauna ang mga Senior Citizens (60+), PWD, Buntis, at may dalang sanggol (`OPD-P01`).
   - Kalkulasyon ng tinatayang oras ng paghihintay (Estimated Wait Time).

2. **📺 TV Waiting Room Board (`/tv`)**
   - High-contrast fullscreen view na pwedeng i-project sa Smart TV o monitor sa clinic waiting area.
   - **Real-Time Polling & WebSockets:** Kusang nag-a-update tuwing may tinatawag na pasyente.
   - **Audio Chime & Speech Synthesis:** Nagsasalita ang computer/TV (*"Now calling, ticket number OPD-P01, please proceed to Consultation Room 1"*).

3. **🩺 Doctor & Nurse Station Terminal (`/doctor`)**
   - One-click buttons: `Call Next`, `Repeat Call (TV)`, `Start Consultation`, `Mark No-Show`.
   - **Free Medicine Dispensing:** Direktang mag-reseta ng gamot (Paracetamol, Amlodipine, Metformin, etc.) na kusa ring magbabawas sa live inventory.

4. **💊 Transparent "Libreng Gamot" Tracker (`/medicines`)**
   - Makikita ng mga mamamayan kung may available pang stock ng maintenance medicines bago maglakad papunta sa health center.

5. **📱 Inbound & Outbound SMS Gateway (`/sms/simulate`)**
   - Suporta para sa keypad phones via Semaphore / Twilio API webhook.
   - Text syntax: `PILA [CLINIC] [PANGALAN] [PRIORITY]`
   - Halimbawa: `PILA OPD JUAN DELA CRUZ SENIOR`
   - Automated status checking: `STATUS OPD-001`
   - Automated stock check: `GAMOT PARACETAMOL`

---

## 🚀 Paano Patakbuhin (Quick Start)

### 1. Buksan ang Terminal sa Project Directory:
```bash
cd C:\Users\PC\.gemini\antigravity\scratch\pilafree
```

### 2. Patakbuhin ang Development Server:
```bash
php artisan serve
```
Mabubuksan ang web application sa: **`http://127.0.0.1:8000`**

---

## 🎭 Paano I-demo sa mga Kaibigan (The "WOW" Experience)

1. **Buksan ang dalawang browser window (o isang laptop + isang cellphone):**
   - Sa unang window (o TV/monitor): Buksan ang **`http://localhost:8000/tv`** at pindutin ang **"I-enable ang Boses (Audio)"**.
   - Sa pangalawang window: Buksan ang **`http://localhost:8000/doctor`** (Doctor Station).
   - Sa cellphone mo: Buksan ang **`http://[IP-Address-ng-Laptop]:8000`** (o sa ibang tab) at kumuha ng bagong ticket.

2. **Subukan ang Pila:**
   - Sa Doctor Console, pindutin ang **"TAWAGIN ANG SUSUNOD SA PILA"**.
   - **Panoorin ang TV Board:** Agad magpa-flash ang ticket number, tutunog ang chime, at **magsasalita ang computer** sa pangalan ng ticket!
   - **Tingnan ang Ticket Screen ng Pasyente:** Kusa itong magiging kulay kahel na may nakasulat na *"TINATAWAG NA KAYO NGAYON!"*.

3. **Subukan ang SMS Simulator:**
   - Sa ilalim ng Doctor Station, gamitin ang **Interactive SMS Simulation Gateway** para magpadala ng simulated text (e.g., `PILA OPD Gorio SENIOR`) at makita ang automatic bot response.

---

## 🧪 Automated Testing
Upang subukan ang lahat ng endpoints at database rules:
```bash
php artisan test
```
Lahat ng tests ay passing (100% green).
