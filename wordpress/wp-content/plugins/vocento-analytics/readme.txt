=== Vocento Analytics ===
Contributors: @davidnaviaweb
Tags: Vocento,Analytics
Requires at least: 4.0
Tested up to: 4.5
Stable tag: trunk

Generador de Analytics para Vocento

== Description ==
El plugin permite configurar analítica a nivel de red o de site de varias redes.

== Installation ==
Tras activar el plugin aparecerá dentro del menú ajustes (en el site) la opción de \"Vocento Analytics\".
En la red aparecerá en el raíz del menú la opción \"Vocento Analytics\".

Para el correcto funcionamiento del plugin es necesario incluir los siguientes actions en su theme:
Justo despues de la etiqueta body pegue la siguiente linea:
<?php  do_action('vocento_analytics_after_body_start');?>

De manera que quede:

<body>
<?php  do_action('vocento_analytics_after_body_start');?>

Justo antes del cierre de la etiqueta body pegue la siguiente linea:
<?php  do_action('vocento_analytics_before_body_end');?>

De manera que quede:

<?php  do_action('vocento_analytics_before_body_end');?>
</body>

== Changelog ==
= 1.0 =
* Versión inicial

== Changelog ==
= 1.1 =
* Ampliar campos de metabox
* voc_ho tipo "Ninguno"
* Inspectlet

= 1.2 =
* Añadida red KRUX
* Añadida herramienta de importación/exportación de configuraciones

= 1.3 =
* Corrección de bugs menores
* Añadidos parámetros C1 y C2 para VocentoWP_Analytics.utils.dynamic.refreshTracking()