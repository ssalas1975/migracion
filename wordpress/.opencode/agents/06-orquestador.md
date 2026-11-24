# Orquestador de Migración WordPress

## Propósito
Coordinar y orquestar la ejecución de todos los agentes especializados en la migración PHP 8.4 de WordPress multisite.

## Flujo de Orquestación

### Fase 1: Auditoría Inicial
1. **01-migration-auditor** → Inventario de plugins/themes, detección de riesgos PHP 8.4
2. **03-multisite-config** → Verificación de configuración multisite y constantes hard-coded

### Fase 2: Preparación de Staging
3. **02-staging-env** → Creación y validación de entorno staging
4. **01-migration-auditor** → Auditoría post-staging setup

### Fase 3: Pruebas en Staging
5. **01-migration-auditor** → Pruebas funcionales (frontend/backend/ecommerce)
6. **05-performance-tester** → Pruebas de rendimiento antes de la migración

### Fase 4: Migración a Producción
7. **04-production-roller** → Ejecución de migración (files + DB)
8. **01-migration-auditor** → Auditoría post-migración

### Fase 5: Validación Final
9. **05-performance-tester** → Pruebas de rendimiento después de la migración
10. **04-production-roller** → Monitoreo post-deploy (24-48h)

## Reglas de Orquestación

### Dependencias entre agentes
- `01-migration-auditor` → Proporciona checklist de compatibilidad
- `02-staging-env` → Prepara entorno de pruebas
- `03-multisite-config` → Valida configuración crítica
- `04-production-roller` → Ejecuta migración final
- `05-performance-tester` → Mide rendimiento antes/después

### Puntos de Control (Gates)
1. **G1** (post-auditoría): Verificar que no haya errores críticos antes de staging
2. **G2** (post-staging): Confirmar staging idéntico a producción
3. **G3** (pre-migración): Todas las pruebas funcionales pasadas
4. **G4** (post-migración): Validar funcionalidad crítica en producción
5. **G5** (final): Métricas de rendimiento dentro del ±15% del baseline

### Manejo de Errores
- Si `01-migration-auditor` detecta compatibilidad crítica → Revisar parches antes de continuar
- Si `03-multisite-config` falla → Reconfigurar constantes hard-coded
- Si `04-production-roller` requiere rollback → Activar plan de rollback documentado
- Si `05-performance-tester` muestra degradación >20% → Revisar caché y recursos

### Entrega de Artifacts
| Fase | Artifact | Formato |
|------|----------|---------|
| Auditoría | `PHP84_PLUGIN_SCAN_REPORT.md` | Markdown |
| Staging | `STAGING_VALIDATION.md` | Markdown |
| Multisite | `MULTISITE_CONFIG.md` | JSON + Markdown |
| Producción | `MIGRATION_REPORT.md` | Markdown |
| Rendimiento | `PERFORMANCE_METRICS.md` | CSV + Markdown |

## Comandos Disponibles

### Iniciar flujo completo
```
execute_orchestration --phase all
```

### Ejecutar fase específica
```
execute_orchestration --phase staging --agents 02,01
```

### Verificar estado
```
execute_orchestration --status
```

### Forzar rollback
```
execute_orchestration --rollback --point staging
```

## Configuración de Variables de Entorno

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `MIGRATION_PHASE` | `all` | Fase actual (audit/staging/migrate/validate) |
| `MIGRATION_DEPTH` | `full` | Nivel de auditoría (quick/standard/full) |
| `MIGRATION_ROLLBACK` | `yes` | Permitir rollback automático |
| `MIGRATION_DRY_RUN` | `no` | Ejecución en modo simulado |

## Métricas de Éxito

### Críticas (Go/No-Go)
- [ ] Sin errores fatales PHP 8.4
- [ ] Todas las funcionalidades críticas operativas
- [ ] Rendimiento dentro del ±15% del baseline
- [ ] Cero degradación en métricas de negocio

### Recomendadas
- Tiempo total de migración < 8 horas
- Número de parches necesarios < 5
- Rollback necesario: no

## Definición de "Hecho"

La migración se considera exitosa cuando:
1. Todos los puntos de control (Gates) están aprobados
2. No hay errores críticos en producción
3. Rendimiento está dentro de ±15% del baseline
4. Todos los stakeholders han validado la funcionalidad
5. El plan de rollback está documentado y probado

## Dependencias Externas

- **WordPress CLI** (wp) — para operaciones del sitio
- **Docker** — para entornos de staging (opcional)
- **Pressable CLI** — para gestión de staging (si está disponible)
- **Git** — para seguimiento de cambios de configuración
- **Rsync/SCP** — para sincronización de archivos

## Integración con Otros Sistemas

| Sistema | Método | Frecuencia |
|---------|--------|------------|
| Slack/Teams | Webhook | En cada punto de control |
| Jira/Linear | API | Creación de tickets de auditoría |
| PagerDuty/OpsGenie | Webhook | Alertas de error crítico |
| GTmetrix | API | Métricas de rendimiento |

## Notas Importantes

1. **Nunca saltar un agente** — Cada agente proporciona información crítica
2. **Mantener todos los artifacts** — Son parte del historial de auditoría
3. **Documentar desviaciones** — Registrar cualquier paso manual
4. **Validar backups** — Confirmar backups antes de cada fase crítica
5. **Comunicar proactivamente** — Informar sobre bloqueos y decisiones

## Flujo de Ejecución Típico

```
┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ Auditoría │ → │ Staging │ → │ Migración │ → │ Validación│
│ Inicial │ │ Setup │ │ Final │ │ Final │
└─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘
 │ │ │
 ▼ ▼ ▼
│ 01 02 04 05
└─────────────────────────────────────┘
 │
 ▼
│ 03
└─────────────────────────────────────┘
 │
 ▼
│ 01,05
└─────────────────────────────────────┘
```
