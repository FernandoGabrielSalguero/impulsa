# Impulsa Material CDN

Este archivo es la fuente de verdad para cargar `impulsa_material` en nuevas vistas de `impulsa_emprende`.

## Regla principal

`impulsa_material` se consume desde la web, no desde rutas locales del proyecto.

No usar:

```html
<link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
<script src="../../../assets/impulsa_material/js/material.js"></script>
<script src="../../../assets/impulsa_material/js/material-validaciones.js"></script>
```

No usar tampoco:

```html
<link rel="stylesheet" href="/assets/impulsa_material/css/material.css">
```

## Cómo cargarlo correctamente

Las vistas deben usar los helpers definidos en [config.php](../../config.php):

- `renderImpulsaMaterialFonts()`
- `obtenerImpulsaMaterialCssHref()`
- `obtenerImpulsaMaterialJsSrc()`
- `obtenerImpulsaMaterialValidacionesJsSrc()`

## Ejemplo base para una vista nueva

```php
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= renderImpulsaMaterialFonts() ?>
  <link rel="stylesheet" href="<?= htmlspecialchars(obtenerImpulsaMaterialCssHref(), ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body>
  ...

  <script src="<?= htmlspecialchars(obtenerImpulsaMaterialJsSrc(), ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
```

## Cuándo incluir `material-validaciones.js`

Incluirlo solo si la vista tiene formularios que usan las validaciones de `impulsa_material`.

Ejemplo:

```php
<script src="<?= htmlspecialchars(obtenerImpulsaMaterialValidacionesJsSrc(), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
```

## Cuándo usar `defer`

- `material-validaciones.js`: usar `defer`.
- `material.js`: mantener sin `defer` si la vista tiene scripts inline o dependencias inmediatamente después que esperan que `material.js` ya esté disponible.
- Si la vista fue diseñada para trabajar con carga diferida y no depende del orden inmediato, se puede evaluar usar `defer`, pero la regla actual del proyecto es conservar el comportamiento existente.

## Qué URLs reales terminan cargándose

Por defecto los helpers apuntan a:

- `https://impulsagroup.com/assets/impulsa_material/css/material.css`
- `https://impulsagroup.com/assets/impulsa_material/js/material.js`
- `https://impulsagroup.com/assets/impulsa_material/js/material-validaciones.js`

El dominio base sale de `config.php` y puede cambiarse por variable de entorno sin tocar todas las vistas.

## Checklist para nuevas páginas

1. No hardcodear rutas locales de `assets/impulsa_material`.
2. Usar los helpers de `config.php`.
3. Incluir fuentes con `renderImpulsaMaterialFonts()` si la vista usa `Inter` o `Material Symbols Rounded`.
4. Incluir `material-validaciones.js` solo si hay formularios que lo necesiten.
5. Verificar en DevTools > Network que las requests salgan a `https://impulsagroup.com/...`.

## Verificación rápida en navegador

```js
[...document.querySelectorAll('link[rel="stylesheet"], script[src]')]
  .map(el => el.href || el.src)
  .filter(url => url.includes('impulsa_material'))
```

El resultado esperado debe mostrar URLs de `https://impulsagroup.com/...` y no rutas locales.
