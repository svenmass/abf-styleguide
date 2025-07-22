# GitHub Actions Setup für automatisches Deployment

## Erforderliche GitHub Secrets

Gehe zu deinem Repository → Settings → Secrets and variables → Actions und erstelle folgende Secrets:

### 1. CLOUDWAYS_HOST
Der Hostname deines Cloudways-Servers
```
Beispiel: 123.456.789.123 oder server.cloudways.com
```

### 2. CLOUDWAYS_USERNAME  
Dein SSH-Username (normalerweise "master")
```
master
```

### 3. CLOUDWAYS_SSH_KEY
Dein privater SSH-Key für Cloudways
```
-----BEGIN OPENSSH PRIVATE KEY-----
[Dein privater SSH-Key hier einfügen]
-----END OPENSSH PRIVATE KEY-----
```

### 4. CLOUDWAYS_PORT
SSH-Port deines Servers (normalerweise 22 oder custom)
```
22
```

### 5. CLOUDWAYS_APP_ID
Deine Cloudways Application ID
```
Beispiel: abcdefghijk
```

## SSH-Key Setup für Cloudways

### 1. SSH-Key generieren (falls noch nicht vorhanden)
```bash
ssh-keygen -t rsa -b 4096 -C "deployment@github-actions"
```

### 2. Public Key zu Cloudways hinzufügen
- Cloudways Dashboard → Server → Security → SSH Public Key
- Inhalt der `.pub` Datei einfügen

### 3. Private Key als GitHub Secret speichern
- Inhalt der privaten Key-Datei als `CLOUDWAYS_SSH_KEY` Secret hinzufügen

## Wie es funktioniert

🔄 **Automatisch**: Bei jedem Push auf `content-blocks` Branch der das Theme-Verzeichnis betrifft
📱 **Manuell**: Über GitHub Actions Tab → "Deploy Theme to Cloudways" → Run workflow

## Vorteile

✅ Vollautomatisches Deployment
✅ Nur bei Theme-Änderungen ausgeführt  
✅ Sichere SSH-Verbindung
✅ Berechtigungen werden automatisch gesetzt
✅ Deployment-Status sichtbar in GitHub

## Troubleshooting

### SSH-Verbindung fehlgeschlagen
- SSH-Key korrekt in Cloudways hinterlegt?
- Hostname und Port richtig?
- Username korrekt (meist "master")?

### Deployment schlägt fehl
- App-ID korrekt?
- Pfad zu wp-content stimmt?
- Git-Repository auf Server initialisiert?

### Erste Einrichtung erforderlich
Falls das Git-Repository noch nicht auf dem Server existiert, erst einmal manuell das Sparse-Checkout Setup durchführen (siehe CLOUDWAYS-DEPLOYMENT.md). 