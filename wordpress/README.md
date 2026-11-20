# 🚀 Plantilla Meytel para Migración WordPress y el Chat de VS Code

Esta carpeta contiene la configuración para incorporar a un proyecto de migración WordPress. La guía operativa detallada de la migración a PHP 8.4 se entrega junto con esta plantilla (referencia: `la guía operativa incluida en el kit`).

## Una base que cada proyecto adapta
La plantilla ofrece una configuración general para las tareas de desarrollo de migración. Cada proyecto puede adaptar el orquestador y los subagentes, añadir o eliminar perfiles, y ajustar sus modelos, instrucciones, herramientas, permisos y comandos según sus necesidades. Por ejemplo, el equipo puede elegir otro modelo de IA para el orquestador si obtiene mejores resultados en la detección de incompatibilidades de PHP 8.4.

Una vez acordada, la configuración se versiona en el repositorio del proyecto junto con el código y la documentación. Así, todos los participantes reciben y comparten la misma configuración de referencia al obtener o actualizar el repositorio. Las credenciales (acceso a Staging/Producción) se configuran de forma segura por cada persona y **nunca** se suben al repositorio.

## 📦 Contenido

- `opencode.jsonc`: agente y modelo predeterminados, permisos del proyecto y política del proveedor Zen.
- `AGENTS.md`: instrucciones compartidas para la gestión y trabajo en el repositorio de migración.
- `.opencode/agents/`: **Siete agentes especializados** con prefijo `meytel-` (ej. `meytel-planner`, `meytel-auditor`).
- `.opencode/commands/`: `/plan-migration`, `/audit-plugins`, `/verify-staging`.
- `.github/agents/`: Siete perfiles equivalentes para el chat de VS Code; su uso es opcional.

> 💡 **Nota Crucial:** Los archivos `.opencode/agents/*.md` definen el comportamiento, el modelo y los permisos de cada agente, enfocados en la compatibilidad de PHP 8.4 y la revisión de `wp-content`.

## ➡️ Instalar y Usar

**En un repositorio nuevo de migración:**
Copia a la raíz `opencode.jsonc`, `AGENTS.md`, `.opencode/` y `.github/`. Así quedan disponibles las dos integraciones en el mismo proyecto. **No copies la carpeta exterior ni este README al README del producto.**

**En un repositorio existente:**
Combina `opencode.jsonc` y `AGENTS.md` con la configuración e instrucciones que ya existen, y añade el contenido de `.opencode/` y `.github/` sin sobrescribir ficheros ajenos.

**Proceso:**
1. Conectar el entorno corporativo con `/connect`.
2. Confirmar modelos con `/models`.
3. Abrir una sesión nueva desde la raíz del proyecto. **No almacenes credenciales** de acceso a Staging o Producción en este repositorio.

## ✨ Subagentes sugeridos para la Migración
Hemos diseñado agentes especializados para las fases de la migración:

| Agente | Función Principal | Objetivo Específico |
| :--- | :--- | :--- |
| **`meytel-planner`** | Planificador de la migración. | Generar cronogramas, definir hitos y crear checklists basados en el estado actual del proyecto. |
| **`meytel-repo-explorer`** | Explorador del repositorio (WordPress). | Auditar la estructura de `wp-content` (temas y plugins), identificar dependencias críticas y mapear archivos. |
| **`meytel-auditor-php`** | Auditor de compatibilidad PHP 8.4. | Analizar código en `wp-content` para detectar sintaxis, funciones obsoletas o patrones de código que fallen en PHP 8.4. |
| **`meytel-tester`** | Ejecutor de pruebas funcionales. | Guiar la creación de casos de prueba (ej. "Prueba Checkout", "Prueba Formulario") en el entorno Staging. |
| **`meytel-security`** | Auditor de seguridad. | Revisar configuraciones de `wp-config.php` y detectar vulnerabilidades comunes antes del despliegue. |
| **`meytel-documenter`** | Documentador de cambios. | Registrar todos los cambios realizados (actualizaciones de plugins, correcciones de código) en un log de migración. |
| **`meytel-orchestrator`** | Coordinador principal. | Gestionar el flujo de trabajo entre los subagentes (ej. **Planner** -> **Explorer** -> **Auditor** -> **Tester**). |

## 🖥️ Opción de chat visual de VS Code
La carpeta oculta `.github/agents/` contiene la variante genérica de los siete perfiles Meytel para el chat de VS Code. Su uso es opcional. Abre el proyecto en VS Code, configura el proveedor Zen corporativo, inicia una sesión compatible y selecciona `meytel-orchestrator`. Los perfiles especialistas están listos para ser invocados como subagentes. **Cada proyecto puede añadir o eliminar subagentes y ajustar instrucciones** según las necesidades específicas de su WordPress.

## ⚙️ Comandos de ejemplo (Atajos)
Los comandos `/plan-migration`, `/audit-plugins` y `/verify-staging` son atajos opcionales para OpenCode.
*   `/plan-migration`: Inicia el proceso de planificación de la migración.
*   `/audit-plugins`: Llama a `meytel-auditor-php` y `meytel-repo-explorer` para escanear `wp-content` en busca de incompatibilidades con PHP 8.4.
*   `/verify-staging`: Ejecuta una serie de pruebas funcionales en el entorno de Staging antes de proceder a Producción.

Estos comandos ahorran instrucciones repetidas y favorecen un flujo de trabajo estándar, pero no garantizan un ahorro de tokens. Son adaptables: cada proyecto puede modificarlos según su metodología de trabajo.

---
