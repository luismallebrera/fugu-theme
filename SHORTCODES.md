# Shortcodes del Tema


## Shortcodes de GDR
Muestra las siglas del GDR actual.
- **Uso:** `[galgdr_siglas]`
- **Contexto:** Dentro de una página de GDR

### `[gdr_municipios]`
- **Uso:** `[gdr_municipios]` o `[gdr_municipios id="123" title="Municipios asociados" link="no"]`
  - `title` (opcional): Título opcional que se mostrará encima de la lista. Si se omite, se genera “Municipios asociados a {nombre}”.
  - `link` (opcional): Controla si cada municipio se muestra como enlace. Valores: `yes` (default) o `no`.
- **Retorna:** Lista `<ul>` de municipios publicados con enlaces a cada ficha.

### `[galgdr_municipios_name]`
Versión textual del shortcode anterior, pensada para usar dentro de frases o widgets sencillos.
- **Parámetros:**
  - `id` (opcional): ID del GDR. Por defecto usa el post actual si es de tipo GDR.
  - `separator` (opcional): Cadena usada entre cada municipio. Por defecto `<br>`. Admite valores especiales `vertical` (usa ` | `) y `horizontal` (usa ` / `).
  - `item_class` (opcional): Clase CSS para los `<span>` de los municipios cuando `format="spans"`. Default `soda-entry-list__item`.
  - `separator_class` (opcional): Clase CSS para los `<span>` separadores cuando `format="spans"`. Default `soda-entry-list__separator`.
- **Retorna:** Lista de nombres de municipios unidos por el separador definido.
---
Muestra el nombre del GDR asociado a un municipio.
- **Uso:** `[municipio_galgdr_name]` o `[municipio_galgdr_name id="123"]`
- **Retorna:** Nombre del GDR asociado

### `[municipio_provincia_name]`
Muestra el nombre de la provincia asociada a un municipio.
- **Uso:** `[municipio_provincia_name]` o `[municipio_provincia_name id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del municipio. Por defecto usa el municipio actual o `?municipio_id=X` de la URL
- **Retorna:** Nombre de la provincia
### `[municipio_search]`
Widget de búsqueda de municipios con dos pasos: seleccionar provincia y luego municipio.
- **Uso:** `[municipio_search]`
  - Select2 para mejorar la experiencia de usuario
  - Filtrado AJAX de municipios por provincia
  - Abre popup con información del municipio seleccionado

---

## Shortcodes de Proyectos

### `[proyectos_gdr_link]`
Muestra el nombre del GDR asociado con enlace a su página.
- **Uso:** `[proyectos_gdr_link]` o `[proyectos_gdr_link id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del proyecto. Por defecto usa el proyecto actual
- **Retorna:** Enlace HTML con el nombre del GDR (ej: `<a href="...">Asociación para el Desarrollo...</a>`)

### `[proyectos_gdr_siglas_link]`
Muestra las siglas del GDR asociado con enlace a su página.
- **Uso:** `[proyectos_gdr_siglas_link]` o `[proyectos_gdr_siglas_link id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del proyecto. Por defecto usa el proyecto actual
- **Retorna:** Enlace HTML con las siglas del GDR (ej: `<a href="...">ADIMAN</a>`)
- **Nota:** Las siglas se obtienen automáticamente del GDR asociado al proyecto

### `[proyectos_provincia]`
Muestra el nombre de la provincia del proyecto.
- **Uso:** `[proyectos_provincia]` o `[proyectos_provincia id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del proyecto. Por defecto usa el proyecto actual
- **Retorna:** Nombre de la provincia (ej: "TOLEDO")

### `[proyectos_municipio]`
Muestra el nombre del municipio del proyecto.
- **Uso:** `[proyectos_municipio]` o `[proyectos_municipio id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del proyecto. Por defecto usa el proyecto actual
- **Retorna:** Nombre del municipio

---

## Shortcodes de Portfolio

### `[portfolio_title_color]`
Retorna el valor del color del título (light/dark).
- **Uso:** `[portfolio_title_color]` o `[portfolio_title_color id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del portfolio. Por defecto usa el post actual
- **Retorna:** "light" o "dark"

### `[portfolio_title_color_class]`
Retorna una clase CSS basada en el color del título.
- **Uso:** `[portfolio_title_color_class]` o `[portfolio_title_color_class id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del portfolio. Por defecto usa el post actual
- **Retorna:** "light-title" o "dark-title"

### `[portfolio_title_color_hex]`
Retorna el código hexadecimal del color del título.
- **Uso:** `[portfolio_title_color_hex]` o `[portfolio_title_color_hex id="123"]`
- **Parámetros:**
  - `id` (opcional): ID del portfolio. Por defecto usa el post actual
- **Retorna:** "#ffffff" (para light) o "#000000" (para dark)

### `[if_title_color]`
Shortcode condicional que muestra contenido según el color del título.
- **Uso:** `[if_title_color color="light" id="123"]Contenido si es light[/if_title_color]`
- **Parámetros:**
  - `color` (requerido): "light" o "dark"
  - `id` (opcional): ID del portfolio. Por defecto usa el post actual
- **Retorna:** El contenido encerrado si coincide con el color configurado

---

## Shortcodes Generales de Conversión

### `[post_title]`
Convierte un ID de post a su título.
- **Uso:** `[post_title id="123"]`
- **Parámetros:**
  - `id` (requerido): ID del post
- **Retorna:** Título del post
- **Útil en:** Dynamic Tags de Elementor para convertir IDs a nombres

### `[term_name]`
Convierte un ID de término de taxonomía a su nombre.
- **Uso:** `[term_name id="123" taxonomy="provincia"]`
- **Parámetros:**
  - `id` (requerido): ID del término
  - `taxonomy` (opcional): Nombre de la taxonomía. Por defecto "provincia"
- **Retorna:** Nombre del término
- **Útil en:** Dynamic Tags de Elementor para convertir IDs de taxonomías a nombres

---

## Shortcodes de Utilidades

### `[image_pan_zoom]`
Crea un visor de imagen con pan y zoom.
- **Uso:** `[image_pan_zoom image="URL" zoom_min="1" zoom_max="3"]`
- **Parámetros:**
  - `image` (requerido): URL de la imagen
  - `zoom_min` (opcional): Zoom mínimo. Por defecto: 1
  - `zoom_max` (opcional): Zoom máximo. Por defecto: 3
  - `zoom_step` (opcional): Incremento de zoom. Por defecto: 0.25
  - `mouse_wheel` (opcional): Habilitar zoom con rueda del ratón. Por defecto: "true"
  - `drag` (opcional): Habilitar arrastre. Por defecto: "true"
  - `controls` (opcional): Mostrar botones de control. Por defecto: "true"
  - `controls_position` (opcional): Posición de controles. Valores: "top-right", "top-left", "top-center", "bottom-right", "bottom-left", "bottom-center". Por defecto: "top-right"
- **Características:**
  - Límites de arrastre para mantener imagen visible
  - Centrado automático de imágenes más pequeñas que el contenedor
  - Soporte táctil para móviles
- **Ejemplo:** `[image_pan_zoom image="https://ejemplo.com/imagen.jpg" zoom_max="5" controls_position="bottom-center"]`

---

## Meta Keys de Custom Fields

### Proyectos
- `_proyectos_provincia` - ID del término de taxonomía provincia (integer)
- `_proyectos_municipio` - ID del post de municipio (integer)
- `_proyectos_ayuda` - Texto de ayuda (string)
- `_proyectos_gdr` - ID del post de GDR (integer)
- `_proyectos_gdr_siglas` - Siglas del GDR (string, auto-sincronizado desde el GDR asociado)

### GDR
- `_galgdr_siglas` - Siglas del GDR (string)

### Municipio
- `_municipio_galgdr_asociado` - ID del GDR asociado (integer)
- `_municipio_provincia` - ID de la provincia asociada (integer)

### Portfolio
- `_portfolio_description` - Descripción HTML del portfolio (string)
- `_portfolio_large_image` - ID de imagen grande (integer)
- `_portfolio_medium_image` - ID de imagen mediana (integer)
- `_portfolio_small_image` - ID de imagen pequeña (integer)
- `_portfolio_title_color` - Color del título: "light" o "dark" (string)
- `_portfolio_button_text` - Texto del botón (string)
- `_portfolio_button_link` - URL del botón (string)

---

## Notas de Uso

1. **Shortcodes con parámetro `id`**: Si no se proporciona el parámetro `id`, la mayoría de shortcodes usan el ID del post actual (función `get_the_ID()`).

2. **Shortcodes en Elementor**: Se recomienda usar el widget "Shortcode" de Elementor para insertar estos códigos.

3. **Dynamic Tags**: Los shortcodes `[post_title]` y `[term_name]` están diseñados específicamente para usarse con Dynamic Tags de Elementor, usando el método "Before/After" para convertir IDs a nombres legibles.

4. **Popup de Municipios**: El sistema de popup se activa automáticamente cuando hay un parámetro `?municipio_id=X` en la URL.

5. **Select2**: El widget de búsqueda de municipios requiere Select2, que ya está encolado en el tema (CDN versión 4.1.0).
