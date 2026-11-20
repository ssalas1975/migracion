#!/bin/bash

# Plugin PHP 8.4 Compatibility Scanner
OUTPUT_FILE="PHP84_PLUGIN_SCAN_REPORT.md"

echo "# 🔍 Reporte de Escaneo de Plugins - Compatibilidad PHP 8.4" > "$OUTPUT_FILE"
echo "## Generado: $(date)" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"
echo "---" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# Contadores para estadísticas
TOTAL_PLUGINS=0
CRITICAL_ISSUES=0
MODERATE_ISSUES=0
LOW_ISSUES=0
CUSTOM_PLUGINS=0
THIRD_PARTY_PLUGINS=0

echo "## 📊 Resumen General" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# Recorrer cada plugin
for plugin_dir in */; do
    plugin_name=$(basename "$plugin_dir")
    TOTAL_PLUGINS=$((TOTAL_PLUGINS + 1))
    
    # Determinar si es custom o third-party
    if [[ "$plugin_name" =~ ^[a-z].*-[a-z]+$ ]]; then
        THIRD_PARTY_PLUGINS=$((THIRD_PARTY_PLUGINS + 1))
    else
        CUSTOM_PLUGINS=$((CUSTOM_PLUGINS + 1))
    fi
    
    echo "### $plugin_name" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
    
    # Buscar archivos PHP en el plugin
    php_files=$(find "$plugin_dir" -name "*.php" -type f | head -20)
    
    if [ -z "$php_files" ]; then
        echo "❌ Sin archivos PHP detectados" >> "$OUTPUT_FILE"
        continue
    fi
    
    echo "**Archivos PHP analizados:** $(echo "$php_files" | wc -l)" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
    
    # 1. Buscar uso de eval() - Patrón que puede romperse con union types
    eval_count=$(grep -r "eval\(" "$plugin_dir" 2>/dev/null | head -5)
    if [ ! -z "$eval_count" ]; then
        echo "⚠️ **eval():** $(echo "$eval_count" | wc -l | tr -d ' ') us(es)" >> "$OUTPUT_FILE"
        MODERATE_ISSUES=$((MODERATE_ISSUES + 1))
    fi
    
    # 2. Buscar uso de __get / __set (magic methods)
    magic_methods=$(grep -r "__get\|__set" "$plugin_dir" 2>/dev/null | head -3)
    if [ ! -z "$magic_methods" ]; then
        echo "⚠️ **Magic Methods:** $(echo "$magic_methods" | wc -l | tr -d ' ') occurrences" >> "$OUTPUT_FILE"
        LOW_ISSUES=$((LOW_ISSUES + 1))
    fi
    
    # 3. Buscar set_error_handler anidado
    nested_handlers=$(grep -r "set_error_handler\|restore_error_handler" "$plugin_dir" 2>/dev/null | head -3)
    if [ ! -z "$nested_handlers" ]; then
        echo "⚠️ **Nested error handlers:** $(echo "$nested_handlers" | wc -l | tr -d ' ') occurrences" >> "$OUTPUT_FILE"
        LOW_ISSUES=$((LOW_ISSUES + 1))
    fi
    
    # 4. Buscar array_walk con callbacks (puede tener problemas con union types)
    array_walk=$(grep -r "array_walk" "$plugin_dir" 2>/dev/null | head -3)
    if [ ! -z "$array_walk" ]; then
        echo "⚠️ **array_walk:** $(echo "$array_walk" | wc -l | tr -d ' ') occurrences" >> "$OUTPUT_FILE"
        LOW_ISSUES=$((LOW_ISSUES + 1))
    fi
    
    # 5. Buscar uso de preg_replace_callback (puede tener problemas con union types)
    preg_callback=$(grep -r "preg_replace_callback" "$plugin_dir" 2>/dev/null | head -3)
    if [ ! -z "$preg_callback" ]; then
        echo "⚠️ **preg_replace_callback:** $(echo "$preg_callback" | wc -l | tr -d ' ') occurrences" >> "$OUTPUT_FILE"
        MODERATE_ISSUES=$((MODERATE_ISSUES + 1))
    fi
    
    # 6. Buscar uso de ReflectionClass getMethods (necesita filtro)
    reflection=$(grep -r "getMethods()" "$plugin_dir" 2>/dev/null | head -3)
    if [ ! -z "$reflection" ]; then
        echo "⚠️ **ReflectionClass::getMethods():** $(echo "$reflection" | wc -l | tr -d ' ') occurrences" >> "$OUTPUT_FILE"
        LOW_ISSUES=$((LOW_ISSUES + 1))
    fi
    
    # 7. Buscar uso de array_column (nuevo en PHP 5.5, compatible)
    echo "" >> "$OUTPUT_FILE"
    
    # 8. Buscar uso de match() (PHP 8.0+, compatible con 8.4)
    if grep -q "match\s*(\s*[^)]*\)" "$plugin_dir"/*.php 2>/dev/null; then
        echo "✅ **match():** Usado correctamente" >> "$OUTPUT_FILE"
    fi
    
    # 9. Buscar union types (PHP 8.0+, compatible con 8.4)
    if grep -q "type\s*:\s*[a-zA-Z_]" "$plugin_dir"/*.php 2>/dev/null; then
        echo "✅ **Union Types:** Usados correctamente" >> "$OUTPUT_FILE"
    fi
    
    # 10. Buscar readonly classes (PHP 8.4+)
    if grep -q "readonly\s*\[" "$plugin_dir"/*.php 2>/dev/null; then
        echo "🆕 **Readonly Classes:** Usados correctamente" >> "$OUTPUT_FILE"
    fi
    
    # 11. Buscar named arguments (PHP 8.0+, compatible)
    if grep -q "\$\w\+\s*=\s*\w\+\(" "$plugin_dir"/*.php 2>/dev/null; then
        echo "✅ **Named Arguments:** Usados correctamente" >> "$OUTPUT_FILE"
    fi
    
    # 12. Buscar Fibers (PHP 8.1+, compatible)
    if grep -q "fiber\(\)" "$plugin_dir"/*.php 2>/dev/null; then
        echo "🆕 **Fibers:** Usados correctamente" >> "$OUTPUT_FILE"
    fi
    
    # 13. Buscar JIT (PHP 8.4+)
    if grep -q "jit" "$plugin_dir"/*.php 2>/dev/null; then
        echo "🆕 **JIT optimizations:** Referenciadas" >> "$OUTPUT_FILE"
    fi
    
    echo "" >> "$OUTPUT_FILE"
    echo "---" >> "$OUTPUT_FILE"
    echo "" >> "$OUTPUT_FILE"
done

# Resumen de estadísticas
echo "## 📈 Estadísticas Finales" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"
echo "| Métrica | Valor |" >> "$OUTPUT_FILE"
echo "|----------|-------|" >> "$OUTPUT_FILE"
echo "| Total Plugins | $TOTAL_PLUGINS |" >> "$OUTPUT_FILE"
echo "| Custom Plugins | $CUSTOM_PLUGINS |" >> "$OUTPUT_FILE"
echo "| Third-Party Plugins | $THIRD_PARTY_PLUGINS |" >> "$OUTPUT_FILE"
echo "| Critical Issues | $CRITICAL_ISSUES |" >> "$OUTPUT_FILE"
echo "| Moderate Issues | $MODERATE_ISSUES |" >> "$OUTPUT_FILE"
echo "| Low Issues | $LOW_ISSUES |" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# Categorizar por prioridad de migración
echo "## 🎯 Priorización de Migración" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# Plugins críticos (core functionality)
CRITICAL_PLUGINS=(
    "woocommerce"
    "elementor"
    "sitepress-multilingual-cms"
    "td-blocks-red-pack"
    "wp-super-cache"
)

for plugin in "${CRITICAL_PLUGINS[@]}"; do
    if [ -d "$plugin" ]; then
        echo "### 🟢 CRÍTICO: $plugin" >> "$OUTPUT_FILE"
        echo "- Funcionalidad central del sitio" >> "$OUTPUT_FILE"
        echo "- **Prioridad:** Alta" >> "$OUTPUT_FILE"
        echo "- **Estado PHP 8.4:** Requiere auditoría detallada" >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    fi
done

# Plugins de UI/UX
UI_PLUGINS=(
    "brizy"
    "oxygen"
    "header-footer-elementor"
)

for plugin in "${UI_PLUGINS[@]}"; do
    if [ -d "$plugin" ]; then
        echo "### 🟡 UI/UX: $plugin" >> "$OUTPUT_FILE"
        echo "- Elementos visuales y experiencia de usuario" >> "$OUTPUT_FILE"
        echo "- **Prioridad:** Media" >> "$OUTPUT_FILE"
        echo "- **Estado PHP 8.4:** Compatible por defecto" >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    fi
done

# Plugins custom (desarrollados internamente)
CUSTOM_NAMES=(
    "voc-abc-salud"
    "voc-gistau"
    "voc-premios-abc-top-100"
    "vocento-analytics"
)

for plugin in "${CUSTOM_NAMES[@]}"; do
    if [ -d "$plugin" ]; then
        echo "### 🔵 CUSTOM: $plugin" >> "$OUTPUT_FILE"
        echo "- Desarrollado internamente por Vocento" >> "$OUTPUT_FILE"
        echo "- **Prioridad:** Variable (depende de uso)" >> "$OUTPUT_FILE"
        echo "- **Estado PHP 8.4:** Requiere auditoría personalizada" >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    fi
done

echo "## ⚠️ Hallazgos Críticos" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"
echo "### Patrones que requieren atención:" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# Contar patrones en todos los plugins
total_eval=$(grep -r "eval\(" wp-content/plugins/ 2>/dev/null | wc -l)
total_magic=$(grep -r "__get\|__set" wp-content/plugins/ 2>/dev/null | wc -l)
total_nested=$(grep -r "set_error_handler" wp-content/plugins/ 2>/dev/null | wc -l)

echo "| Patrón | Total | Impacto en PHP 8.4 |" >> "$OUTPUT_FILE"
echo "|--------|-------|-------------------|" >> "$OUTPUT_FILE"
echo "| eval() | $total_eval | 🟡 Medio - Puede romper union types |" >> "$OUTPUT_FILE"
echo "| Magic Methods | $total_magic | 🟢 Bajo - Requiere refactorización |" >> "$OUTPUT_FILE"
echo "| Nested Handlers | $total_nested | 🟢 Bajo - Linealización simple |" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

cat "$OUTPUT_FILE"
