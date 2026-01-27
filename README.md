
# PROBLEMA
L'applicazione gestisce le spedizioni di merci tra due città per i vari clienti (team).

Ogni spedizione ha dei referenti per il punto di partenza (start) e il punto di arrivo (end).

Allo stato attuale quando un referente viene aggiunto tramite il pannello di amminstrazione è salvato come nuovo elemento e collegato alla spedizione.

Questo ha portato la tabella ad avere molti record duplicati.

Come possiamo risolvere questo problema al fine di migliorare la nostra applicazione e l'esperienza utente?


## Installazione del sistema

1) Scarica il repository in locale

```
git clone https://github.com/karrycar/devtest.git

cd devtest
```

2) Rinomina il file .env.example

```
mv .env.example .env
```

3) Esegui i seguenti comandi

```
composer install

sail up -d

sail artisan key:generate

sail art migrate:fresh --seed

npm install

npm run dev
```

# Accesso al pannello
http://localhost:3000/login

user: admin@karrycar.com

pass: password
