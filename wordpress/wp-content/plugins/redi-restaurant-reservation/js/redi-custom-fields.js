class ReDiCustomFields {
    static instance = null;

    static getInstance() {
        if (!ReDiCustomFields.instance) {
            ReDiCustomFields.instance = new ReDiCustomFields();
        }
        return ReDiCustomFields.instance;
    }

    constructor() {
        this.container = jQuery('#custom_fields_container');
        this.loadedFields = new Map(); // Track loaded fields by placeId
    }

    isLoaded(placeId) {
        return this.loadedFields.has(placeId);
    }

    renderFields(customFields, placeId) {
        // If fields are already loaded for this place, skip
        if (this.isLoaded(placeId)) {
            return;
        }

        // API can return an empty object or wrapped payload when there are no custom fields.
        const normalizedFields = Array.isArray(customFields)
            ? customFields
            : Array.isArray(customFields?.CustomFields)
                ? customFields.CustomFields
                : [];

        if (!normalizedFields.length) {
            this.loadedFields.set(placeId, true);
            return;
        }

        // Sort custom fields by DisplayOrder
        normalizedFields.sort((a, b) => {
            return (a.DisplayOrder || 0) - (b.DisplayOrder || 0);
        });
        
        // Process each custom field
        normalizedFields.forEach(field => {
            // Generate HTML for this field
            const fieldHtml = this.renderField(field);
            
            // Find all elements with data-display-order
            const orderedElements = jQuery('[data-display-order]');
            
            // Find the element with the closest lower or equal display-order
            let targetElement = null;
            let targetOrder = -1;
            
            orderedElements.each(function() {
                const elementOrder = parseInt(jQuery(this).data('display-order'));
                if (elementOrder <= field.DisplayOrder && elementOrder > targetOrder) {
                    targetElement = jQuery(this);
                    targetOrder = elementOrder;
                }
            });
            
            if (targetElement) {
                // Insert after the target element
                targetElement.after(fieldHtml);
            } else {
                // If no target found, append to container
                this.container.append(fieldHtml);
            }
        });

        // Mark these fields as loaded for this place
        this.loadedFields.set(placeId, true);
    }

    renderField(field) {
        let html = `<div data-display-order="${field.DisplayOrder}">`;


         if (
            (field.Type === 'dropdown' || field.Type === 'options') &&
            (!field.Values || field.Values.trim() === '')
        ) {
            console.warn(`Skipping field "${field.Text}" (ID: ${field.Id}) — no values defined. Defined values are required for dropdown and options types. Go to Settings > Custom Fields to define values.`);
            return ''; // Don't render anything
        }

        switch (field.Type) {
            case 'options':
                html += `<label for="field_${field.Id}">${field.Text}`;
                if (field.Required) {
                    html += `<span class="redi_required"> *</span>
                        <input type="hidden" 
                            id="field_${field.Id}_message" 
                            value="${field.Message || 'Custom field is required'}">`;
                }
                html += '</label>';
                html += this.renderRadioField(field);
                break;
            case 'dropdown':
                html += `<label for="field_${field.Id}">${field.Text}`;
                if (field.Required) {
                    html += `<span class="redi_required"> *</span>
                        <input type="hidden" 
                            id="field_${field.Id}_message" 
                            value="${field.Message || 'Custom field is required'}">`;
                }
                html += '</label>';
                html += this.renderDropdownField(field);
                break;
            case 'newsletter':
            case 'reminder':
            case 'allowsms':
            case 'checkbox':
            case 'allowwhatsapp':
            case 'gdpr':
                html += this.renderCheckboxField(field); // label will be inside this
                break;
            default:
                html += `<label for="field_${field.Id}">${field.Text}`;
                if (field.Required) {
                    html += `<span class="redi_required"> *</span>
                        <input type="hidden" 
                            id="field_${field.Id}_message" 
                            value="${field.Message || 'Custom field is required'}">`;
                }
                html += '</label>';
                html += this.renderTextField(field);
        }

        html += '</div>';
        return html;
    }

    renderTextField(field) {
        return `<input type="text" 
            value="" 
            id="field_${field.Id}" 
            name="field_${field.Id}"
            ${field.Required ? 'class="field_required"' : ''}>`;
    }

    renderCheckboxField(field) {
        return `
            <div class="redi-custom-checkbox">
                <label for="field_${field.Id}" style="display:block;">
                    ${field.Text}
                    ${field.Required ? '<span class="redi_required"> *</span>' : ''}
                </label>
                <input type="checkbox" 
                    id="field_${field.Id}" 
                    name="field_${field.Id}"
                    ${field.Required ? 'class="field_required"' : ''}
                    ${field.Default === 'True' ? 'checked' : ''}>
                ${field.Required ? `<input type="hidden" id="field_${field.Id}_message" value="${field.Message || 'Custom field is required'}">` : ''}
            </div>
        `;
    }

    renderRadioField(field) {
        return field.Values.split(',')
            .filter(value => value)
            .map(value => `
                <input type="radio" 
                    value="${value}" 
                    name="field_${field.Id}" 
                    id="field_${field.Id}_${value}" 
                    class="redi-radiobutton${field.Required ? ' field_required' : ''}">
                <label class="redi-radiobutton-label" 
                    for="field_${field.Id}_${value}">${value}</label>
                <br/>`)
            .join('');
    }

    renderDropdownField(field) {
        const options = field.Values.split(',')
            .filter(value => value)
            .map(value => `<option value="${value}">${value}</option>`)
            .join('');

        return `<select 
            id="field_${field.Id}" 
            name="field_${field.Id}"
            ${field.Required ? 'class="field_required"' : ''}>
            <option value="">Select</option>
            ${options}
        </select>`;
    }
} 