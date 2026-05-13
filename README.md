# 🏗️ SmartSite - Sistema di Gestione Documentale Ingegneristica (DMS)

SmartSite è una piattaforma gestionale full-stack (DMS) progettata per aziende del settore ingegneristico. Permette la gestione strutturata di documenti tecnici, il tracciamento rigoroso delle revisioni, i flussi di approvazione e mantiene un registro di audit inalterabile per garantire la massima sicurezza e conformità aziendale.

## 🚀 Funzionalità Principali

### 📄 Gestione Documentale e Ricerca
- **Anagrafica Documenti:** Creazione intuitiva di nuovi documenti con generazione automatica di codici identificativi.
- **Ricerca in Tempo Reale:** Motore di ricerca reattivo integrato nella dashboard per filtrare i documenti per codice o titolo senza ricaricare la pagina.
- **Paginazione Ottimizzata:** Gestione efficiente di grandi moli di dati tramite impaginazione server-side.

### 🔄 Controllo Revisioni (Versioning)
- **Upload Sicuro:** Caricamento di file PDF associati a specifiche revisioni.
- **Visualizzazione e Download:** Preview del documento direttamente nel browser o download del file fisico.
- **Flusso di Approvazione:** Sistema di workflow per i responsabili (Stati: *In Attesa, Approvato, Rifiutato*) gestito con interfaccia reattiva.

### 🕵️‍♂️ Sicurezza e Tracciabilità (Audit Log)
- **Action Tracking:** Registrazione silenziosa di ogni evento critico (creazione documenti, upload revisioni, visualizzazione PDF a schermo, download, approvazioni).
- **Esportazione PDF:** Generazione di report ufficiali in PDF del registro di sistema, con filtri avanzati per intervallo di date.
- **Architettura Multi-Tenant:** Predisposizione nativa per la segregazione dei dati aziendali.
- **Primary Keys Sicure:** Utilizzo esclusivo di UUID per la protezione delle risorse nel database.

---

## 🛠️ Stack Tecnologico

Il progetto adotta un'architettura ibrida a monolite moderno, combinando la robustezza di Laravel con la reattività di Vue.js senza l'overhead di un'API REST tradizionale.

**Backend:**
- PHP 8.x
- Laravel 11
- MySQL (gestito via Laravel Sail/Docker)
- Libreria `barryvdh/laravel-dompdf` per la generazione di report

**Frontend:**
- Vue 3 (Composition API)
- Inertia.js (Routing e data binding)
- Tailwind CSS (Styling e UI flessibile)
- Laravel Breeze (Infrastruttura di Autenticazione)

---

## 💻 Installazione e Setup Locale

Per eseguire il progetto in ambiente di sviluppo locale, è necessario avere [Docker](https://www.docker.com/) installato sul proprio computer.

1. **Clona il repository:**
   ```bash
   git clone [https://github.com/federicamudu/SmartSite.git](https://github.com/federicamudu/SmartSite.git)
   cd smartsite
   ```

2. **Installa le dipendenze PHP**
    ```bash
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
    ```

3. **Configura le variabili d'ambiente:**
    ```bash
    cp .env.example .env
    ```

4. **Avvia i container Docker tramite Sail:**

    ```bash
    ./vendor/bin/sail up -d
    ```

5. **Genera la chiave dell'applicazione e migra il Database:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate --seed
    ```

6. **Installa e compila gli asset Frontend (Vue/Tailwind):**
    ```bash
    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run dev
    ```

L'applicazione sarà ora accessibile all'indirizzo: http://localhost

--- 

## 🔑 Primo Accesso (Dati di Test)

Eseguendo il comando di seeding (migrate --seed), il sistema crea automaticamente un utente e un tenant di prova. Puoi accedere alla piattaforma utilizzando queste credenziali:

- Email: admin@acme.com

- Password: password123