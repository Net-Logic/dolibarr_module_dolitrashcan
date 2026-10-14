---
title: "Papelera para Dolibarr - recupere los archivos eliminados por error"
short: "Una papelera para Dolibarr: los archivos eliminados en cualquier zona de documentos se conservan y pueden restaurarse."
start: "El módulo aparece como **DoliTrashCan**; el plazo de conservación se ajusta en su página de configuración."
---
**Papelera para Dolibarr** evita perder documentos. Cada archivo eliminado de una zona de documentos (terceros, presupuestos, pedidos, facturas, proyectos...) se mueve a una papelera en lugar de desaparecer, con quién lo eliminó, cuándo y desde qué ficha.

## Funcionalidades

- **Automático**: nada que configurar en los objetos, los botones de eliminar habituales envían el archivo a la papelera.
- **Página Papelera** en el menú GED: archivos eliminados con su tipo, el usuario, la fecha y un enlace a la ficha de origen.
- **Restauración** de un archivo a su ubicación original con un clic (el índice GED se actualiza). Un archivo que vuelve a existir nunca se sobrescribe.
- **Eliminación definitiva** con confirmación, según los permisos del usuario (lectura, restauración, eliminación).
- **Purga automática**: los archivos más antiguos que el número de días elegido (365 por defecto, 0 para conservarlos siempre) se eliminan definitivamente.
- **Solo documentos reales**: los archivos temporales y las miniaturas no van a la papelera.
- **Seguro**: si un archivo no se puede mover a la papelera, no se elimina.
