Hola DeepSeek. Vamos a crear una nueva funcionalidad para registrar los ingresos de usuarios al sistema.

Archivos donde debes trabajar:

```txt
impulsa_emprende\view\admin\adminIngresosView.php
impulsa_emprende\model\admin\adminIngresosModel.php
impulsa_emprende\controller\admin\adminIngresosController.php
```

Contexto del proyecto:

```txt
estructure\copiaLocalEstructuraBBDD.md
```

Ahí tienes una copia local de la estructura de la base de datos. Léela antes de proponer cambios.

También revisa el CDN local en:

```txt
assets\impulsa_material\index.html
```

La web consume el CDN con estas URLs:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://impulsagroup.com/assets/impulsa_material/css/material.css">
<script src="https://impulsagroup.com/assets/impulsa_material/js/material.js" defer></script>
<script src="https://impulsagroup.com/assets/impulsa_material/js/material-validaciones.js" defer></script>
```

Objetivo:

Necesito llevar un control de los usuarios que ingresan al sistema.

Se debe registrar:

* Nombre del usuario
* Rol
* Fecha de ingreso
* Hora de ingreso

Tareas:

1. Crear una tabla nueva en la base de datos para guardar estos ingresos.
2. Darme el código SQL necesario para crear esa tabla.
3. Implementar la lógica en el modelo, controlador y vista usando los archivos indicados.
4. En `adminIngresosView.php`, mostrar una tabla con el listado de ingresos registrados.
5. Agregar el acceso a esta nueva vista en:

```txt
impulsa_emprende\view\admin\adminMenu.php
```

para que esté disponible desde el menú del administrador.

Para mantener consistencia visual y estructural, revisa estas vistas como referencia:

```txt
impulsa_emprende\view\admin\adminListUserView.php
impulsa_emprende\view\admin\adminMarketingView.php
```

Importante:

* Respeta la arquitectura existente del proyecto.
* Usa las clases del CDN ya implementado.
* No rompas funcionalidades existentes.
* No dupliques lógica innecesaria.
* El registro del ingreso debe realizarse cuando el usuario inicia sesión correctamente.
* Si necesitas modificar el login o algún controlador existente para insertar el registro, indícame exactamente qué archivo modificar y qué código agregar.
* Devuélveme los cambios por archivo, de forma ordenada.
