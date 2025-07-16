# Logo & Icon Setup - ABF Styleguide Theme

## 🎯 Übersicht

Das Theme unterstützt jetzt vollständige Logo- und Icon-Verwaltung über die WordPress Admin-Oberfläche.

## 📋 Verfügbare Felder

### Logo & Branding
- **Desktop Logo**: Für Desktop-Ansicht (empfohlen: 200x60px, PNG/SVG/WebP)
- **Mobile Logo**: Für Mobile-Ansicht (empfohlen: 150x45px, PNG/SVG/WebP)  
- **Logo Alt-Text**: Für Barrierefreiheit

### Favicon & Icons
- **Favicon**: Browser-Tab Icon (empfohlen: 32x32px, ICO/PNG/SVG)
- **Apple Touch Icon**: iOS-Geräte (empfohlen: 180x180px, PNG/SVG)
- **Android Touch Icon**: Android-Geräte (empfohlen: 192x192px, PNG/SVG)

## 🚀 Einrichtung

### 1. Theme Settings öffnen
1. Gehe zu **WordPress Admin → Theme Settings**
2. Du siehst drei Tabs: **Logo & Branding**, **Favicon & Icons**, **Farben**

### 2. Logos hochladen
1. **Desktop Logo**: Lade dein Hauptlogo hoch (PNG/SVG/WebP empfohlen)
2. **Mobile Logo**: Lade eine kleinere Version für Mobile hoch
3. **Alt-Text**: Gib einen beschreibenden Text ein (z.B. "Firmenname Logo")

### 3. Icons hochladen
1. **Favicon**: 32x32px ICO, PNG oder SVG
2. **Apple Touch Icon**: 180x180px PNG oder SVG
3. **Android Touch Icon**: 192x192px PNG oder SVG

### 4. Speichern
- Klicke auf **"Aktualisieren"** um alle Einstellungen zu speichern

## 💻 Verwendung im Code

### Logo ausgeben
```php
// Desktop Logo
abf_output_logo('desktop', 'logo-desktop');

// Mobile Logo  
abf_output_logo('mobile', 'logo-mobile');

// Logo mit Link
echo '<a href="' . esc_url(home_url('/')) . '" class="logo-container">';
abf_output_logo('desktop', 'logo-desktop');
echo '</a>';
```

### Logo-URL abrufen
```php
$desktop_logo_url = abf_get_logo_url('desktop');
$mobile_logo_url = abf_get_logo_url('mobile');
```

### Logo-Präsenz prüfen
```php
if (abf_has_logo('desktop')) {
    // Desktop Logo ist vorhanden
}
```

### Icon-Daten abrufen
```php
$icon_data = abf_get_icon_data();
$favicon_url = $icon_data['favicon']['url'] ?? '';
```

## 🎨 CSS-Klassen

### Verfügbare Klassen
- `.logo-container`: Container für Logo-Ausrichtung
- `.logo-desktop`: Desktop-Logo (wird auf Mobile ausgeblendet)
- `.logo-mobile`: Mobile-Logo (wird auf Desktop ausgeblendet)
- `.text-logo`: Fallback-Text-Logo

### Responsive Verhalten
```css
/* Desktop: Desktop-Logo anzeigen */
.logo-desktop { display: block; }
.logo-mobile { display: none; }

/* Mobile: Mobile-Logo anzeigen */
@media (max-width: 767px) {
    .logo-desktop { display: none; }
    .logo-mobile { display: block; }
}
```

## 🔧 Fallback-Verhalten

### Logo-Fallback
- Wenn kein Logo hochgeladen ist → Text-Logo mit Blog-Name
- Wenn kein Mobile-Logo → Desktop-Logo wird verwendet
- Alt-Text-Fallback → Blog-Name

### Icon-Fallback
- Wenn kein Favicon → Standard SVG-Favicon wird verwendet
- Touch-Icons sind optional

## 📱 Empfohlene Bildgrößen

### Logos
- **Desktop**: 200x60px (PNG/SVG/WebP)
- **Mobile**: 150x45px (PNG/SVG/WebP)

### Icons
- **Favicon**: 32x32px (ICO/PNG/SVG)
- **Apple Touch**: 180x180px (PNG/SVG)
- **Android Touch**: 192x192px (PNG/SVG)

## 🎯 Best Practices

### Logo-Dateien
- Verwende **SVG** für beste Skalierbarkeit und kleinste Dateigröße
- **PNG** mit Transparenz für komplexe Logos
- **WebP** für moderne Browser (kleinere Dateigröße)
- **JPG** nur für Fotologos

### Icon-Dateien
- **Favicon**: SVG für moderne Browser, ICO für ältere Browser
- **Touch-Icons**: SVG für beste Skalierbarkeit, PNG als Fallback
- Quadratische Formate für Touch-Icons

### Performance
- Optimiere Bildgrößen vor dem Upload
- Verwende WebP für moderne Browser (falls unterstützt)
- Komprimiere PNG-Dateien

## 🔍 Troubleshooting

### Logo wird nicht angezeigt
1. Prüfe ob Logo in Theme Settings hochgeladen wurde
2. Prüfe Dateiformat (PNG/SVG/WebP/JPG)
3. Prüfe CSS-Klassen im Template

### Favicon wird nicht angezeigt
1. Cache leeren (Browser + WordPress)
2. Prüfe Dateiformat (ICO/PNG/SVG)
3. Warte 24h für Browser-Cache-Update

### SVG-Upload funktioniert nicht
1. Prüfe ob SVG-Datei gültig ist (enthält `<svg>` Tag)
2. SVG darf keine gefährlichen Elemente enthalten (script, object, etc.)
3. SVG darf keine externen Referenzen haben

### Mobile/Desktop Logo-Wechsel funktioniert nicht
1. Prüfe CSS-Media-Queries
2. Prüfe ob beide Logos hochgeladen wurden
3. Teste auf echten Geräten (nicht nur DevTools)

## 📚 Weiterführende Links

- [WordPress Media Library](https://wordpress.org/support/article/media-library/)
- [Favicon Generator](https://realfavicongenerator.net/)
- [SVG Optimizer](https://jakearchibald.github.io/svgomg/) 