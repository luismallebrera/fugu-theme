# Default Fonts Directory

Coloca tus fuentes por defecto en esta carpeta. El tema las cargará automáticamente si no hay fuentes personalizadas configuradas en el Customizer.

## Estructura

Organiza las fuentes en carpetas individuales por familia:

```
assets/fonts/
├── MiFuente/
│   ├── MiFuente-Regular.woff2
│   ├── MiFuente-Regular.woff
│   └── MiFuente-Regular.ttf
├── OtraFuente/
│   ├── OtraFuente-Regular.woff2
│   └── OtraFuente-Regular.woff
```

## Notas

- **Nombre de la carpeta** = Nombre de la fuente en Elementor
- El tema buscará automáticamente archivos `.woff2`, `.woff` y `.ttf`
- Se recomienda incluir al menos `.woff2` (mejor compresión y compatibilidad moderna)
- Las fuentes aparecerán en el grupo "Custom Fonts" de Elementor
- Si subes fuentes desde el Customizer, las fuentes de esta carpeta se desactivarán automáticamente
