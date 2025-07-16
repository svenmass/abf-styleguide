# Header & Navigation Setup - ABF Styleguide Theme

## 🎯 Übersicht

Das Theme verfügt jetzt über einen vollständigen Header mit responsiver Navigation und Sidebar-Layout.

## 📱 Responsive Verhalten

### Desktop (>992px)
- **Header**: Vollbreite, Logo links
- **Navigation**: Immer sichtbar als Sidebar links (300px breit)
- **Content**: Versetzt um 300px nach rechts

### Mobile (<992px)
- **Header**: Vollbreite, Logo links, Burger Menu rechts
- **Navigation**: Versteckt, wird durch Burger Menu geöffnet
- **Content**: Vollbreite

## 🎨 Header-Spezifikationen

### Header-Höhen
- **Mobile**: 74px (50px Logo + 12px + 12px)
- **Tablet**: 82px (50px Logo + 16px + 16px)
- **Desktop Small**: 98px (50px Logo + 24px + 24px)
- **Desktop Large**: 114px (50px Logo + 32px + 32px)

### Spacing
- **Mobile**: `$spacing-xs` (12px) auf allen Seiten
- **Tablet**: `$spacing-sm` (16px) auf allen Seiten
- **Desktop Small**: `$spacing-md` (24px) auf allen Seiten
- **Desktop Large**: `$spacing-lg` (32px) auf allen Seiten

### Logo
- **Höhe**: 50px (Desktop und Mobile)
- **Position**: Links im Header
- **Responsive**: Automatischer Wechsel zwischen Desktop/Mobile Logo

## 🧭 Navigation-Spezifikationen

### Sidebar-Layout
- **Breite**: 300px
- **Position**: Links, sticky
- **Höhe**: 100vh
- **Background**: Weiß mit Schatten

### Navigation-Inhalt
- **Header**: Titel + Schließen-Button (nur Mobile)
- **Content**: WordPress-Menü mit Fallback
- **Footer**: Kontakt-Informationen

### Menü-Styling
- **Hover**: Primary-Farbe als Background
- **Active**: Primary-Farbe als Background
- **Submenu**: Eingerückt mit kleinerer Schrift

## 🍔 Burger Menu

### Design
- **3 Linien**: 24px breit, 2px hoch
- **Farbe**: Primary-Farbe
- **Animation**: Smooth-Transition zu X

### Funktionalität
- **Toggle**: Öffnet/schließt Navigation
- **Overlay**: Dunkler Hintergrund auf Mobile
- **Keyboard**: ESC-Taste schließt Navigation
- **Resize**: Automatisches Schließen bei Desktop-Breakpoint

## 💻 Verwendung

### Header einbinden
```php
// Automatisch in header.php eingebunden
get_template_part('navigation');
```

### Logo ausgeben
```php
// Automatisch im Header
abf_output_logo('desktop', 'logo-desktop');
abf_output_logo('mobile', 'logo-mobile');
```

### Navigation-Menü registrieren
```php
// In WordPress Admin: Erscheinungsbild → Menüs
// Menü-Location: "Hauptnavigation"
```

## 🎨 CSS-Klassen

### Header
- `.site-header`: Haupt-Header-Container
- `.header-container`: Flex-Container für Logo und Burger
- `.header-logo`: Logo-Container
- `.logo-link`: Logo-Link

### Navigation
- `.site-navigation`: Haupt-Navigation-Container
- `.navigation-container`: Innerer Container
- `.navigation-header`: Header mit Titel und Schließen-Button
- `.navigation-content`: Scrollbarer Content-Bereich
- `.navigation-menu`: WordPress-Menü-Liste
- `.navigation-footer`: Footer mit Kontakt

### Burger Menu
- `.burger-menu-toggle`: Burger-Button
- `.burger-line`: Einzelne Linien
- `.active`: Aktiver Zustand (X-Animation)

### Overlay
- `.navigation-overlay`: Dunkler Hintergrund (Mobile)

## 🔧 JavaScript-Funktionen

### Event-Listener
- **Burger Click**: Toggle Navigation
- **Overlay Click**: Schließt Navigation
- **Close Button**: Schließt Navigation
- **ESC Key**: Schließt Navigation
- **Window Resize**: Schließt Navigation bei Desktop

### Funktionen
- `openNavigation()`: Öffnet Navigation
- `closeNavigation()`: Schließt Navigation

## 📱 Breakpoints

### Responsive Verhalten
- **<576px**: Mobile (Burger Menu)
- **576px-767px**: Tablet (Burger Menu)
- **768px-991px**: Tablet (Burger Menu)
- **≥992px**: Desktop (Sidebar Navigation)

### Content-Anpassungen
- **<992px**: Content vollbreite
- **≥992px**: Content versetzt um 300px

## 🎯 Best Practices

### Logo
- Verwende SVG für beste Skalierbarkeit
- Desktop-Logo: 200x60px empfohlen
- Mobile-Logo: 150x45px empfohlen

### Navigation
- Maximal 3-4 Hauptmenüpunkte
- Verwende Untermenüs für komplexe Strukturen
- Halte Menüpunkte kurz und prägnant

### Performance
- Logo-Dateien optimieren
- JavaScript wird nur bei Bedarf geladen
- CSS ist bereits kompiliert

## 🔍 Troubleshooting

### Navigation öffnet sich nicht
1. Prüfe ob JavaScript geladen wird
2. Prüfe Browser-Konsole auf Fehler
3. Prüfe ob Burger-Button existiert

### Layout-Probleme
1. Prüfe CSS-Kompilierung (`npm run build`)
2. Prüfe Breakpoint-Werte
3. Prüfe Logo-Dimensionen

### Menü wird nicht angezeigt
1. Prüfe WordPress-Menü-Einstellungen
2. Prüfe Menü-Location "Hauptnavigation"
3. Fallback-Menü wird automatisch angezeigt

## 📚 Weiterführende Links

- [WordPress Navigation Menus](https://wordpress.org/support/article/navigation-menus/)
- [CSS Flexbox Guide](https://css-tricks.com/snippets/css/a-guide-to-flexbox/)
- [JavaScript Event Handling](https://developer.mozilla.org/en-US/docs/Web/Events) 