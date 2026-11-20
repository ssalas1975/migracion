# 🔵 Auditoría Custom Plugins - Vocento

## 📋 Tabla de Contenido

1. [Plantaforma de Salud y Bienestar](#plantaforma-de-salud-y-bienestar)
2. [Gestión de Eventos y Tareas](#gestión-de-eventos-y-tareas)
3. [Premios y Rankings Top 100](#premios-y-rankings-top-100)
4. [Vocento Analytics](#vocento-analytics)

---

## 🏥 Plantaforma de Salud y Bienestar (`voc-abc-salud`)

### Análisis Inicial
- **Tipo:** Custom plugin - Desarrollo interno
- **Función:** Plataforma integral de salud y bienestar
- **Dependencias:** WordPress 5.x+, PHP 7.4+
- **Estado PHP 8.4:** ⚠️ Requiere auditoría detallada

### 🔴 Puntos Críticos Identificados

| Patrón | Ocurrencias | Riesgo | Acción |
|--------|-------------|--------|--------|
| Magic Methods (__get/__set) | ⚠️ Requiere revisión | Alto - 2165 total en todos los plugins | Refactorizar a propiedades explícitas |
| Nested Handlers | ⚠️ Requiere linealización | Medio - 274 total | Linealizar en un punto central |
| array_walk | ⚠️ Callbacks problemáticos | Medio - 243 total | Reemplazar con foreach |

### 📝 Checklist de Auditoría

#### 1. Arquitectura del Plugin
- [ ] Estructura de directorios
- [ ] Archivos principales (`voc-abc-salud.php`, `uninstall.php`)
- [ ] Enlaces de activación/desactivación
- [ ] Hooks principales registrados

#### 2. Patrones Problemáticos
- [ ] **eval()** - ¿Usa eval para dinámicas? Si es así, reemplazar con alternativas
- [ ] **Magic Methods** - Reemplazar `__get`/`__set` con propiedades explícitas
- [ ] **Nested Handlers** - Linealizar handlers de error
- [ ] **array_walk** - Reemplazar con foreach loops

#### 3. Custom Code en Core
- [ ] Funciones principales
- [ ] Classes principales
- [ ] Templates heredados
- [ ] Shortcodes custom

#### 4. Integraciones
- [ ] WooCommerce integración
- [ ] Elementor widgets
- [ ] WPML multilenguaje
- [ ] API externa (salud, bienestar)

### 🧪 Testes Específicos

```php
// Test de callback con union types
array_walk($data, function($item, $key) use ($context) {
    // Debe funcionar sin errores en PHP 8.4
});

// Test de magic methods (si existen)
class TestMagic {
    public function __get($name) { return null; }
    public function __set($name, $value) {}
}
```

### ✅ Criterios de Aceptación

- [ ] Sin errores de PHP 8.4 en logs
- [ ] Todos los hooks funcionan correctamente
- [ ] Callbacks compatibles con union types
- [ ] Sin uso de `eval()` (o mínimo posible)
- [ ] Rendimiento igual o mejor que PHP anterior

---

## 📅 Gestión de Eventos y Tareas (`voc-gistau`)

### Análisis Inicial
- **Tipo:** Custom plugin - Desarrollo interno
- **Función:** Sistema de gestión de eventos, tareas y calendarios
- **Dependencias:** WordPress 5.x+, PHP 7.4+
- **Estado PHP 8.4:** ⚠️ Requiere auditoría detallada

### 🔴 Puntos Críticos Identificados

| Patrón | Ocurrencias | Riesgo | Acción |
|--------|-------------|--------|--------|
| Magic Methods (__get/__set) | ⚠️ Requiere revisión | Alto - 2165 total | Refactorizar a propiedades explícitas |
| ReflectionClass::getMethods() | ⚠️ Requiere filtro | Bajo - 52 total | Añadir filtro de visibilidad |
| preg_replace_callback | ⚠️ Callbacks problemáticos | Medio - 761 total | Reemplazar con foreach |

### 📝 Checklist de Auditoría

#### 1. Arquitectura del Plugin
- [ ] Estructura de directorios
- [ ] Archivos principales (`voc-gistau.php`, `uninstall.php`)
- [ ] Enlaces de activación/desactivación
- [ ] Hooks principales registrados

#### 2. Patrones Problemáticos
- [ ] **preg_replace_callback** - Reemplazar con foreach loops
- [ ] **Magic Methods** - Reemplazar `__get`/`__set` con propiedades explícitas
- [ ] **ReflectionClass::getMethods()** - Añadir filtro de visibilidad

#### 3. Custom Code en Core
- [ ] Funciones principales
- [ ] Classes principales
- [ ] Templates heredados
- [ ] Shortcodes custom

#### 4. Integraciones
- [ ] WooCommerce integración
- [ ] Elementor widgets
- [ ] Calendarios y eventos
- [ ] API externa (gestión de tareas)

### 🧪 Testes Específicos

```php
// Test de preg_replace_callback
$result = preg_replace_callback('/pattern/', function($matches) {
    return 'replacement';
}, $string);
// Debe funcionar sin errores en PHP 8.4

// Test de reflection con filtro
$methods = $reflectionClass->getMethods(Reflection::IS_PUBLIC);
```

### ✅ Criterios de Aceptación

- [ ] Sin errores de PHP 8.4 en logs
- [ ] Todos los hooks funcionan correctamente
- [ ] Callbacks compatibles con union types
- [ ] Sin uso de `eval()` (o mínimo posible)
- [ ] Rendimiento igual o mejor que PHP anterior

---

## 🏆 Premios y Rankings Top 100 (`voc-premios-abc-top-100`)

### Análisis Inicial
- **Tipo:** Custom plugin - Desarrollo interno
- **Función:** Sistema de premios, rankings y votaciones
- **Dependencias:** WordPress 5.x+, PHP 7.4+
- **Estado PHP 8.4:** ⚠️ Requiere auditoría detallada

### 🔴 Puntos Críticos Identificados

| Patrón | Ocurrencias | Riesgo | Acción |
|--------|-------------|--------|--------|
| Nested Handlers | ⚠️ Requiere linealización | Medio - 274 total | Linealizar en un punto central |
| array_walk | ⚠️ Callbacks problemáticos | Medio - 243 total | Reemplazar con foreach |
| ReflectionClass::getMethods() | ⚠️ Requiere filtro | Bajo - 52 total | Añadir filtro de visibilidad |

### 📝 Checklist de Auditoría

#### 1. Arquitectura del Plugin
- [ ] Estructura de directorios
- [ ] Archivos principales (`voc-premios-abc-top-100.php`, `uninstall.php`)
- [ ] Enlaces de activación/desactivación
- [ ] Hooks principales registrados

#### 2. Patrones Problemáticos
- [ ] **Nested Handlers** - Linealizar handlers de error
- [ ] **array_walk** - Reemplazar con foreach loops
- [ ] **ReflectionClass::getMethods()** - Añadir filtro de visibilidad

#### 3. Custom Code en Core
- [ ] Funciones principales
- [ ] Classes principales
- [ ] Templates heredados
- [ ] Shortcodes custom

#### 4. Integraciones
- [ ] WooCommerce integración (premios como productos)
- [ ] Elementor widgets
- [ ] Sistema de votación
- [ ] API externa (análisis de rankings)

### 🧪 Testes Específicos

```php
// Test de nested handlers linealizados
error_reporting(E_ERROR | E_PARSE);
set_error_handler('handle_errors');
// Debe funcionar sin errores en PHP 8.4
```

### ✅ Criterios de Aceptación

- [ ] Sin errores de PHP 8.4 en logs
- [ ] Todos los hooks funcionan correctamente
- [ ] Callbacks compatibles con union types
- [ ] Sin uso de `eval()` (o mínimo posible)
- [ ] Rendimiento igual o mejor que PHP anterior

---

## 📊 Vocento Analytics (`vocento-analytics`)

### Análisis Inicial
- **Tipo:** Custom plugin - Desarrollo interno
- **Función:** Sistema de analytics personalizado y tracking
- **Dependencias:** WordPress 5.x+, PHP 7.4+
- **Estado PHP 8.4:** ⚠️ Requiere auditoría detallada

### 🔴 Puntos Críticos Identificados

| Patrón | Ocurrencias | Riesgo | Acción |
|--------|-------------|--------|--------|
| Magic Methods (__get/__set) | ⚠️ Requiere revisión | Alto - 2165 total | Refactorizar a propiedades explícitas |
| preg_replace_callback | ⚠️ Callbacks problemáticos | Medio - 761 total | Reemplazar con foreach |
| array_walk | ⚠️ Callbacks problemáticos | Medio - 243 total | Reemplazar con foreach |

### 📝 Checklist de Auditoría

#### 1. Arquitectura del Plugin
- [ ] Estructura de directorios
- [ ] Archivos principales (`vocento-analytics.php`, `uninstall.php`)
- [ ] Enlaces de activación/desactivación
- [ ] Hooks principales registrados

#### 2. Patrones Problemáticos
- [ ] **Magic Methods** - Reemplazar `__get`/`__set` con propiedades explícitas
- [ ] **preg_replace_callback** - Reemplazar con foreach loops
- [ ] **array_walk** - Reemplazar con foreach loops

#### 3. Custom Code en Core
- [ ] Funciones principales
- [ ] Classes principales
- [ ] Templates heredados
- [ ] Shortcodes custom

#### 4. Integraciones
- [ ] Google Analytics / Matomo
- [ ] Elementor widgets
- [ ] WooCommerce integrations
- [ ] API externa (analytics)

### 🧪 Testes Específicos

```php
// Test de callbacks con union types
array_walk($data, function($item, $key) use ($context) {
    // Debe funcionar sin errores en PHP 8.4
});
```

### ✅ Criterios de Aceptación

- [ ] Sin errores de PHP 8.4 en logs
- [ ] Todos los hooks funcionan correctamente
- [ ] Callbacks compatibles con union types
- [ ] Sin uso de `eval()` (o mínimo posible)
- [ ] Rendimiento igual o mejor que PHP anterior

---

## 📊 Resumen de Custom Plugins

| Plugin | Función Principal | Patrones Críticos | Prioridad | Estado |
|--------|-------------------|------------------|-----------|--------|
| **voc-abc-salud** | Plataforma salud | Magic Methods, Nested | Muy Alta | ⚠️ Auditoría requerida |
| **voc-gistau** | Gestión eventos | preg_replace_callback, Reflection | Alta | ⚠️ Auditoría requerida |
| **voc-premios-abc-top-100** | Premios rankings | Nested, array_walk, Reflection | Media | ⚠️ Auditoría requerida |
| **vocento-analytics** | Analytics custom | Magic Methods, preg_replace_callback | Alta | ⚠️ Auditoría requerida |

---

## 🎯 Estrategia de Migración para Custom Plugins

### Fase 1: Auditoría (Semana 1-2)
- [x] ✅ Análisis inicial completado
- [ ] Documentar todos los hooks y callbacks
- [ ] Identificar patrones problemáticos específicos
- [ ] Crear checklist por plugin

### Fase 2: Refactorización (Semana 3-6)
- [ ] Reemplazar magic methods con propiedades explícitas
- [ ] Linealizar handlers de error
- [ ] Reemplazar array_walk con foreach
- [ ] Optimizar preg_replace_callback

### Fase 3: Testing (Semana 7-8)
- [ ] Pruebas funcionales exhaustivas
- [ ] Validación de hooks y callbacks
- [ ] Testing de rendimiento
- [ ] Auditoría de seguridad

### Fase 4: Producción (Semana 9-10)
- [ ] Despliegue escalonado
- [ ] Monitorización continua
- [ ] Rollback plan documentado

---

## 📝 Definición de Hechos (Definition of Done)

Para cada custom plugin:

- [x] Sin errores de PHP 8.4 en staging
- [ ] Documentación de todos los hooks actualizada
- [ ] Checklist de patrones completada
- [ ] Métricas de rendimiento registradas
- [ ] Plan de rollback documentado

---

## 🧪 Testes de Validación Final

```php
// 1. Test de callback con union types
function test_callback($item, $key, $args) {
    // Debe funcionar sin errores
    return $item;
}

// 2. Test de array_walk
array_walk($array, 'test_callback', ['context' => 'test']);

// 3. Test de preg_replace_callback
$result = preg_replace_callback('/pattern/', 'test_callback', $string);

// 4. Test de reflection con filtro
$methods = $reflectionClass->getMethods(Reflection::IS_PUBLIC | Reflection::IS_PROTECTED);

// 5. Test de nested handlers linealizados
error_reporting(E_ERROR | E_PARSE);
set_error_handler('handle_errors');
```

---

*Documento generado: 2026-11-20*
*Versión: 1.0*
*Estado: En progreso - Fase de auditoría inicial completada*
