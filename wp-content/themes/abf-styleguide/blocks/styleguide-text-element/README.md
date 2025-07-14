# Styleguide-Textelement Block

Ein konfigurierbares Textelement mit maximaler Breite von 840px für das ABF Styleguide.

## Funktionen

### Inhalt
- **Headline** (optional): Hauptüberschrift mit konfigurierbarem HTML-Tag, Schriftgröße, Schriftgewicht und Farbe
- **Text** (optional): Haupttext (Richtext-Editor) mit konfigurierbarer Schriftgröße, Schriftgewicht und Farbe
- **Button** (optional): Call-to-Action Button mit ausschließlich ACF-konfigurierten Farben, Standard- und Hover-Effekten

### Design
- **Maximale Breite**: 840px (basierend auf `$container-content` in `variables.scss`)
- **Responsive Spacing**: Automatische Anpassung der Abstände basierend auf den Breakpoints in `variables.scss`
- **Bedingte Anzeige**: Elemente werden nur angezeigt, wenn sie Inhalt haben (kein unnötiges Spacing)
- **Dynamische Farben**: Unterstützung aller Farben aus der `colors.json` über Theme-Einstellungen

### Technische Details

#### Spacing-System
- **Mobile (≤576px)**: 12px Abstand (`$spacing-xs`)
- **Tablet (576px-768px)**: 16px Abstand (`$spacing-sm`)
- **Desktop Small (768px-992px)**: 16px Abstand (`$spacing-sm`) - reduziert
- **Desktop (992px-1200px)**: 16px Abstand (`$spacing-sm`) - reduziert
- **Large Desktop (≥1201px)**: 16px Abstand (`$spacing-sm`) - reduziert

#### Schriftgrößen
Verfügbare Schriftgrößen basierend auf `variables.scss`:
- 12px (Small)
- 18px (Body)
- 24px (H2)
- 36px (H1)
- 48px (XL)
- 60px (XXL)
- 72px (3XL)

#### Schriftgewichte
- Light (300)
- Regular (400)
- Bold (700)

#### HTML-Tags
**Headline**: h1, h2, h3, h4, h5, h6, p
**Text**: Richtext-Editor (kein HTML-Tag wählbar, da WYSIWYG-Editor)

#### Farben
- Alle Farben aus der `colors.json` (dynamisch über Theme-Einstellungen)
- Standard-Farben: primary, secondary, white, black, inherit
- Button: Separate Standard- und Hover-Farben für Hintergrund und Text
- **Button-Design**: Ausschließlich ACF-konfigurierte Farben, kein Fallback auf Standard-Varianten

## Verwendung

1. Block zum Gutenberg-Editor hinzufügen
2. Inhalt für Headline, Text und/oder Button eingeben
3. Design-Einstellungen für jedes Element konfigurieren
4. Button-URL kann normale URL oder Modal-Trigger sein:
   - `#register-modal`
   - `#login-modal`
   - `#modal`

## Dateien

- `block.json`: Block-Konfiguration
- `template.php`: PHP-Template für Frontend-Ausgabe
- `style.scss`: SCSS-Styling mit responsive Design
- `README.md`: Diese Dokumentation

## Browser-Support

- Moderne Browser mit CSS `:has()` Support für optimale Spacing-Logik
- Fallback-Styling für ältere Browser ohne `:has()` Support 