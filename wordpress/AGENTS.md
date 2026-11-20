# Meytel Migration Agent Framework

## Core Directories
- **`.opencode/agents/`** — 7 agent definition files (see below)
- **`.opencode/commands/`** — 3 command shortcut definitions (see below)

## Seven Built-in Agents
| Agent | Purpose | Key Focus |
| :--- | :--- | :--- |
| `meytel-planner` | Migration scheduling, milestones, checklists | Cronogramas y hitos |
| `meytel-repo-explorer` | Audit `wp-content` structure, dependencies, file mapping | Plugins y temas en `wp-content` |
| `meytel-auditor-php` | PHP 8.4 compatibility: syntax, deprecated functions, patterns | Código en `wp-content` |
| `meytel-tester` | Functional test cases (Checkout, Forms) | Entorno Staging |
| `meytel-security` | `wp-config.php` review, vulnerability detection | Configuración de seguridad |
| `meytel-documenter` | Migration change log | Registro de cambios |
| `meytel-orchestrator` | Workflow coordination between agents | Orquestación general |

## Core Commands
- `/plan-migration` — Start migration planning process
- `/audit-plugins` — Scan `wp-content` for PHP 8.4 incompatibilities
- `/verify-staging` — Run functional tests on Staging before Production

## Important Constraints
- **Credential policy**: Store connection setup in `.opencode/agents/`, keep staging/production credentials out of the repo
- **File structure**: Each plugin/theme is a separate package boundary; audit them individually
- **PHP 8.4 migration**: This is the primary compatibility target; all agents focus on this
