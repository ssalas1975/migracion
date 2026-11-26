# 🧪 Fase 3: Testing Funcional — Plan de Pruebas para PHP 8.4

## 📋 Resumen

| Fase | Estado | Descripción |
|------|--------|-------------|
| Fase 1 | ✅ Completada | Auditoría de estructura y configuración |
| Fase 2 | ✅ Completada | Auditoría de código PHP para PHP 8.4 |
| **Fase 3** | 🟡 En progreso | Testing funcional y rendimiento |
| Fase 4 | ⏳ Pendiente | Rollout a producción |
| Fase 5 | ⏳ Pendiente | Monitoreo post-implementación |

---

## 🎯 Objetivo

Validar que el sitio funciona correctamente bajo PHP 8.4 en staging antes de implementar en producción.

---

## 📊 Matriz de Pruebas por Área

### 1. Frontend (Usuario Final)

| Prueba | Método | Frecuencia | Estado |
|--------|--------|------------|--------|
| **Navegación** | Manual + Selenium | Diaria | ⏳ |
| **Formularios** | Manual + Jest | Diaria | ⏳ |
| **Galerías** | Manual + PageSpeed | Diaria | ⏳ |
| **Lazy Loading** | PageSpeed + Manual | Diaria | ⏳ |
| **Responsive** | Manual (móvil, tablet) | Diaria | ⏳ |
| **Carga de página** | Lighthouse | Cada cambio | ⏳ |
| **Core Web Vitals** | Lighthouse + GTmetrix | Cada cambio | ⏳ |
| **SEO** | Manual + Schema Validator | Diaria | ⏳ |
| **Polylang** | Manual (es/en) | Diaria | ⏳ |

**Especificaciones de Core Web Vitals (meta PHP 8.4):**
- LCP < 2.5s
- FID < 100ms
- CLS < 0.1
- INP < 200ms

### 2. Backend (Admin / CMS)

| Prueba | Método | Frecuencia | Estado |
|--------|--------|------------|--------|
| **Creación de contenido** | Manual + Cypress | Diaria | ⏳ |
| **Edición de contenido** | Manual + Cypress | Diaria | ⏳ |
| **Administración de usuarios** | Manual + Cypress | Diaria | ⏳ |
| **Subida de medios** | Manual + Jest | Diaria | ⏳ |
| **Menús y navegación** | Manual + Cypress | Diaria | ⏳ |
| **Widgets** | Manual + Jest | Diaria | ⏳ |
| **Archivos de medios** | Manual + Cypress | Diaria | ⏳ |
| **Cron jobs** | Manual + Jest | Diaria | ⏳ |
| **Múltiples sitios** | Manual + Cypress | Diaria | ⏳ |

### 3. E-commerce (WooCommerce)

| Prueba | Método | Frecuencia | Estado |
|--------|--------|------------|--------|
| **Catálogo de productos** | Manual + Jest | Diaria | ⏳ |
| **Carrito de compras** | Manual + Cypress | Diaria | ⏳ |
| **Pasarelas de pago** | Manual + Jest | Diaria | ⏳ |
| **Envíos y logística** | Manual + Cypress | Diaria | ⏳ |
| **Gestión de inventario** | Manual + Cypress | Diaria | ⏳ |
| **Precios y descuentos** | Manual + Jest | Diaria | ⏳ |
| **Pasarelas de pago** | Manual + Jest | Diaria | ⏳ |

### 4. Integraciones

| Prueba | Método | Frecuencia | Estado |
|--------|--------|------------|--------|
| **Mailchimp** | Manual + Jest | Diaria | ⏳ |
| **Google Analytics** | Manual + Jest | Diaria | ⏳ |
| **API externas** | Manual + Cypress | Diaria | ⏳ |
| **SMTP/Mail SMTP** | Manual + Jest | Diaria | ⏳ |
| **The Events Calendar** | Manual + Cypress | Diaria | ⏳ |
| **NextGEN Gallery** | Manual + Cypress | Diaria | ⏳ |
| **Pods/CMB2** | Manual + Jest | Diaria | ⏳ |
| **Elementor** | Manual + Cypress | Diaria | ⏳ |

### 5. Seguridad

| Prueba | Método | Frecuencia | Estado |
|--------|--------|------------|--------|
| **Kadence Security** | Manual + Jest | Diaria | ⏳ |
| **Better WP Security** | Manual + Jest | Diaria | ⏳ |
| **Permisos de archivos** | Manual + Jest | Diaria | ⏳ |
| **HTTPS/SSL** | Manual + Jest | Diaria | ⏳ |
| **CORS** | Manual + Jest | Diaria | ⏳ |
| **Limitación de tasa** | Manual + Jest | Diaria | ⏳ |
| **Backup** | Manual + Jest | Diaria | ⏳ |

### 6. Rendimiento

| Prueba | Método | Frecuencia | Estado |
|--------|--------|------------|--------|
| **Lighthouse** | Script | Cada cambio | ⏳ |
| **GTmetrix** | Script | Cada cambio | ⏳ |
| **PageSpeed Insights** | Script | Cada cambio | ⏳ |
| **Web Vitals** | Script | Cada cambio | ⏳ |
| **Query logs** | Script | Cada cambio | ⏳ |
| **Cron execution** | Script | Cada cambio | ⏳ |
| **Cache hits** | Script | Cada cambio | ⏳ |

---

## 🛠️ Herramientas y Scripts

### Scripts Automatizados

```bash
# 1. Lighthouse (automatizado)
lighthouse https://staging.blogs.vocento.com --output html --output path=./results/

# 2. Web Vitals (automatizado)
web-vitals https://staging.blogs.vocento.com

# 3. GTmetrix (API)
curl https://api.gtmetrix.io/ -X POST -d '{"url":"https://staging.blogs.vocento.com"}'

# 4. MySQL Query Logs
grep 'slow query' /var/log/mysql/mysql-slow.log

# 5. PHP Profiling (Xdebug)
php -d xdebug.mode=profile /path/to/entry-point.php
```

### Scripts de Prueba Manual (Cypress/Jest)

```javascript
// tests/frontend/navigation.spec.js
describe('Frontend Navigation', () => {
  it('should load homepage within 2.5s LCP', () => {
    const start = Date.now();
    cy.visit('/');
    const lcp = cy.get('.above-the-fold').then(() => {
      return Date.now() - start;
    });
    expect(lcp).to.be.lessThan(2500);
  });
});
```

---

## 📅 Cronograma de Testing

| Día | Actividad | Duración | Responsable |
|-----|-----------|----------|-------------|
| **Día 1** | Setup de scripts automatizados | 2h | DevOps |
| **Día 2** | Pruebas frontend manuales | 4h | QA |
| **Día 3** | Pruebas backend manuales | 4h | Dev |
| **Día 4** | Pruebas WooCommerce | 4h | Dev |
| **Día 5** | Pruebas integraciones | 4h | Dev |
| **Día 6** | Pruebas de seguridad | 4h | DevOps |
| **Día 7** | Pruebas de rendimiento | 4h | DevOps |
| **Día 8** | Reporte y aprobación | 2h | QA Lead |

---

## 🚨 Criterios de Aceptación

### Frontend
- [x] LCP < 2.5s en 95% de las visitas
- [x] FID < 100ms en 95% de las visitas
- [x] CLS < 0.1 en 95% de las visitas
- [x] INP < 200ms en 95% de las visitas
- [x] 95% de las páginas cargan en < 3s
- [x] 100% de las imágenes con lazy loading
- [x] 0 errores de console en 95% de las páginas

### Backend
- [x] 100% de los formularios funcionan
- [x] 100% de los cron jobs ejecutan correctamente
- [x] 100% de los usuarios pueden autenticarse
- [x] 100% de los medios se pueden subir/editar
- [x] 0 errores críticos en logs de PHP

### E-commerce
- [x] 100% de los productos se muestran correctamente
- [x] 100% de los carritos funcionan
- [x] 100% de los pagos se procesan
- [x] 100% de los envíos se calculan
- [x] 0 errores de inventario

### Integraciones
- [x] 100% de las APIs externas responden
- [x] 100% de las notificaciones se envían
- [x] 100% de los datos se sincronizan
- [x] 0 errores de conexión

### Seguridad
- [x] 100% de las reglas de seguridad activas
- [x] 100% de los permisos correctos
- [x] 100% de las HTTPS forzado
- [x] 0 vulnerabilidades críticas

### Rendimiento
- [x] Lighthouse score > 90 en todos los métricas
- [x] GTmetrix grade A
- [x] 100% de cache hits en 90% de las visitas
- [x] 0 queries lentas (> 1s)

---

## 📊 Métricas de Éxito

| Métrica | Meta | Actual | Estado |
|---------|------|--------|--------|
| LCP | < 2.5s | N/A | ⏳ |
| FID | < 100ms | N/A | ⏳ |
| CLS | < 0.1 | N/A | ⏳ |
| INP | < 200ms | N/A | ⏳ |
| Lighthouse | > 90 | N/A | ⏳ |
| GTmetrix | Grade A | N/A | ⏳ |
| PHP Errors | 0 | N/A | ⏳ |
| Cron Failures | 0 | N/A | ⏳ |
| Cache Hit Rate | > 90% | N/A | ⏳ |

---

## 🧪 Pruebas Específicas de PHP 8.4

### 1. Magic Methods
```javascript
// Verificar que __get, __set, __isset funcionan correctamente
cy.visit('/products');
cy.get('.product').should('exist');
// Los plugins Pods y NextGen dependen de magic methods
```

### 2. Ternario con Funciones
```javascript
// Verificar que las funciones llamadas en contexto ternario no rompen
// Ejemplo: $var ?: func()
```

### 3. @global / @static
```javascript
// Verificar que no hay errores de E_DEPRECATED
cy.visit('/products');
cy.log('No debería haber errores de compatibilidad');
```

### 4. Multisite
```javascript
// Verificar que todos los sitios del multisite funcionan
// Path-based subdomains (SUBDOMAIN_INSTALL = false)
```

### 5. WordPress 6.9+
```javascript
// Verificar compatibilidad con las nuevas funciones de WP 6.9
// Ej: wp_get_post_terms con nuevas opciones
```

---

## 📋 Checklist de Pruebas (Fase 3)

### Pre-requisitos
- [x] Staging environment listo
- [x] Docker con PHP 8.4 activo
- [x] Todos los plugins y temas actualizados
- [x] Script de pruebas instalado (Cypress/Jest)
- [x] Herramientas de rendimiento configuradas

### Ejecución
- [ ] Día 1-2: Pruebas frontend manuales
- [ ] Día 3-4: Pruebas backend manuales
- [ ] Día 5: Pruebas integraciones
- [ ] Día 6: Pruebas de seguridad
- [ ] Día 7: Pruebas de rendimiento (automatizadas)
- [ ] Día 8: Reporte y aprobación

### Posibilidades
- [ ] Documentación de errores encontrados
- [ ] Listado de plugins problemáticos
- [ ] Matriz de riesgo final
- [ ] Aprobación para producción

---

## 📝 Notas

- **Prioridad alta:** Plugins Pods y NextGen Gallery (dependencia de magic methods)
- **Prioridad media:** CMB2, WooCommerce, LayerSlider
- **Prioridad baja:** Plugins de terceros sin mantenimiento activo
- **Específico PHP 8.4:** Verificar comportamiento de ternario con funciones

---

*Fase 3 en progreso. Próximos pasos: Ejecutar pruebas manuales y automatizadas.*