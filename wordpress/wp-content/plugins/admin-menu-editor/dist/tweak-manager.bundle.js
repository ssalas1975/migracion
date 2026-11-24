"use strict";
(self["wsAmeWebpackChunk"] = self["wsAmeWebpackChunk"] || []).push([["tweak-manager"],{

/***/ "./modules/tweaks/tweak-manager.ts"
/*!*****************************************!*\
  !*** ./modules/tweaks/tweak-manager.ts ***!
  \*****************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _customizables_revised_ui_ui_pack_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../customizables/revised-ui/ui-pack.js */ "./customizables/revised-ui/ui-pack.js");
/* harmony import */ var _customizables_revised_ui_registry_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../customizables/revised-ui/registry.js */ "./customizables/revised-ui/registry.js");
/* harmony import */ var _customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../customizables/revised-ui/settings.js */ "./customizables/revised-ui/settings.js");
/* harmony import */ var _customizables_revised_ui_component_collection_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../customizables/revised-ui/component-collection.js */ "./customizables/revised-ui/component-collection.js");
/* harmony import */ var _customizables_revised_ui_components_ame_rev_event_button_ame_rev_event_button_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../customizables/revised-ui/components/ame-rev-event-button/ame-rev-event-button.js */ "./customizables/revised-ui/components/ame-rev-event-button/ame-rev-event-button.js");
/// <reference path="../../js/knockout.d.ts" />
/// <reference path="../../js/jquery.d.ts" />
/// <reference types="@types/lodash" />
/// <reference path="../../modules/actor-selector/actor-selector.ts" />
/// <reference path="../../js/jquery.biscuit.d.ts" />
/// <reference path="../../js/ko-extensions.ts" />
var SaveSettingsForm = AmeKoFreeExtensions.SaveSettingsForm;





let ameTweakManager;
class AmeTweakManagerModule {
    constructor(scriptData) {
        this.lastUserTweakSuffix = 0;
        this.adminCssSection = null;
        this.addUrlBlockDialog = null;
        this.cachedCodeEditorParams = null;
        const _ = AmeTweakManagerModule._;
        this.actorSelector = new AmeActorSelector(AmeActors, scriptData.isProVersion);
        //Reselect the previously selected actor.
        this.actorSelector.setSelectedActorFromUrl();
        //Set syntax highlighting options.
        this.cssHighlightingOptions = _.merge({}, scriptData.defaultCodeEditorSettings, {
            'codemirror': {
                'mode': 'css',
                'lint': true,
                'autoCloseBrackets': true,
                'matchBrackets': true
            }
        });
        const collapsibleSectionStateStore = new AmeCollapsibleStateStore(AmeTweakManagerModule.openSectionCookieName, scriptData.preferenceCookiePath, AmeTweakManagerModule.openSectionCookieName);
        const registry = _customizables_revised_ui_registry_js__WEBPACK_IMPORTED_MODULE_1__.ServiceRegistry.init()
            .register('actorSelector', this.actorSelector)
            .register('collapsibleStateStore', collapsibleSectionStateStore);
        const featureStrategy = new AmeActorFeatureStrategy({
            roleCombinationMode: AmeRoleCombinationMode.Some,
            getSelectedActor: this.actorSelector.getActorObservable(ko),
            getAllActors: () => this.actorSelector.getVisibleActors()
        });
        this.tweaksUi = new _customizables_revised_ui_ui_pack_js__WEBPACK_IMPORTED_MODULE_0__.UiPack(scriptData.uiPack, registry, (c) => {
            switch (c.t) {
                case 'container':
                    if (!(c.role === 'group')) {
                        c = { ...c, component: 'ame-rev-postbox-section' };
                    }
                    //Register the admin CSS section container so we can add new tweaks to it later.
                    if (c.id === 'twm-section_admin-css' && c.t === 'container') {
                        c.registerSelf = (container) => {
                            this.adminCssSection = container;
                        };
                    }
                    break;
                case 'control':
                    if (c.component === 'ame-actor-feature-checkbox') {
                        //Ensure actor feature checkboxes use the shared strategy.
                        c['strategy'] = featureStrategy;
                    }
            }
            return c;
        });
        const sectionIds = [];
        this.getDefaultSlotContent(scriptData.uiPack.interfaceStructure).forEach((child) => {
            if (child.t === 'container' && (typeof child.id === 'string')) {
                sectionIds.push(child.id);
            }
        });
        //By default, open the first section and close the rest.
        if (!collapsibleSectionStateStore.hasAnyStoredStates()) {
            const sectionStates = {};
            sectionIds.forEach((sectionId, index) => {
                sectionStates[sectionId] = index === 0;
            });
            collapsibleSectionStateStore.setAll(sectionStates);
        }
        if (scriptData.lastUserTweakSuffix) {
            this.lastUserTweakSuffix = scriptData.lastUserTweakSuffix;
        }
        this.adminCssEditorDialog = new AmeEditAdminCssDialogV3(scriptData.editAdminCssUiPack, this);
        if (scriptData.addUrlBlockUiPack) {
            this.addUrlBlockDialog = new AmeAddUrlBlockDialog(scriptData.addUrlBlockUiPack, (blockData) => {
                //Handle the added block data.
                const blocks = this.getBlocksArraySetting();
                blocks.addItem(blockData);
            });
        }
        this.saveSettingsForm = new SaveSettingsForm({
            ...scriptData.saveFormConfig,
            settingsGetter: () => this.serializeSettings(),
            selectedActor: this.actorSelector.getActorObservable(ko),
            formClasses: ['ame-twm-save-form']
        });
    }
    serializeSettings() {
        const allSettings = this.tweaksUi.settings.serializeValue();
        let tweaks = allSettings[AmeTweakManagerModule.tweaksSettingsId];
        if (!tweaks || (typeof tweaks !== 'object')) {
            tweaks = {};
        }
        let featureData = allSettings[AmeTweakManagerModule.featureDataSettingId];
        if (!featureData || (typeof featureData !== 'object')) {
            featureData = {};
        }
        return {
            'tweaks': tweaks,
            'featureData': featureData,
            'lastUserTweakSuffix': this.lastUserTweakSuffix
        };
    }
    onAddCssSnippet() {
        this.adminCssEditorDialog.open();
    }
    onEditTweak(_unused, event) {
        const detail = this.getCustomEventDetail(event);
        if (!(detail.data instanceof _customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__.Setting)) {
            throw new Error('Edit Tweak event did not contain a valid Setting in its detail: ' + JSON.stringify(detail));
        }
        this.adminCssEditorDialog.selectedTweak = detail.data;
        this.adminCssEditorDialog.open();
    }
    onDeleteTweak(_unused, event) {
        const detail = this.getCustomEventDetail(event);
        //Note: In this case, the event data is expected to be the tweak's ID (a string), not a Setting.
        if (typeof detail.data !== 'string') {
            throw new Error('Delete Tweak event did not contain a valid tweak ID in its detail: ' + JSON.stringify(detail));
        }
        const tweakId = detail.data;
        const tweaks = this.getTweaksStructSetting();
        const tweakToDelete = tweaks.getChild(tweakId);
        if (!tweakToDelete.isDefined()) {
            throw new Error(`Tweak with ID "${tweakId}" not found in tweaks setting.`);
        }
        if (!confirm('Delete this admin CSS snippet?')) {
            return;
        }
        //Find the corresponding control in the admin CSS section and remove it.
        const children = this.getAdminCssSection().slots['default'];
        for (const [index, child] of children().entries()) {
            if (child.t === 'control' && child.objectId === tweakId) {
                children.splice(index, 1);
                break;
            }
        }
        //Remove the tweak settings.
        tweaks.removeSetting(tweakId);
    }
    addAdminCssTweak(settingToCopy) {
        this.lastUserTweakSuffix++;
        const value = settingToCopy.value();
        if (!value) {
            throw new Error('Invalid setting value'); //This should never happen.
        }
        const label = value['label'] || 'New Snippet';
        let slug = AmeTweakManagerModule.slugify(label);
        if (slug !== '') {
            slug = '-' + slug;
        }
        const newTweakId = 'utw-' + this.lastUserTweakSuffix + slug;
        const settingPath = AmeTweakManagerModule.tweaksSettingsId + '.' + newTweakId;
        const tweaks = this.getTweaksStructSetting();
        tweaks.addSetting(newTweakId, (0,_customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__.createSettingFromSchema)(settingToCopy.getSchema(), value));
        //Create the control: the actor feature checkbox with a nested code editor.
        const defaultEditorParams = this.getDefaultCodeEditorParams();
        const newControl = {
            t: 'control',
            label: { t: 'binding', bind: settingPath + '.label' },
            component: 'ame-rev-actor-feature',
            bindings: { value: settingPath + '.enabledForActor' },
            hasTweakActions: true,
            objectId: newTweakId,
            slots: {
                default: [
                    {
                        ...defaultEditorParams,
                        t: 'control',
                        label: '',
                        component: 'ame-rev-code-editor',
                        bindings: { value: settingPath + '.css' },
                    }
                ],
                actions: [
                    {
                        t: "control",
                        kind: "link",
                        component: "ame-rev-event-button",
                        eventName: "adminMenuEditor:editObject",
                        iconClass: "dashicons dashicons-edit",
                        eventData: {
                            "t": "resolve",
                            "ref": {
                                "t": "binding",
                                "bind": settingPath
                            }
                        }
                    },
                    {
                        t: "control",
                        kind: "link",
                        component: "ame-rev-event-button",
                        eventName: "adminMenuEditor:deleteObject",
                        iconClass: "dashicons dashicons-trash",
                        classes: ["ame-delete-action-button"],
                        eventData: newTweakId
                    }
                ]
            }
        };
        //Add the new control to the admin CSS section. Usually, the last child is the "Add CSS Snippet"
        //button, so let's insert it before that.
        const children = this.getAdminCssSection().slots['default'];
        const totalChildren = children().length;
        children.splice(totalChildren - 1, 0, newControl);
    }
    getAdminCssSection() {
        if (this.adminCssSection) {
            return this.adminCssSection;
        }
        throw new Error('Admin CSS section container is not registered yet or does not exist.');
    }
    getTweaksStructSetting() {
        const tweaks = this.tweaksUi.settings
            .getChild(AmeTweakManagerModule.tweaksSettingsId).getOrThrow();
        if (!(tweaks instanceof _customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__.StructSetting)) {
            throw new Error('Tweaks setting is not a StructSetting');
        }
        return tweaks;
    }
    getCustomEventDetail(event) {
        if (event.originalEvent instanceof CustomEvent) {
            return event.originalEvent.detail;
        }
        return null;
    }
    getDefaultSlotContent(element) {
        if (element.slots && element.slots['default']) {
            return element.slots['default'];
        }
        else {
            return [];
        }
    }
    getDefaultCodeEditorParams() {
        if (this.cachedCodeEditorParams) {
            return this.cachedCodeEditorParams;
        }
        //Find the default admin CSS tweak. This is a linear search, but it's only done once,
        //and I expect it will only need to look at <50 nodes in the UI tree, so it should be fine.
        const path = ['twm-section_admin-css', 'ame-tweak-default-admin-css'];
        let currentElement = this.tweaksUi.rootComponent.params;
        for (const id of path) {
            for (const child of this.getDefaultSlotContent(currentElement)) {
                if (child.id === id) {
                    currentElement = child;
                    break;
                }
            }
            if (currentElement.id !== id) {
                throw new Error(`Default admin CSS snippet control not found at path: ${path.join(' -> ')}`);
            }
        }
        //This node should always have a child: a code editor control.
        if (this.getDefaultSlotContent(currentElement).length < 1) {
            throw new Error('Default admin CSS snippet control does not have a child code editor');
        }
        const firstChild = this.getDefaultSlotContent(currentElement)[0];
        if (!firstChild || firstChild.t !== 'control' || firstChild.component !== 'ame-rev-code-editor') {
            throw new Error('Default admin CSS snippet control does not have a child code editor');
        }
        //Grab everything except "id" and "bindings" from the code editor's params.
        const { id, bindings, ...paramsWithoutIdAndBindings } = firstChild;
        this.cachedCodeEditorParams = paramsWithoutIdAndBindings;
        return this.cachedCodeEditorParams;
    }
    //region Block URLs feature
    onAddUrlBlock() {
        if (!this.addUrlBlockDialog) {
            throw new Error('Add URL Block dialog is not available.');
        }
        this.addUrlBlockDialog.open();
    }
    onToggleUrlBlockEditor(_unused, event) {
        const $container = jQuery(event.target).closest('.ame-rui-block-by-url-container');
        const $panel = $container.find('.ame-rui-block-by-url-edit-panel').first();
        $panel.toggle().removeClass('hidden');
        if (event.originalEvent instanceof CustomEvent) {
            const detail = event.originalEvent.detail;
            if (detail && (detail.eventButton instanceof _customizables_revised_ui_components_ame_rev_event_button_ame_rev_event_button_js__WEBPACK_IMPORTED_MODULE_4__.AmeRevEventButton)) {
                const isOpen = $panel.is(':visible');
                detail.eventButton.customLabel(isOpen ? 'Close' : 'Edit');
                detail.eventButton.customIconClass(isOpen ? 'dashicons dashicons-arrow-up' : 'dashicons dashicons-arrow-right');
            }
        }
    }
    onDeleteUrlBlock(_unused, event) {
        if (!confirm('Delete this URL block?')) {
            return;
        }
        const blocks = this.getBlocksArraySetting();
        const detail = this.getCustomEventDetail(event);
        if (detail && (detail.data instanceof _customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__.Setting)) {
            const blockToDelete = detail.data;
            blocks.removeItem(blockToDelete);
        }
        else {
            console.warn('Delete URL Block event did not contain a valid Setting in its detail:', detail);
        }
    }
    getBlocksArraySetting() {
        return this.tweaksUi.settings.getChildByPath('ws_ame_tweak_settings--featureData.url-blocks.blocks')
            .map((blocks) => {
            if (blocks instanceof _customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__.ArraySetting) {
                return blocks;
            }
            else {
                throw new Error('Expected "blocks" to be an ArraySetting, but it was not: ' + blocks);
            }
        })
            .getOrThrow('blocks setting is not defined');
    }
    //endregion
    static slugify(input) {
        const _ = AmeTweakManagerModule._;
        let output = _.deburr(input);
        output = output.replace(/[^a-zA-Z0-9 _\-]/g, '');
        return _.kebabCase(output);
    }
}
AmeTweakManagerModule._ = wsAmeLodash;
AmeTweakManagerModule.openSectionCookieName = 'ame_tmce_open_sections';
AmeTweakManagerModule.tweaksSettingsId = 'ws_ame_tweak_settings--tweaks';
AmeTweakManagerModule.featureDataSettingId = 'ws_ame_tweak_settings--featureData';
class AmeEditAdminCssDialogV3 extends AmeBaseKnockoutDialog {
    constructor(uiConfig, manager) {
        super();
        this.manager = manager;
        this.autoCancelButton = true;
        this.selectedTweak = null;
        this.options.minWidth = 400;
        this.options.classes = {
            'ui-dialog': 'ui-corner-all ame-twm-admin-css-dialog',
        };
        this.panelUi = new _customizables_revised_ui_ui_pack_js__WEBPACK_IMPORTED_MODULE_0__.UiPack(uiConfig);
        this.confirmButtonLabel = ko.observable('Add Snippet');
        this.isConfirmButtonEnabled = ko.pureComputed(() => {
            return !this.panelUi.hasValidationErrors();
        });
    }
    onOpen() {
        if (this.selectedTweak) {
            this.title('Edit admin CSS snippet');
            this.confirmButtonLabel('Save Changes');
            //Load the selected tweak's data into the temp setting for editing.
            this.getTempCssSnippetSetting().updateWithoutValidation(this.selectedTweak.value());
        }
        else {
            this.title('Add admin CSS snippet');
            this.confirmButtonLabel('Add Snippet');
            this.getTempCssSnippetSetting().updateWithoutValidation({
                label: '',
                css: '',
                enabledForActor: {},
                isUserDefined: true,
                typeId: 'admin-css'
            });
        }
    }
    onConfirm() {
        if (this.selectedTweak) {
            this.selectedTweak.value(this.getTempCssSnippetSetting().value());
        }
        else {
            this.manager.addAdminCssTweak(this.getTempCssSnippetSetting());
        }
        this.close();
    }
    onClose() {
        this.selectedTweak = null;
    }
    getTempCssSnippetSetting() {
        const opt = this.panelUi.settings.getChild('tempAdminCssSnippet');
        if (!opt.isDefined()) {
            throw new Error('tempAdminCssSnippet setting is not defined');
        }
        const tempSnippetSetting = opt.get();
        if (tempSnippetSetting instanceof _customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__.StructSetting) {
            return tempSnippetSetting;
        }
        else {
            throw new Error('Expected tempAdminCssSnippet to be a StructSetting, but it was not: ' + tempSnippetSetting);
        }
    }
}
class AmeAddUrlBlockDialog extends AmeBaseKnockoutDialog {
    constructor(ui, add) {
        super();
        this.add = add;
        this.options.minWidth = 400;
        this.panelUi = new _customizables_revised_ui_ui_pack_js__WEBPACK_IMPORTED_MODULE_0__.UiPack(ui);
        this.confirmButtonLabel = ko.observable('Add Block');
        this.isConfirmButtonEnabled = ko.pureComputed(() => {
            return !this.panelUi.hasValidationErrors();
        });
    }
    getTempUrlBlockSetting() {
        const opt = this.panelUi.settings.getChild('tempUrlBlock');
        if (!opt.isDefined()) {
            throw new Error('tempUrlBlock setting is not defined');
        }
        const tempBlockSetting = opt.get();
        if (tempBlockSetting instanceof _customizables_revised_ui_settings_js__WEBPACK_IMPORTED_MODULE_2__.StructSetting) {
            return tempBlockSetting;
        }
        else {
            throw new Error('Expected tempUrlBlock to be a StructSetting, but it was not: ' + tempBlockSetting);
        }
    }
    onConfirm(event) {
        super.onConfirm(event);
        const blockData = {
            ...this.getTempUrlBlockSetting().value(),
            enabledForActor: {}
        };
        this.add(blockData);
        this.isOpen(false);
    }
    onOpen() {
        //Reset state when the dialog opens.
        this.getTempUrlBlockSetting().updateWithoutValidation({
            title: '',
            urlList: '',
            matchType: 'auto',
            enabledForActor: {}
        });
    }
}
{
    (0,_customizables_revised_ui_component_collection_js__WEBPACK_IMPORTED_MODULE_3__.registerRevisedUiComponents)(ko);
    let isTwmInitialized = false;
    function wsAmeInitTweakManager() {
        if (isTwmInitialized) {
            return;
        }
        const rootNode = document.getElementById('ame-tweak-manager');
        if (!rootNode) {
            return;
        }
        ameTweakManager = new AmeTweakManagerModule(wsTweakManagerData);
        ko.applyBindings(ameTweakManager, rootNode);
        isTwmInitialized = true;
    }
    //We need to initialize the tweak manager as soon as possible so that tweak sections can be
    //targeted by #hash links. However, we also can't do it too early or wp.codeEditor.initialize()
    //will complain that it should be called after the DOM is ready. jQuery.ready() doesn't work
    //because it fires too late and/or asynchronously, but a plain DOMContentLoaded event listener
    //works fine.
    addEventListener('DOMContentLoaded', () => {
        wsAmeInitTweakManager();
    });
}


/***/ },

/***/ "./customizables/revised-ui/component-collection.js"
/*!**********************************************************!*\
  !*** ./customizables/revised-ui/component-collection.js ***!
  \**********************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   registerRevisedUiComponents: () => (/* binding */ registerRevisedUiComponents)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./controls.js */ "./customizables/revised-ui/controls.js");
/* harmony import */ var _components_ame_rev_text_input_ame_rev_text_input_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/ame-rev-text-input/ame-rev-text-input.js */ "./customizables/revised-ui/components/ame-rev-text-input/ame-rev-text-input.js");
/* harmony import */ var _components_ame_foreach_ame_foreach_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/ame-foreach/ame-foreach.js */ "./customizables/revised-ui/components/ame-foreach/ame-foreach.js");
/* harmony import */ var _components_ame_rev_actor_feature_ame_rev_actor_feature_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/ame-rev-actor-feature/ame-rev-actor-feature.js */ "./customizables/revised-ui/components/ame-rev-actor-feature/ame-rev-actor-feature.js");
/* harmony import */ var _components_ame_rev_code_editor_ame_rev_code_editor_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/ame-rev-code-editor/ame-rev-code-editor.js */ "./customizables/revised-ui/components/ame-rev-code-editor/ame-rev-code-editor.js");
/* harmony import */ var _components_ame_rev_sibling_description_ame_rev_sibling_description_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/ame-rev-sibling-description/ame-rev-sibling-description.js */ "./customizables/revised-ui/components/ame-rev-sibling-description/ame-rev-sibling-description.js");
/* harmony import */ var _components_ame_rev_control_group_ame_rev_control_group_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/ame-rev-control-group/ame-rev-control-group.js */ "./customizables/revised-ui/components/ame-rev-control-group/ame-rev-control-group.js");
/* harmony import */ var _components_ame_rev_color_picker_ame_rev_color_picker_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/ame-rev-color-picker/ame-rev-color-picker.js */ "./customizables/revised-ui/components/ame-rev-color-picker/ame-rev-color-picker.js");
/* harmony import */ var _components_ame_rev_postbox_section_ame_rev_postbox_section_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/ame-rev-postbox-section/ame-rev-postbox-section.js */ "./customizables/revised-ui/components/ame-rev-postbox-section/ame-rev-postbox-section.js");
/* harmony import */ var _components_ame_rev_general_structure_ame_rev_general_structure_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./components/ame-rev-general-structure/ame-rev-general-structure.js */ "./customizables/revised-ui/components/ame-rev-general-structure/ame-rev-general-structure.js");
/* harmony import */ var _components_ame_rev_event_button_ame_rev_event_button_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./components/ame-rev-event-button/ame-rev-event-button.js */ "./customizables/revised-ui/components/ame-rev-event-button/ame-rev-event-button.js");
/* harmony import */ var _components_ame_rev_nested_description_ame_rev_nested_description_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./components/ame-rev-nested-description/ame-rev-nested-description.js */ "./customizables/revised-ui/components/ame-rev-nested-description/ame-rev-nested-description.js");
/* harmony import */ var _components_ame_rev_description_ame_rev_description_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./components/ame-rev-description/ame-rev-description.js */ "./customizables/revised-ui/components/ame-rev-description/ame-rev-description.js");
/* harmony import */ var _components_ame_rev_tooltip_trigger_ame_rev_tooltip_trigger_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./components/ame-rev-tooltip-trigger/ame-rev-tooltip-trigger.js */ "./customizables/revised-ui/components/ame-rev-tooltip-trigger/ame-rev-tooltip-trigger.js");
/* harmony import */ var _components_ame_rev_select_box_ame_rev_select_box_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./components/ame-rev-select-box/ame-rev-select-box.js */ "./customizables/revised-ui/components/ame-rev-select-box/ame-rev-select-box.js");
/* harmony import */ var _components_ame_rev_text_area_ame_rev_text_area_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./components/ame-rev-text-area/ame-rev-text-area.js */ "./customizables/revised-ui/components/ame-rev-text-area/ame-rev-text-area.js");
/* harmony import */ var _components_ame_rev_with_ame_rev_with_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./components/ame-rev-with/ame-rev-with.js */ "./customizables/revised-ui/components/ame-rev-with/ame-rev-with.js");
/* harmony import */ var _components_ame_rev_toggle_checkbox_ame_rev_toggle_checkbox_js__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./components/ame-rev-toggle-checkbox/ame-rev-toggle-checkbox.js */ "./customizables/revised-ui/components/ame-rev-toggle-checkbox/ame-rev-toggle-checkbox.js");
/* harmony import */ var _components_ame_rev_static_html_ame_rev_static_html_js__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./components/ame-rev-static-html/ame-rev-static-html.js */ "./customizables/revised-ui/components/ame-rev-static-html/ame-rev-static-html.js");



















//ame:component-imports
function registerRevisedUiComponents(ko) {
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_controls_js__WEBPACK_IMPORTED_MODULE_0__.DebugControl, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_controls_js__WEBPACK_IMPORTED_MODULE_0__.DebugContainer, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_control_group_ame_rev_control_group_js__WEBPACK_IMPORTED_MODULE_6__.AmeRevControlGroup, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_foreach_ame_foreach_js__WEBPACK_IMPORTED_MODULE_2__.AmeForeachBlock, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_postbox_section_ame_rev_postbox_section_js__WEBPACK_IMPORTED_MODULE_8__.AmeRevPostboxSection, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_text_input_ame_rev_text_input_js__WEBPACK_IMPORTED_MODULE_1__.AmeRevTextInput, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_actor_feature_ame_rev_actor_feature_js__WEBPACK_IMPORTED_MODULE_3__.ActorFeatureCheckbox, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_code_editor_ame_rev_code_editor_js__WEBPACK_IMPORTED_MODULE_4__.AmeRevCodeEditor, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_color_picker_ame_rev_color_picker_js__WEBPACK_IMPORTED_MODULE_7__.AmeRevColorPicker, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_sibling_description_ame_rev_sibling_description_js__WEBPACK_IMPORTED_MODULE_5__.AmeRevSiblingDescription, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_general_structure_ame_rev_general_structure_js__WEBPACK_IMPORTED_MODULE_9__.AmeRevGeneralStructure, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_event_button_ame_rev_event_button_js__WEBPACK_IMPORTED_MODULE_10__.AmeRevEventButton, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_nested_description_ame_rev_nested_description_js__WEBPACK_IMPORTED_MODULE_11__.AmeRevNestedDescription, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_description_ame_rev_description_js__WEBPACK_IMPORTED_MODULE_12__.AmeRevDescription, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_tooltip_trigger_ame_rev_tooltip_trigger_js__WEBPACK_IMPORTED_MODULE_13__.AmeRevTooltipTrigger, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_select_box_ame_rev_select_box_js__WEBPACK_IMPORTED_MODULE_14__.AmeRevSelectBox, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_text_area_ame_rev_text_area_js__WEBPACK_IMPORTED_MODULE_15__.AmeRevTextArea, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_with_ame_rev_with_js__WEBPACK_IMPORTED_MODULE_16__.AmeRevWith, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_toggle_checkbox_ame_rev_toggle_checkbox_js__WEBPACK_IMPORTED_MODULE_17__.AmeRevToggleCheckbox, ko);
    (0,_controls_js__WEBPACK_IMPORTED_MODULE_0__.registerKoComponent)(_components_ame_rev_static_html_ame_rev_static_html_js__WEBPACK_IMPORTED_MODULE_18__.AmeRevStaticHtml, ko);
    //ame:component-registrations
}
//# sourceMappingURL=component-collection.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-foreach/ame-foreach.js"
/*!************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-foreach/ame-foreach.js ***!
  \************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeForeachBlock: () => (/* binding */ AmeForeachBlock)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");
/* harmony import */ var _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../shared-dsl/client/wire-dsl.js */ "./shared-dsl/client/wire-dsl.js");


class AmeForeachBlock extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement {
    constructor(params, $element) {
        super(params, $element);
        const inputItems = params.items;
        if (!(0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__.looksLikeSerializedMinimalBinding)(inputItems)) {
            console.error('AmeForeachBlock requires the "items" parameter to be a serialized expression.', inputItems);
            throw new Error('AmeForeachBlock requires the "items" parameter to be a serialized expression.');
        }
        const itemsBinding = this.context.resolve((0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__.unserializeMinimalBinding)(inputItems));
        if (!itemsBinding?.isIterable()) {
            throw new Error('AmeForeachBlock requires the "items" binding to resolve to an iterable collection.');
        }
        const items = itemsBinding.items; //Temp variable for type narrowing.
        if (this.isObservableArray(items)) {
            this._items = items;
        }
        else {
            throw new Error('AmeForeachBlock requires the "items" binding to resolve to an observable array.');
        }
    }
    isObservableArray(value) {
        return (typeof value === 'function')
            && (typeof value.slice === 'function')
            && (typeof value.indexOf === 'function')
            && (ko.isObservable(value));
    }
    get items() {
        return this._items;
    }
}
AmeForeachBlock.componentName = 'ame-foreach';
AmeForeachBlock.template = `
		<!-- ko foreach: {data: items, as: 'dataItem'} -->
			<!-- ko foreach: {data: $component.slots['default']} -->
				<div data-bind="component: $component.generateChildComponent($data, dataItem)"></div>
			<!-- /ko -->
		<!-- /ko -->
	`;
//# sourceMappingURL=ame-foreach.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-actor-feature/ame-rev-actor-feature.js"
/*!********************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-actor-feature/ame-rev-actor-feature.js ***!
  \********************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ActorFeatureCheckbox: () => (/* binding */ ActorFeatureCheckbox)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

const REGISTER_CHILD_PARAM = 'cbRegisterCheckedObservable';
const DEREGISTER_CHILD_PARAM = 'cbDeregisterCheckedObservable';
class ActorFeatureCheckbox extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.childCheckedObservables = ko.observableArray([]);
        this.parentDeregisterCallback = null;
        this.elementClasses.push('ame-actor-feature-checkbox-control');
        this.inputClasses.push('ame-actor-feature-checkbox');
        if (this.id) {
            this.elementId = this.id;
        }
        if (typeof this.bindings['value'] === 'undefined') {
            throw new Error('ActorFeatureCheckbox requires a "value" binding to be defined.');
        }
        const settingValue = this.bindings.value.value;
        const observableMap = new AmeObservableActorFeatureMap(settingValue());
        //Link the observable map and the setting value.
        let isUpdating = false;
        ko.computed(() => observableMap.getAll())
            .extend({ deferred: true })
            .subscribe((newValue) => {
            if (isUpdating) {
                return;
            }
            //Avoid updating the setting if the value hasn't actually changed.
            //This isn't strictly necessary to avoid infinite loops, but it helps prevent some
            //unnecessary updates that isUpdating alone doesn't prevent (likely because of
            //{deferred: true} above).
            const currentExternalValue = settingValue.peek();
            if (wsAmeLodash.isEqual(currentExternalValue, newValue)) {
                return;
            }
            isUpdating = true;
            settingValue(newValue);
            isUpdating = false;
        });
        //Apply changes from the setting to the observable map.
        settingValue.subscribe((externalValue) => {
            if (isUpdating) {
                return;
            }
            isUpdating = true;
            if (externalValue === null) {
                observableMap.resetAll();
            }
            else {
                observableMap.setAll(externalValue);
            }
            isUpdating = false;
        });
        this.featureState = new AmeActorFeatureState(observableMap, this.acquireFeatureStrategy(params));
        this.isChecked = ko.computed({
            read: this.featureState.isEnabled,
            write: (newValue) => {
                this.featureState.isEnabled(newValue);
                //When the user checks or unchecks this checkbox, update all child checkboxes.
                //Note that this only propagates changes from parent to children, not the other way around.
                //The setting represented by this checkbox can be independent of its children, like
                //a parent tweak that hides an entire section + child tweaks that hide individual fields.
                this.childCheckedObservables().forEach((childObservable) => {
                    childObservable(newValue);
                });
            }
        });
        this.isIndeterminate = this.featureState.isIndeterminate;
        //Let children register themselves so that we can update them when the parent checkbox is toggled.
        this.registerChildObservable = (childObservable) => {
            this.childCheckedObservables.push(childObservable);
        };
        this.deregisterChildObservable = (childObservable) => {
            this.childCheckedObservables.remove(childObservable);
        };
        //Register this control with the parent checkbox, if any.
        if (typeof params[REGISTER_CHILD_PARAM] === 'function') {
            params[REGISTER_CHILD_PARAM](this.isChecked);
        }
        if (typeof params[DEREGISTER_CHILD_PARAM] === 'function') {
            this.parentDeregisterCallback = params[DEREGISTER_CHILD_PARAM];
        }
    }
    acquireFeatureStrategy(params) {
        //The strategy can either be passed directly or constructed using an actor selector from
        //the service registry and optional strategy settings.
        if (typeof params['strategy'] !== 'undefined') {
            const strategy = params['strategy'];
            if (!(strategy instanceof AmeActorFeatureStrategy)) {
                throw new Error('AmeActorFeatureCheckbox parameter "strategy" is not a valid AmeActorFeatureStrategy instance.');
            }
            return strategy;
        }
        const actorSelector = this.registry.get('actorSelector');
        if (!(actorSelector instanceof AmeActorSelector)) {
            throw new Error('AmeActorFeatureCheckbox requires a valid AmeActorSelector registered as "actorSelector" in the ServiceRegistry.');
        }
        return new AmeActorFeatureStrategy({
            ...ameUnserializeFeatureStrategySettings(params.strategySettings ?? {}),
            getSelectedActor: actorSelector.getActorObservable(ko),
            getAllActors: () => actorSelector.getVisibleActors()
        });
    }
    generateChildParams(childParams, dataItem) {
        const params = super.generateChildParams(childParams, dataItem);
        params[REGISTER_CHILD_PARAM] = this.registerChildObservable;
        params[DEREGISTER_CHILD_PARAM] = this.deregisterChildObservable;
        return params;
    }
    dispose() {
        //Deregister this control from the parent checkbox, if any.
        if (this.parentDeregisterCallback) {
            this.parentDeregisterCallback(this.isChecked);
        }
        super.dispose();
    }
}
ActorFeatureCheckbox.componentName = 'ame-rev-actor-feature';
ActorFeatureCheckbox.template = `
		<div data-bind="class: classString, attr: elementAttributesMap">
			<label>
				<input type="checkbox" data-bind="checked: isChecked, indeterminate: isIndeterminate, 
					attr: inputAttributesMap, class: inputClassString, enable: isEnabled">
				<span data-bind="text: label"></span>
				${_controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement.tooltipTriggerTemplate}
			</label>
			<!-- ko if: slotHasContent('actions') -->
			<span class="ame-rev-afc-actions">
				${_controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement.slotContentTemplate('actions')}
			</span>
			<!-- /ko -->
			<!-- ko if: (description) -->
				<!-- ko component: {
					name: 'ame-rev-nested-description', 
					params: generateDescriptionParams({includeLineBreak: false})
				} --><!-- /ko -->
			<!-- /ko -->
			${_controls_js__WEBPACK_IMPORTED_MODULE_0__.Control.childrenContainerTemplate}
		</div>
	`;
//# sourceMappingURL=ame-rev-actor-feature.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-choice-control/ame-rev-choice-control.js"
/*!**********************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-choice-control/ame-rev-choice-control.js ***!
  \**********************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevChoiceControl: () => (/* binding */ AmeRevChoiceControl),
/* harmony export */   RevChoiceControlOption: () => (/* binding */ RevChoiceControlOption)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class RevChoiceControlOption {
    constructor(data) {
        this.value = data.value;
        this.label = data.label;
        this.description = data.description || '';
        this.enabled = (typeof data.enabled === 'undefined') || data.enabled;
        this.icon = data.icon || '';
    }
}
class AmeRevChoiceControl extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.options = ko.observableArray([]);
        if ((typeof params['options'] !== 'undefined') && Array.isArray(params.options)) {
            this.options(params.options.map((optionData) => new RevChoiceControlOption(optionData)));
        }
    }
}
AmeRevChoiceControl.componentName = 'ame-rev-choice-control';
AmeRevChoiceControl.template = ``;
//# sourceMappingURL=ame-rev-choice-control.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-code-editor/ame-rev-code-editor.js"
/*!****************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-code-editor/ame-rev-code-editor.js ***!
  \****************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevCodeEditor: () => (/* binding */ AmeRevCodeEditor)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevCodeEditor extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        if ((typeof params.editorSettings === 'object') && (params.editorSettings !== null)) {
            this.editorSettings = params.editorSettings;
        }
        else {
            this.editorSettings = false;
        }
        this.elementClasses.push('ame-code-editor-control-wrap', 'ame-rev-code-editor-control');
    }
}
AmeRevCodeEditor.template = `
		<div data-bind="class: classString"> 
			<textarea data-bind="attr: inputAttributesMap, value: mainBindingValue, 
				class: inputClassString, ameCodeMirror: editorSettings" 
				class="large-text" cols="50" rows="10"></textarea>
		</div>` + _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control.siblingDescriptionTemplate;
AmeRevCodeEditor.componentName = 'ame-rev-code-editor';
//# sourceMappingURL=ame-rev-code-editor.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-color-picker/ame-rev-color-picker.js"
/*!******************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-color-picker/ame-rev-color-picker.js ***!
  \******************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevColorPicker: () => (/* binding */ AmeRevColorPicker)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevColorPicker extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.inputClasses.push('ame-color-picker', 'ame-color-picker-component');
    }
}
//Note: The original version of this component had the input start out as hidden
//and made it visible in koDescendantsComplete(). Unfortunately, I didn't leave
//a comment or commit message explaining why it was implemented like that. If we
//run into issues with this version, try that approach again. It may have been
//necessary to work around some kind of color picker initialization issue.
AmeRevColorPicker.componentName = 'ame-rev-color-picker';
AmeRevColorPicker.template = `
		<input type="text" data-bind="ameColorPicker: mainBindingValue, 
			class: classString, attr: inputAttributesMap">`;
//# sourceMappingURL=ame-rev-color-picker.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-control-group/ame-rev-control-group.js"
/*!********************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-control-group/ame-rev-control-group.js ***!
  \********************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevControlGroup: () => (/* binding */ AmeRevControlGroup)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevControlGroup extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Container {
    constructor(params, $element) {
        super(params, $element);
        this.elementClasses.push('ame-general-control-group', 'ame-rev-control-group');
    }
    get classString() {
        const classes = super.classString;
        if (!this.isTitleVisible) {
            return classes + ' ame-rev-cg-no-title';
        }
        return classes;
    }
}
AmeRevControlGroup.componentName = 'ame-rev-control-group';
AmeRevControlGroup.template = `
		<div data-bind="class: classString">
			<!-- ko if: isTitleVisible -->
				<h4 class="ame-gcg-title ame-rev-cg-title">
					<span data-bind="text: title"></span>
				</h4>
			<!-- /ko -->
			<div class="ame-gcg-children ame-rev-cg-children">
				${_controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement.childrenLoopTemplate}
			</div>
		</div>`;
//# sourceMappingURL=ame-rev-control-group.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-description/ame-rev-description.js"
/*!****************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-description/ame-rev-description.js ***!
  \****************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevDescription: () => (/* binding */ AmeRevDescription)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

/**
 * General description component. It can be used directly, but it's primarily designed
 * to be extended by other components.
 */
class AmeRevDescription extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        if (typeof params.descriptionHtml !== 'undefined') {
            if (typeof params.descriptionHtml === 'string' || ko.isObservable(params.descriptionHtml)) {
                this.descriptionHtml = params.descriptionHtml;
            }
            else {
                throw new Error('descriptionHtml must be a string or a Knockout observable.');
            }
        }
        else {
            this.descriptionHtml = '';
        }
    }
}
AmeRevDescription.componentName = 'ame-rev-description';
AmeRevDescription.template = `<span class="description" data-bind="html: descriptionHtml"></span>`;
//# sourceMappingURL=ame-rev-description.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-event-button/ame-rev-event-button.js"
/*!******************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-event-button/ame-rev-event-button.js ***!
  \******************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevEventButton: () => (/* binding */ AmeRevEventButton)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");
/* harmony import */ var _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../shared-dsl/client/wire-dsl.js */ "./shared-dsl/client/wire-dsl.js");


class AmeRevEventButton extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.eventData = null;
        /**
         * Whether to wrap the button in a <p> tag.
         */
        this.wrap = false;
        this.customLabel = ko.observable('');
        this.customIconClass = ko.observable('');
        this.kind = 'button';
        this.cachedEventTarget = null;
        this.triedToFindEventTarget = false;
        if (typeof params['eventName'] === 'undefined') {
            throw new Error('AmeEventButton requires an "eventName" parameter to be defined.');
        }
        this.eventName = (params['eventName'] + '');
        if (typeof params['wrap'] !== 'undefined') {
            this.wrap = !!(params['wrap']);
        }
        if (typeof params['kind'] !== 'undefined') {
            this.kind = params['kind'] === 'link' ? 'link' : 'button';
        }
        //Not used as of this writing, but could be used in the future for passing
        //additional data to the event handler.
        if (typeof params['eventData'] !== 'undefined') {
            if ((0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__.looksLikeSerializedExpression)(params['eventData'])) {
                this.eventData = (0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__.unserializeExpression)(params['eventData']);
            }
            else {
                this.eventData = new _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__.Literal(params['eventData']);
            }
        }
        this.effectiveLabel = ko.pureComputed(() => {
            return this.customLabel() || this.label();
        });
        this.iconClass = _controls_js__WEBPACK_IMPORTED_MODULE_0__.paramParsers.string(params, 'iconClass', '', this.context);
        this.effectiveIconClass = ko.pureComputed(() => {
            return this.customIconClass() || this.iconClass();
        });
        this.elementClasses.unshift('ame-event-button-control', 'ame-rev-event-button');
        if (this.kind === 'button') {
            this.elementClasses.unshift('button');
        }
        else if (this.kind === 'link') {
            this.elementClasses.push('ame-rev-event-button-link');
        }
        if (this.id) {
            this.elementId = this.id;
        }
    }
    triggerEvent() {
        const eventData = {
            data: (this.eventData ? this.eventData.evaluate(this.context) : null),
            eventButton: this
        };
        this.findEventTarget()?.dispatchEvent(new CustomEvent(this.eventName, {
            detail: eventData,
            bubbles: true,
        }));
    }
    findEventTarget() {
        if (this.triedToFindEventTarget) {
            return this.cachedEventTarget;
        }
        this.triedToFindEventTarget = true;
        const $child = this.findChild('input, button, a, p');
        if ($child.length === 0) {
            throw new Error('AmeRevEventButton could not find its child element to dispatch the event on.');
        }
        this.cachedEventTarget = $child[0];
        return this.cachedEventTarget;
    }
}
AmeRevEventButton.buttonContentTemplate = `<!-- ko if: effectiveIconClass 
			--><span data-bind="css: effectiveIconClass"></span><!-- 
		/ko --><!-- ko if: effectiveLabel 
			--><span data-bind="text: effectiveLabel" class="ame-rev-event-button-label"></span><!-- 
		/ko -->`;
AmeRevEventButton.buttonTemplate = `
		<!-- ko if: kind === 'link' -->
			<a href="#" data-bind="class: classString, enable: isEnabled, click: triggerEvent, attr: elementAttributesMap">
				${AmeRevEventButton.buttonContentTemplate}
			</a>
		<!-- /ko -->
		<!-- ko if: kind === 'button' -->
			<button data-bind="class: classString, enable: isEnabled, click: triggerEvent, attr: elementAttributesMap">
				${AmeRevEventButton.buttonContentTemplate}
			</button>
		<!-- /ko -->
	`;
AmeRevEventButton.componentName = 'ame-rev-event-button';
AmeRevEventButton.template = `
		<!-- ko if: wrap -->
			<p>${AmeRevEventButton.buttonTemplate}</p>
		<!-- /ko -->
		<!-- ko ifnot: wrap -->
			${AmeRevEventButton.buttonTemplate}
		<!-- /ko -->
	`;
//# sourceMappingURL=ame-rev-event-button.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-general-structure/ame-rev-general-structure.js"
/*!****************************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-general-structure/ame-rev-general-structure.js ***!
  \****************************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevGeneralStructure: () => (/* binding */ AmeRevGeneralStructure)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevGeneralStructure extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Container {
    constructor(params, $element) {
        super(params, $element);
    }
}
AmeRevGeneralStructure.componentName = 'ame-rev-general-structure';
AmeRevGeneralStructure.template = _controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement.childrenLoopTemplate;
//# sourceMappingURL=ame-rev-general-structure.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-nested-description/ame-rev-nested-description.js"
/*!******************************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-nested-description/ame-rev-nested-description.js ***!
  \******************************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevNestedDescription: () => (/* binding */ AmeRevNestedDescription)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");
/* harmony import */ var _ame_rev_description_ame_rev_description_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../ame-rev-description/ame-rev-description.js */ "./customizables/revised-ui/components/ame-rev-description/ame-rev-description.js");


class AmeRevNestedDescription extends _ame_rev_description_ame_rev_description_js__WEBPACK_IMPORTED_MODULE_1__.AmeRevDescription {
    constructor(params, $element) {
        super(params, $element);
        this.includeLineBreak = _controls_js__WEBPACK_IMPORTED_MODULE_0__.paramParsers.boolean(params, 'includeLineBreak', true, this.context);
    }
}
AmeRevNestedDescription.componentName = 'ame-rev-nested-description';
AmeRevNestedDescription.template = `
		<!-- ko if: includeLineBreak --><br><!-- /ko --><span class="description" data-bind="html: descriptionHtml"></span>
	`;
//# sourceMappingURL=ame-rev-nested-description.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-postbox-section/ame-rev-postbox-section.js"
/*!************************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-postbox-section/ame-rev-postbox-section.js ***!
  \************************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevPostboxSection: () => (/* binding */ AmeRevPostboxSection)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevPostboxSection extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Container {
    constructor(params, $element) {
        super(params, $element);
        this.elementClasses.push('ws-ame-postbox', 'ame-postbox-section');
        //Optionally, remember the open/closed state of the section.
        if (this.id && this.registry && this.registry.has('collapsibleStateStore')) {
            const collapsibleStateStore = this.registry.get('collapsibleStateStore');
            this.isOpen = collapsibleStateStore.getOrCreateObservable(this.id, true);
        }
        else {
            this.isOpen = ko.observable(true);
        }
        if (this.id) {
            this.elementId = this.id;
        }
        if (this.description() && !this.tooltip) {
            this.tooltip = new _controls_js__WEBPACK_IMPORTED_MODULE_0__.TooltipData(this.description(), 'info');
        }
        if (typeof params.registerSelf === 'function') {
            params.registerSelf(this);
        }
    }
    toggle() {
        this.isOpen(!this.isOpen());
    }
}
AmeRevPostboxSection.componentName = 'ame-rev-postbox-section';
AmeRevPostboxSection.template = `
		<div class="ws-ame-postbox ame-postbox-section" 
			data-bind="css: { 'ws-ame-closed-postbox': !isOpen() }, attr: elementAttributesMap, class: classString">
			<div class="ws-ame-postbox-header">
				<h3>
					<span data-bind="text: title"></span>
					${_controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement.tooltipTriggerTemplate}
				</h3>
				<button class="ws-ame-postbox-toggle" data-bind="click: toggle"></button>
			</div>
			<div class="ws-ame-postbox-content" data-bind="class: childrenContainerClassString">
				<!-- ko foreach: {data: $component.slots['default']} -->
					<div class="ame-postbox-section-child">
					<!-- ko component: $component.generateChildComponent($data) --><!-- /ko -->
					</div>
				<!-- /ko -->			
			</div>
		</div>
	`;
//# sourceMappingURL=ame-rev-postbox-section.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-select-box/ame-rev-select-box.js"
/*!**************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-select-box/ame-rev-select-box.js ***!
  \**************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevSelectBox: () => (/* binding */ AmeRevSelectBox)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");
/* harmony import */ var _ame_rev_choice_control_ame_rev_choice_control_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../ame-rev-choice-control/ame-rev-choice-control.js */ "./customizables/revised-ui/components/ame-rev-choice-control/ame-rev-choice-control.js");


class AmeRevSelectBox extends _ame_rev_choice_control_ame_rev_choice_control_js__WEBPACK_IMPORTED_MODULE_1__.AmeRevChoiceControl {
    constructor(params, $element) {
        super(params, $element);
        this.elementClasses.push('ame-select-box-control', 'ame-rev-select-box');
    }
}
AmeRevSelectBox.componentName = 'ame-rev-select-box';
AmeRevSelectBox.template = `
		<select data-bind="class: classString, value: mainBindingValue, options: options,
			optionsValue: 'value', optionsText: 'label', enable: isEnabled, attr: inputAttributes"></select>
		${_controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement.tooltipTriggerTemplate}
		${_controls_js__WEBPACK_IMPORTED_MODULE_0__.Control.siblingDescriptionTemplate}
	`;
//# sourceMappingURL=ame-rev-select-box.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-sibling-description/ame-rev-sibling-description.js"
/*!********************************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-sibling-description/ame-rev-sibling-description.js ***!
  \********************************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevSiblingDescription: () => (/* binding */ AmeRevSiblingDescription)
/* harmony export */ });
/* harmony import */ var _ame_rev_description_ame_rev_description_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../ame-rev-description/ame-rev-description.js */ "./customizables/revised-ui/components/ame-rev-description/ame-rev-description.js");

class AmeRevSiblingDescription extends _ame_rev_description_ame_rev_description_js__WEBPACK_IMPORTED_MODULE_0__.AmeRevDescription {
}
AmeRevSiblingDescription.componentName = 'ame-rev-sibling-description';
AmeRevSiblingDescription.template = `<p class="description" data-bind="html: descriptionHtml"></p>`;
//# sourceMappingURL=ame-rev-sibling-description.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-static-html/ame-rev-static-html.js"
/*!****************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-static-html/ame-rev-static-html.js ***!
  \****************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevStaticHtml: () => (/* binding */ AmeRevStaticHtml)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevStaticHtml extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.containerType = 'span';
        this.htmlContent = (typeof params.html === 'string') ? params.html : '';
        if (typeof params.container === 'string') {
            this.containerType = params.container;
        }
    }
}
AmeRevStaticHtml.componentName = 'ame-rev-static-html';
AmeRevStaticHtml.template = `
		<!-- ko if: containerType === 'div' -->
			<div data-bind="html: htmlContent"></div>
		<!-- /ko -->
		<!-- ko if: containerType === 'span' -->
			<span data-bind="html: htmlContent"></span>
		<!-- /ko -->
	`;
//# sourceMappingURL=ame-rev-static-html.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-text-area/ame-rev-text-area.js"
/*!************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-text-area/ame-rev-text-area.js ***!
  \************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevTextArea: () => (/* binding */ AmeRevTextArea)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevTextArea extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.elementClasses.push('large-text', 'ame-rev-text-area');
        this.rows = _controls_js__WEBPACK_IMPORTED_MODULE_0__.paramParsers.number(params, 'rows', 0, this.context);
        this.cols = _controls_js__WEBPACK_IMPORTED_MODULE_0__.paramParsers.number(params, 'cols', 0, this.context);
        this.placeholder = _controls_js__WEBPACK_IMPORTED_MODULE_0__.paramParsers.string(params, 'placeholder', '', this.context);
        this.useTextInputBinding = _controls_js__WEBPACK_IMPORTED_MODULE_0__.paramParsers.boolean(params, 'useTextInputBinding', false, this.context);
    }
    getEffectiveInputClasses() {
        const classes = super.getEffectiveInputClasses();
        //Since this control has no wrapper element, input classes also include the overall element classes.
        classes.push(...this.elementClasses);
        return classes;
    }
    get inputAttributesMap() {
        const attributes = super.inputAttributesMap;
        const rows = this.rows();
        const cols = this.cols();
        if (rows > 0) {
            attributes['rows'] = rows.toString();
        }
        if (cols > 0) {
            attributes['cols'] = cols.toString();
        }
        const placeholder = this.placeholder();
        if (placeholder !== '') {
            attributes['placeholder'] = placeholder;
        }
        return attributes;
    }
}
AmeRevTextArea.componentName = 'ame-rev-text-area';
AmeRevTextArea.template = `
		<!-- ko if: useTextInputBinding -->
			<textarea data-bind="textInput: mainBindingValue, attr: inputAttributesMap, class: inputClassString"></textarea>
		<!-- /ko --><!-- ko ifnot: useTextInputBinding -->
			<textarea data-bind="value: mainBindingValue, attr: inputAttributesMap, class: inputClassString"></textarea>
		<!-- /ko -->
		${_controls_js__WEBPACK_IMPORTED_MODULE_0__.Control.siblingDescriptionTemplate}
	`;
//# sourceMappingURL=ame-rev-text-area.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-text-input/ame-rev-text-input.js"
/*!**************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-text-input/ame-rev-text-input.js ***!
  \**************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevTextInput: () => (/* binding */ AmeRevTextInput)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevTextInput extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.useTextInputBinding = false;
        this.isCode = !!(params.isCode || false);
        this.inputType = (params.inputType || 'text') + '';
        this.useTextInputBinding = !!(params.useTextInputBinding || false);
        this.inputAttributes['type'] = this.inputType;
        const autoInputClasses = [];
        if (!this.inputClasses.includes('large-text')) {
            autoInputClasses.push('regular-text');
        }
        if (this.isCode) {
            autoInputClasses.push('code');
        }
        autoInputClasses.push('ame-text-input-control', 'ame-rev-text-input-control');
        this.inputClasses = [...autoInputClasses, ...this.inputClasses];
    }
}
AmeRevTextInput.template = `
		<!-- ko if: useTextInputBinding -->
			<input data-bind="textInput: mainBindingValue, class: inputClassString, attr: inputAttributesMap">
		<!-- /ko --><!-- ko ifnot: useTextInputBinding -->
			<input data-bind="value: mainBindingValue, class: inputClassString, attr: inputAttributesMap">
		<!-- /ko -->
`;
AmeRevTextInput.componentName = 'ame-rev-text-input';
//# sourceMappingURL=ame-rev-text-input.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-toggle-checkbox/ame-rev-toggle-checkbox.js"
/*!************************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-toggle-checkbox/ame-rev-toggle-checkbox.js ***!
  \************************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevToggleCheckbox: () => (/* binding */ AmeRevToggleCheckbox)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevToggleCheckbox extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        this.onValue = (typeof params.onValue !== 'undefined') ? params.onValue : true;
        this.offValue = (typeof params.offValue !== 'undefined') ? params.offValue : false;
        this.isChecked = ko.pureComputed({
            read: () => {
                return this.mainBindingValue() === ko.unwrap(this.onValue);
            },
            write: (newValue) => {
                this.mainBindingValue(ko.unwrap(newValue ? this.onValue : this.offValue));
            },
            deferEvaluation: true
        });
        this.elementClasses.unshift('ame-toggle-checkbox-control', 'ame-rev-toggle-checkbox');
    }
}
AmeRevToggleCheckbox.componentName = 'ame-rev-toggle-checkbox';
AmeRevToggleCheckbox.template = `
		<label data-bind="class: classString">
			<input type="checkbox" data-bind="checked: isChecked, attr: inputAttributesMap, 
				class: inputClassString, enable: isEnabled">
			<span data-bind="text: label"></span>
			${_controls_js__WEBPACK_IMPORTED_MODULE_0__.Control.nestedDescriptionTemplate}
		</label>
		${_controls_js__WEBPACK_IMPORTED_MODULE_0__.Control.childrenContainerTemplate}	
	`;
//# sourceMappingURL=ame-rev-toggle-checkbox.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-tooltip-trigger/ame-rev-tooltip-trigger.js"
/*!************************************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-tooltip-trigger/ame-rev-tooltip-trigger.js ***!
  \************************************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevTooltipTrigger: () => (/* binding */ AmeRevTooltipTrigger)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");

class AmeRevTooltipTrigger extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.Control {
    constructor(params, $element) {
        super(params, $element);
        if ((typeof params.tooltip === 'undefined') || (params.tooltip === null)) {
            throw new Error('AmeRevTooltipTrigger requires a "tooltip" parameter.');
        }
        this.tooltip = params.tooltip;
        this.text = this.tooltip.htmlContent || '';
        //Convert newlines to <br> for better formatting in tooltips.
        //Some other parts of the plugin rely on the implicit conversion of newlines to <br>
        //that qTip2 apparently does when reading the title attribute, but this component
        //doesn't use the title attribute.
        this.text = this.text.replace(/\n/g, '<br>');
    }
}
AmeRevTooltipTrigger.componentName = 'ame-rev-tooltip-trigger';
AmeRevTooltipTrigger.template = `
		<a class="ws_tooltip_trigger ame-rev-tooltip-trigger"
			data-bind="ameTooltip: {text: text}"><span class="dashicons dashicons-info"></span></a>
	`;
//# sourceMappingURL=ame-rev-tooltip-trigger.js.map

/***/ },

/***/ "./customizables/revised-ui/components/ame-rev-with/ame-rev-with.js"
/*!**************************************************************************!*\
  !*** ./customizables/revised-ui/components/ame-rev-with/ame-rev-with.js ***!
  \**************************************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AmeRevWith: () => (/* binding */ AmeRevWith)
/* harmony export */ });
/* harmony import */ var _controls_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../controls.js */ "./customizables/revised-ui/controls.js");
/* harmony import */ var _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../shared-dsl/client/wire-dsl.js */ "./shared-dsl/client/wire-dsl.js");


class AmeRevWith extends _controls_js__WEBPACK_IMPORTED_MODULE_0__.UiElement {
    constructor(params, $element) {
        super(params, $element);
        const inputItem = params.item;
        if (!(0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__.looksLikeSerializedMinimalBinding)(inputItem)) {
            console.error('AmeRevWith requires the "item" parameter to be a serialized expression.', inputItem);
            throw new Error('AmeRevWith requires the "item" parameter to be a serialized expression.');
        }
        const itemBinding = this.context.resolve((0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_1__.unserializeMinimalBinding)(inputItem));
        if (!itemBinding) {
            throw new Error('AmeRevWith requires the "item" binding to resolve to a valid value.');
        }
        this.item = itemBinding;
    }
}
AmeRevWith.componentName = 'ame-rev-with';
AmeRevWith.template = `
		<!-- ko foreach: {data: $component.slots['default']} -->
			<!-- ko component: $component.generateChildComponent($data, $component.item) -->
		<!-- /ko -->
	`;
//# sourceMappingURL=ame-rev-with.js.map

/***/ },

/***/ "./customizables/revised-ui/controls.js"
/*!**********************************************!*\
  !*** ./customizables/revised-ui/controls.js ***!
  \**********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Container: () => (/* binding */ Container),
/* harmony export */   Control: () => (/* binding */ Control),
/* harmony export */   DebugContainer: () => (/* binding */ DebugContainer),
/* harmony export */   DebugControl: () => (/* binding */ DebugControl),
/* harmony export */   TooltipData: () => (/* binding */ TooltipData),
/* harmony export */   UiElement: () => (/* binding */ UiElement),
/* harmony export */   paramParsers: () => (/* binding */ paramParsers),
/* harmony export */   registerKoComponent: () => (/* binding */ registerKoComponent)
/* harmony export */ });
/* harmony import */ var _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../shared-dsl/client/wire-dsl.js */ "./shared-dsl/client/wire-dsl.js");

//region Elements
class UiElement {
    constructor(params, $element) {
        this.$element = $element;
        this.customizer = null;
        this.slots = {};
        this.id = '';
        /**
         * The HTML ID attribute of the root element of this component.
         *
         * Note: This can be different from the "id" parameter passed to the component.
         * Empty by default, but can be set by subclasses. That's also why it's not readonly.
         */
        this.elementId = '';
        this.elementClasses = [];
        this.tooltip = null;
        this.registry = params.registry;
        this.isBoundToComment = ($element[0]) && ($element[0].nodeType === Node.COMMENT_NODE);
        if (typeof params.slots === 'object' && (params.slots !== null)) {
            for (const [slotName, slotChildren] of Object.entries(params.slots)) {
                if (Array.isArray(slotChildren)) {
                    this.slots[slotName] = ko.observableArray(slotChildren);
                }
            }
            //Always initialize the default slot, even if no children were provided for it.
            //This is to simplify template code.
            if (!this.slots.hasOwnProperty('default')) {
                this.slots['default'] = ko.observableArray([]);
            }
        }
        if ((typeof params.context === 'object') && (params.context !== null)) {
            this.context = params.context;
        }
        else {
            this.context = new _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_0__.Context();
        }
        if ((typeof params.customizer === 'function')) {
            this.customizer = params.customizer;
        }
        if (typeof params.classes === 'object' && Array.isArray(params.classes)) {
            this.elementClasses.push(...params.classes);
        }
        if (typeof params.id === 'string') {
            this.id = params.id;
        }
        this.tooltip = TooltipData.fromComponentParams(params);
        this.description = paramParsers.string(params, 'description', '', this.context);
    }
    getEffectiveElementClasses() {
        return this.elementClasses;
    }
    // noinspection JSUnusedGlobalSymbols -- Used in Knockout templates.
    get classString() {
        return this.getEffectiveElementClasses().join(' ');
    }
    // noinspection JSUnusedGlobalSymbols -- Used in Knockout templates.
    get elementAttributesMap() {
        const attributes = {};
        if (this.elementId !== '') {
            attributes['id'] = this.elementId;
        }
        return attributes;
    }
    dispose() {
        //Override in subclasses to clean up event handlers, subscriptions, etc.
    }
    chooseChildComponent(childParams) {
        if (childParams.component && (ko.components.isRegistered(childParams.component))) {
            return childParams.component;
        }
        switch (childParams.t) {
            case 'control':
                return 'ame-rev-debug-control';
            case 'container':
                if (childParams.role === 'group') {
                    return 'ame-rev-control-group';
                }
                return 'ame-rev-debug-container';
            case 'foreach':
                return 'ame-foreach';
            case 'with':
                return 'ame-rev-with';
            default:
                return 'ame-rev-debug-control';
        }
    }
    generateChildParams(childParams, dataItem) {
        const childDataItem = dataItem || this.getDefaultDataItemForChildren();
        const params = {
            ...childParams,
            registry: this.registry,
            customizer: this.customizer,
            context: this.context.createChildContext(childDataItem),
        };
        if (this.customizer) {
            return this.customizer(params);
        }
        return params;
    }
    // noinspection JSUnusedGlobalSymbols -- Used in Knockout templates.
    generateChildComponent(childParams, dataItem) {
        const params = this.generateChildParams(childParams, dataItem);
        const name = this.chooseChildComponent(params);
        if (!name) {
            throw new Error(`Could not determine component name for child params: ${JSON.stringify(params)}`);
        }
        return { name, params };
    }
    getDefaultDataItemForChildren() {
        //By default, children inherit the same data item as their parent. This helps ensure that
        //you can nest controls inside containers and still use $item bindings in those controls.
        //
        //Loops should provide the current item to their children directly.
        const currentItem = this.context.currentItem;
        if (currentItem && currentItem.isWritable()) {
            return currentItem;
        }
        return null;
    }
    findChild(selector, allowSiblingSearch = null) {
        if (allowSiblingSearch === null) {
            //Enable only if the component is bound to a comment (i.e. "<!-- ko component: ... -->").
            allowSiblingSearch = this.isBoundToComment;
        }
        if (this.isBoundToComment) {
            if (allowSiblingSearch) {
                return this.$element.nextAll(selector).first();
            }
            else {
                //We would never find anything because a comment node has no children.
                return jQuery();
            }
        }
        return this.$element.find(selector);
    }
    slotHasContent(slotName = 'default') {
        if (!this.slots.hasOwnProperty(slotName)) {
            return false;
        }
        const items = this.slots[slotName]();
        return (items.length > 0);
    }
    static slotContentTemplate(slotName = 'default') {
        return `
			<!-- ko if: ($component.slotHasContent('${slotName}')) -->
				<!-- ko foreach: {data: $component.slots['${slotName}']} -->
					<!-- ko component: $component.generateChildComponent($data) --><!-- /ko -->
				<!-- /ko -->
			<!-- /ko -->`;
    }
    // noinspection JSUnusedGlobalSymbols -- Used in the tooltip trigger template below.
    generateTooltipParams(extraParams = {}) {
        if (!this.tooltip) {
            throw new Error('Cannot generate tooltip params because this component has no tooltip.');
        }
        return {
            t: 'control',
            registry: this.registry,
            context: this.context,
            tooltip: this.tooltip,
            ...extraParams
        };
    }
}
UiElement.childrenLoopTemplate = `
		<!-- ko foreach: {data: $component.slots['default']} -->
			<!-- ko component: $component.generateChildComponent($data) --><!-- /ko -->
		<!-- /ko -->`;
UiElement.tooltipTriggerTemplate = `
		<!-- ko if: tooltip -->
			<!-- ko component: {name: 'ame-rev-tooltip-trigger', params: $component.generateTooltipParams()} --><!-- /ko -->
		<!-- /ko -->`;
class Control extends UiElement {
    constructor(params, $element) {
        super(params, $element);
        this.bindings = {};
        this.inputClasses = [];
        this.inputAttributes = {};
        if ((typeof params.bindings === 'object') && (params.bindings !== null)) {
            for (const [bindingName, bindingValue] of Object.entries(params.bindings)) {
                const ref = (0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_0__.unserializeMinimalBinding)(bindingValue);
                const resolved = this.context.resolve(ref);
                if (!resolved) {
                    throw new Error(`Control: Could not resolve binding "${bindingName}" with value ${JSON.stringify(bindingValue)}.`);
                }
                if (!resolved.isWritable()) {
                    throw new Error(`Control: Binding "${bindingName}" resolved to a non-writable binding.`);
                }
                this.bindings[bindingName] = resolved;
            }
        }
        if (this.bindings.value) {
            this.mainBindingValue = this.bindings.value.value;
        }
        else {
            this.mainBindingValue = ko.observable(null);
        }
        this.label = paramParsers.string(params, 'label', '', this.context);
        this.isEnabled = paramParsers.boolean(params, 'enabled', true, this.context);
        if (typeof params.inputClasses === 'object' && Array.isArray(params.inputClasses)) {
            this.inputClasses.push(...params.inputClasses);
        }
        if ((typeof params.inputAttributes === 'object') && (params.inputAttributes !== null)) {
            for (const [attrName, attrValue] of Object.entries(params.inputAttributes)) {
                this.inputAttributes[attrName] = attrValue + '';
            }
        }
    }
    getEffectiveInputClasses() {
        return this.inputClasses;
    }
    // noinspection JSUnusedGlobalSymbols -- Used in Knockout templates.
    get inputClassString() {
        return this.getEffectiveInputClasses().join(' ');
    }
    // noinspection JSUnusedGlobalSymbols -- Used in Knockout templates.
    get inputAttributesMap() {
        return this.inputAttributes;
    }
    // noinspection JSUnusedGlobalSymbols -- Used in the sibling description template above.
    generateDescriptionParams(extraParams = {}) {
        return {
            t: 'control',
            registry: this.registry,
            descriptionHtml: this.description,
            ...extraParams
        };
    }
}
Control.siblingDescriptionTemplate = `
		<!-- ko if: (description) -->
			<!-- ko component: {name: 'ame-rev-sibling-description', params: generateDescriptionParams()} --><!-- /ko -->
		<!-- /ko -->`;
Control.nestedDescriptionTemplate = `
		<!-- ko if: (description) -->
			<!-- ko component: {
				name: 'ame-rev-nested-description', 
				params: generateDescriptionParams({includeLineBreak: false})
			} --><!-- /ko -->
		<!-- /ko -->`;
Control.childrenContainerTemplate = `
		<!-- ko if: (slots['default'] && (slots['default']().length > 0)) -->
			<div class="ame-general-control-children">
				<!-- ko foreach: {data: $component.slots['default']} -->    
					<div class="ame-control-child">
					<!-- ko component: $component.generateChildComponent($data) --><!-- /ko -->
					</div>
				<!-- /ko -->
			</div>
		<!-- /ko -->`;
class Container extends UiElement {
    constructor(params, $element) {
        super(params, $element);
        this.title = paramParsers.string(params, 'title', '', this.context);
        this.isTitleDisabled = paramParsers.boolean(params, 'titleDisabled', false, this.context);
        this.childrenContainerClasses = paramParsers.array(params, 'childrenContainerClasses', [], this.context);
    }
    // noinspection JSUnusedGlobalSymbols -- Used in Knockout templates.
    get childrenContainerClassString() {
        return this.childrenContainerClasses().join(' ');
    }
    // noinspection JSUnusedGlobalSymbols -- Used in Knockout templates.
    /**
     * Should the title of this container be displayed?
     */
    get isTitleVisible() {
        const titleValue = this.title();
        return ((typeof titleValue === 'string') && (titleValue.trim() !== '') && !this.isTitleDisabled());
    }
}
const paramTypeSpecs = {
    string: {
        typeName: 'string',
        coerceExpr: (v) => v + '',
        label: 'string',
    },
    boolean: {
        typeName: 'boolean',
        coerceExpr: (v) => !!v,
        label: 'boolean',
    },
    number: {
        typeName: 'number',
        coerceExpr: (v) => +v,
        label: 'number',
    },
    array: {
        typeName: 'object', // typeof [] === 'object', see isPlainValue below
        coerceExpr: (v) => (Array.isArray(v) ? v : [v]),
        label: 'array',
    },
};
function makeParamParser(spec, isPlainValue) {
    return (params, name, defaultValue, ctx) => {
        const value = params[name];
        // Already an observable → reuse it directly.
        if (ko.isObservable(value)) {
            return value;
        }
        if (isPlainValue(value)) {
            return ko.pureComputed(() => params[name]);
        }
        if ((0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_0__.looksLikeSerializedExpression)(value)) {
            const expr = (0,_shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_0__.unserializeExpression)(value);
            return ko.pureComputed(() => spec.coerceExpr(expr.evaluate(ctx)));
        }
        if (value === undefined) {
            return ko.pureComputed(() => defaultValue);
        }
        throw new Error(`Invalid parameter type for "${name}". Expected ${spec.label} or expression, got ${typeof value}.`);
    };
}
const paramParsers = {
    string: makeParamParser(paramTypeSpecs.string, (v) => typeof v === 'string'),
    boolean: makeParamParser(paramTypeSpecs.boolean, (v) => typeof v === 'boolean'),
    number: makeParamParser(paramTypeSpecs.number, (v) => typeof v === 'number'),
    array: makeParamParser(paramTypeSpecs.array, (v) => Array.isArray(v)),
};
function registerKoComponent(ctor, koInstance) {
    koInstance = koInstance || ko;
    const componentName = ctor.componentName;
    //Catch classes that forget to define a template or component name. We can't enforce
    //this with TypeScript because static properties can't be abstract.
    if (!componentName || componentName.trim() === '') {
        throw new Error(`Cannot register component with empty component name.`);
    }
    if (ctor.template.trim() === '') {
        throw new Error(`Cannot register component ${componentName} with empty template.`);
    }
    koInstance.components.register(componentName, {
        viewModel: {
            createViewModel: function (params, componentInfo) {
                const $element = jQuery(componentInfo.element);
                return new ctor(params, $element);
            }
        },
        template: ctor.template,
    });
}
//endregion
//region Tooltips
class TooltipData {
    constructor(htmlContent, type = 'info', extraClasses = []) {
        this.htmlContent = htmlContent;
        this.type = type;
        this.extraClasses = extraClasses;
    }
    static fromComponentParams(params) {
        if (typeof params.tooltip === 'object' && params.tooltip !== null) {
            const tooltipParams = params.tooltip;
            const htmlContent = (typeof tooltipParams.htmlContent === 'string')
                ? tooltipParams.htmlContent
                : '';
            const type = (tooltipParams.type === 'experimental') ? 'experimental' : 'info';
            const extraClasses = (Array.isArray(tooltipParams.extraClasses))
                ? tooltipParams.extraClasses.map(String)
                : [];
            return new TooltipData(htmlContent, type, extraClasses);
        }
        return null;
    }
}
//endregion
//region Debug components
class DebugParamsRenderer {
    constructor(paramsToShow) {
        const { registry, children, context, ...debugParams } = paramsToShow;
        const output = this.renderJsonTree(ko.toJS(debugParams)); //toJS unwraps nested observables
        //Not live because re-rendering the entire JSON tree on every change is very expensive.
        //Also, since components typically have a reference to the root setting, a change in any
        //setting will trigger a re-render of all debug components, which is not ideal.
        this.html = ko.pureComputed(() => output);
    }
    escapeHtml(s) {
        return s.replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
    }
    renderJsonTree(value, key = null, depth = 0) {
        const label = key !== null
            ? `<span class="json-key">${this.escapeHtml(key)}:</span> `
            : '';
        if (value === null || typeof value !== 'object') {
            const text = (value === undefined) ? 'undefined'
                : (typeof value === 'function') ? '[Function]'
                    : JSON.stringify(value);
            if (typeof text === 'undefined') {
                console.log('Value is undefined for key:', key, 'value:', value, typeof value);
                throw new Error(`Unexpected undefined text value for key "${key}"`);
            }
            return `<div class="json-leaf">${label}<span class="json-value">${this.escapeHtml(text)}</span></div>`;
        }
        const isArray = Array.isArray(value);
        const entries = Object.entries(value);
        const preview = isArray ? `Array(${entries.length})` : `Object {${entries.length}}`;
        // Top level starts open, everything below starts collapsed:
        const open = depth === 0 ? ' open' : '';
        return `<details class="json-node"${open}>
		<summary>${label}<span class="json-preview">${this.escapeHtml(preview)}</span></summary>
		<div class="json-children">
			${entries.map(([k, v]) => this.renderJsonTree(v, k, depth + 1)).join('')}
		</div>
	</details>`;
    }
}
const DEBUG_PARAMS_RENDERER_TEMPLATE = `
	<div class="ame-rev-debug-tree" data-bind="html: debugTree.html"></div>
`;
class DebugContainer extends Container {
    constructor(params, $element) {
        super(params, $element);
        this.debugTree = new DebugParamsRenderer(params);
    }
}
DebugContainer.componentName = 'ame-rev-debug-container';
DebugContainer.template = `
		<div class="ame-rev-debug-container">
			<h3><span data-bind="text: title"></span>  <span class="ame-rev-debug-component-flag">[Debug Container]</span></h3>
			${DEBUG_PARAMS_RENDERER_TEMPLATE}
			<h4>Container Children</h4>
			<ol data-bind="foreach: slots['default']">
				<li>
					<div data-bind="component: $component.generateChildComponent($data)"></div>
				</li>
			</ol>
		</div>`;
class DebugControl extends Control {
    constructor(params, $element) {
        super(params, $element);
        this.debugTree = new DebugParamsRenderer(params);
    }
}
DebugControl.componentName = 'ame-rev-debug-control';
DebugControl.template = `
		<div class="ame-rev-debug-control">
			<h3><span data-bind="text: label"></span> <span class="ame-rev-debug-component-flag">[Debug Control]</span></h3>
			${DEBUG_PARAMS_RENDERER_TEMPLATE}
			<!-- ko if: slots['default'] && (slots['default']().length > 0) -->
			<h4>Control Children</h4>
			<ol data-bind="foreach: slots['default']">
				<li>
					<div data-bind="component: $component.generateChildComponent($data)"></div>
				</li>
			</ol>
			<!-- /ko -->
		</div>`;
//endregion
//# sourceMappingURL=controls.js.map

/***/ },

/***/ "./customizables/revised-ui/registry.js"
/*!**********************************************!*\
  !*** ./customizables/revised-ui/registry.js ***!
  \**********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ServiceRegistry: () => (/* binding */ ServiceRegistry)
/* harmony export */ });
//This is unfortunately a duplicate of the old implementation in customizable.ts. Consider refactoring
//that to use this implementation once the revised UI is more stable.
class ServiceRegistry {
    constructor(registry) {
        this.registry = registry;
    }
    register(key, service) {
        //Add service to registry and return the same object with a narrowed type.
        this.registry[key] = service;
        return this;
    }
    get(key) {
        if (!(key in this.registry)) {
            throw new Error('Invalid service key: ' + String(key));
        }
        return this.registry[key];
    }
    has(key) {
        return key in this.registry;
    }
    static init() {
        return new ServiceRegistry({});
    }
}
//# sourceMappingURL=registry.js.map

/***/ },

/***/ "./customizables/revised-ui/schemas.js"
/*!*********************************************!*\
  !*** ./customizables/revised-ui/schemas.js ***!
  \*********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ArraySchema: () => (/* binding */ ArraySchema),
/* harmony export */   ParseError: () => (/* binding */ ParseError),
/* harmony export */   RecordSchema: () => (/* binding */ RecordSchema),
/* harmony export */   Schema: () => (/* binding */ Schema),
/* harmony export */   SchemaDeserializer: () => (/* binding */ SchemaDeserializer),
/* harmony export */   StructSchema: () => (/* binding */ StructSchema)
/* harmony export */ });

//region Schemas
var Either = AmeMiniFunc.Either;
var none = AmeMiniFunc.none;
var some = AmeMiniFunc.some;
class Schema {
    /**
     * Determines if the given value is considered "empty" for this schema.
     */
    isEmptyValue(value) {
        return (value === null) || (typeof value === 'undefined');
    }
    hasNonEmptyDefaultValue() {
        return this.defaultValue.map(value => !this.isEmptyValue(value)).getOrElse(() => false);
    }
}
class BooleanSchema extends Schema {
    constructor() {
        super(...arguments);
        this.defaultValue = none;
    }
    safeParse(value, path = []) {
        if (typeof value === 'boolean') {
            return { success: true, value };
        }
        else {
            return {
                success: false,
                error: ParseError.fromMessage(`Expected boolean, got ${typeof value}`, 'type_error', path)
            };
        }
    }
}
class CheckableSchema extends Schema {
    constructor(checks = []) {
        super();
        this.checks = checks;
    }
    applyChecks(value, path = []) {
        let convertedValue = value;
        const issues = [];
        for (const check of this.checks) {
            const checkFunction = this.getCheckFunction(check.kind);
            if (!checkFunction) {
                issues.push(new ParseIssue(`Unknown check kind: ${check.kind}`, 'unknown_check', path));
                continue;
            }
            const checkResult = checkFunction(convertedValue, check.value);
            if (checkResult.isLeft()) {
                issues.push(checkResult.value);
            }
            else if (checkResult.isRight()) {
                convertedValue = checkResult.value;
            }
        }
        if (issues.length > 0) {
            return { success: false, error: new ParseError(issues) };
        }
        else {
            return { success: true, value: convertedValue };
        }
    }
}
class StringSchema extends CheckableSchema {
    constructor(checks = []) {
        super(checks);
        this.defaultValue = none;
    }
    safeParse(value, path = []) {
        if (typeof value !== 'string') {
            return {
                success: false,
                error: ParseError.fromMessage(`Expected string, got ${typeof value}`, 'type_error', path)
            };
        }
        return this.applyChecks(value, path);
    }
    isEmptyValue(value) {
        return (value === '') || super.isEmptyValue(value);
    }
    getCheckFunction(kind) {
        return builtInStringChecks[kind];
    }
}
class EnumSchema extends Schema {
    constructor(values) {
        super();
        this.values = values;
        this.defaultValue = none;
    }
    safeParse(value, path = []) {
        if (this.values.includes(value)) {
            return { success: true, value };
        }
        else {
            return {
                success: false,
                error: ParseError.fromMessage(`Expected one of ${JSON.stringify(this.values)}, got ${JSON.stringify(value)}`, 'type_error', path)
            };
        }
    }
}
class NumberSchema extends CheckableSchema {
    constructor(checks = []) {
        super(checks);
        this.defaultValue = none;
    }
    safeParse(value, path = []) {
        //Convert numeric strings to numbers.
        if (typeof value === 'string') {
            const parsedValue = parseFloat(value);
            if (!isNaN(parsedValue)) {
                value = parsedValue;
            }
        }
        if (typeof value !== 'number') {
            return {
                success: false,
                error: ParseError.fromMessage(`Expected number, got ${typeof value}`, 'type_error', path)
            };
        }
        return this.applyChecks(value, path);
    }
    getCheckFunction(kind) {
        return builtinNumberChecks[kind];
    }
}
class StructSchema extends Schema {
    constructor(fieldSchemas, requiredFields = []) {
        super();
        this.fieldSchemas = fieldSchemas;
        this.requiredFields = requiredFields;
        this.defaultValue = none;
    }
    safeParse(value, path = [], skipChildren = false) {
        if (typeof value !== 'object' || value === null) {
            return {
                success: false,
                error: ParseError.fromMessage(`Expected object, got ${typeof value}`, 'type_error', path)
            };
        }
        const resultObject = {};
        const issues = [];
        for (const property of this.requiredFields) {
            if (!value.hasOwnProperty(property)) {
                issues.push(new ParseIssue(`Missing required property: ${property}`, 'missing_property', [...path, property]));
            }
        }
        if (!skipChildren) {
            for (const [property, schema] of Object.entries(this.fieldSchemas)) {
                if (value.hasOwnProperty(property)) {
                    const parseResult = schema.safeParse(value[property], [...path, property]);
                    if (parseResult.success) {
                        resultObject[property] = parseResult.value;
                    }
                    else {
                        issues.push(new ParseIssue(`Error parsing property "${property}": ${parseResult.error.issues.map(issue => issue.message).join('; ')}`, 'property_parse_error', [...path, property]));
                    }
                }
            }
        }
        if (issues.length > 0) {
            return { success: false, error: new ParseError(issues) };
        }
        else {
            return { success: true, value: resultObject };
        }
    }
    isFieldRequired(fieldName) {
        return this.requiredFields.includes(fieldName);
    }
    isEmptyValue(value) {
        if (super.isEmptyValue(value)) {
            return true;
        }
        return isEmptyObject(value);
    }
}
class RecordSchema extends Schema {
    constructor(keySchema, valueSchema, defaultValue = none) {
        super();
        this.keySchema = keySchema;
        this.valueSchema = valueSchema;
        this.defaultValue = defaultValue;
    }
    safeParse(value, path = []) {
        if (typeof value !== 'object' || value === null) {
            return {
                success: false,
                error: ParseError.fromMessage(`Expected object, got ${typeof value}`, 'type_error', path)
            };
        }
        const resultObject = {};
        const issues = [];
        for (const [key, val] of Object.entries(value)) {
            const keyParseResult = this.keySchema.safeParse(key);
            if (!keyParseResult.success) {
                issues.push(new ParseIssue(`Error parsing key "${key}": ${keyParseResult.error.issues.map(issue => issue.message).join('; ')}`, 'key_parse_error', [...path, key]));
                continue;
            }
            const valueParseResult = this.valueSchema.safeParse(val);
            if (!valueParseResult.success) {
                issues.push(new ParseIssue(`Error parsing value for key "${key}": ${valueParseResult.error.issues.map(issue => issue.message).join('; ')}`, 'value_parse_error', [...path, key]));
                continue;
            }
            resultObject[key] = valueParseResult.value;
        }
        if (issues.length > 0) {
            return { success: false, error: new ParseError(issues) };
        }
        else {
            return { success: true, value: resultObject };
        }
    }
    isEmptyValue(value) {
        if (super.isEmptyValue(value)) {
            return true;
        }
        return isEmptyObject(value);
    }
}
class ArraySchema extends Schema {
    constructor(itemSchema, defaultValue = none) {
        super();
        this.itemSchema = itemSchema;
        this.defaultValue = defaultValue;
    }
    safeParse(value, path = []) {
        if (!Array.isArray(value)) {
            return {
                success: false,
                error: ParseError.fromMessage(`Expected array, got ${typeof value}`, 'type_error', path)
            };
        }
        const resultArray = [];
        const issues = [];
        for (let index = 0; index < value.length; index++) {
            const item = value[index];
            const parseResult = this.itemSchema.safeParse(item);
            if (parseResult.success) {
                resultArray.push(parseResult.value);
            }
            else {
                issues.push(new ParseIssue(`Error parsing item at index ${index}: ${parseResult.error.issues.map(issue => issue.message).join('; ')}`, 'item_parse_error', [...path, index]));
            }
        }
        if (issues.length > 0) {
            return { success: false, error: new ParseError(issues) };
        }
        else {
            return { success: true, value: resultArray };
        }
    }
    isEmptyValue(value) {
        if (super.isEmptyValue(value)) {
            return true;
        }
        if (Array.isArray(value)) {
            return value.length === 0;
        }
        return false;
    }
}
class ColorSchema extends StringSchema {
    safeParse(value, path = []) {
        const result = super.safeParse(value, path);
        if (!result.success) {
            return result;
        }
        //TODO: Color validation logic (e.g., regex for hex colors, named colors, etc.)
        return { success: true, value: result.value };
    }
}
function isEmptyObject(obj) {
    if (typeof obj !== 'object' || obj === null) {
        return false;
    }
    return Object.keys(obj).length === 0;
}
class ParseError {
    constructor(issues) {
        this.issues = issues;
    }
    copyWithPrependedPath(newPath) {
        const newIssues = this.issues.map(issue => new ParseIssue(issue.message, issue.code, [...newPath, ...issue.path]));
        return new ParseError(newIssues);
    }
    static fromMessage(message, code, path = []) {
        return new ParseError([new ParseIssue(message, code, path)]);
    }
}
class ParseIssue {
    constructor(message, code, path = []) {
        this.message = message;
        this.code = code;
        this.path = path;
    }
}
class SchemaDeserializer {
    constructor(serializedSharedSchemas = {}) {
        this.serializedSharedSchemas = serializedSharedSchemas;
        this.sharedSchemas = {};
    }
    deserialize(serialized) {
        if ('_ref' in serialized) {
            const ref = serialized._ref;
            if (ref in this.sharedSchemas) {
                return this.sharedSchemas[ref];
            }
            //Deserialize the shared schema on demand.
            if (ref in this.serializedSharedSchemas) {
                this.sharedSchemas[ref] = this.deserialize(this.serializedSharedSchemas[ref]);
                return this.sharedSchemas[ref];
            }
            throw new Error(`Referenced schema "${ref}" not found in shared schemas.`);
        }
        switch (serialized.type) {
            case 'boolean':
                return new BooleanSchema();
            case 'string':
                return new StringSchema(serialized.checks);
            case 'enum':
                return new EnumSchema(serialized.values);
            case 'number':
                return new NumberSchema(serialized.checks);
            case 'struct':
                const fieldSchemas = {};
                for (const [field, fieldSchema] of Object.entries(serialized.fieldSchemas)) {
                    fieldSchemas[field] = this.deserialize(fieldSchema);
                }
                return new StructSchema(fieldSchemas, serialized.requiredFields);
            case 'record':
                return new RecordSchema(this.deserialize(serialized.keySchema), this.deserialize(serialized.itemSchema), (typeof serialized.defaultValue !== 'undefined')
                    ? some(serialized.defaultValue)
                    : none);
            case 'color':
                return new ColorSchema();
            case 'array':
                return new ArraySchema(this.deserialize(serialized.itemSchema));
            default:
                throw new Error(`Unknown schema type: ${serialized.type}`);
        }
    }
}
const builtInStringChecks = {
    'minLength': (value, minLength) => {
        if (typeof value === 'string') {
            return (value.length >= minLength)
                ? Either.right(value)
                : Either.left(new ParseIssue(`String is shorter than ${minLength} characters.`, 'minLength'));
        }
        return Either.left(new ParseIssue('Value is not a string.', 'type'));
    },
    'maxLength': (value, maxLength) => {
        if (typeof value === 'string') {
            return (value.length <= maxLength)
                ? Either.right(value)
                : Either.left(new ParseIssue(`String is longer than ${maxLength} characters.`, 'maxLength'));
        }
        return Either.left(new ParseIssue('Value is not a string.', 'type'));
    },
    'regex': (value, pattern) => {
        if (typeof value === 'string') {
            const regex = new RegExp(pattern);
            return regex.test(value)
                ? Either.right(value)
                : Either.left(new ParseIssue(`String does not match the pattern ${pattern}.`, 'regex'));
        }
        return Either.left(new ParseIssue('Value is not a string.', 'type'));
    },
    'trim': (value) => {
        if (typeof value === 'string') {
            return Either.right(value.trim());
        }
        return Either.left(new ParseIssue('Value is not a string.', 'type'));
    }
};
const builtinNumberChecks = {
    'min': (value, minValue) => {
        if (typeof value === 'number') {
            return (value >= minValue)
                ? Either.right(value)
                : Either.left(new ParseIssue(`Number is less than ${minValue}.`, 'min'));
        }
        return Either.left(new ParseIssue('Value is not a number.', 'type'));
    },
    'max': (value, maxValue) => {
        if (typeof value === 'number') {
            return (value <= maxValue)
                ? Either.right(value)
                : Either.left(new ParseIssue(`Number is greater than ${maxValue}.`, 'max'));
        }
        return Either.left(new ParseIssue('Value is not a number.', 'type'));
    }
};
//# sourceMappingURL=schemas.js.map

/***/ },

/***/ "./customizables/revised-ui/settings.js"
/*!**********************************************!*\
  !*** ./customizables/revised-ui/settings.js ***!
  \**********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ArraySetting: () => (/* binding */ ArraySetting),
/* harmony export */   RecordSetting: () => (/* binding */ RecordSetting),
/* harmony export */   Setting: () => (/* binding */ Setting),
/* harmony export */   SparseStructSetting: () => (/* binding */ SparseStructSetting),
/* harmony export */   StructSetting: () => (/* binding */ StructSetting),
/* harmony export */   createSettingFromSchema: () => (/* binding */ createSettingFromSchema),
/* harmony export */   deserializeSettingsPackToStruct: () => (/* binding */ deserializeSettingsPackToStruct)
/* harmony export */ });
/* harmony import */ var _schemas_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./schemas.js */ "./customizables/revised-ui/schemas.js");


var none = AmeMiniFunc.none;
var some = AmeMiniFunc.some;
class Setting {
    constructor(schema, hasCustomValue = true) {
        /**
         * Write-once property to store the key of this setting in its parent struct or record.
         * Only for internal use; the setting itself should not touch this property directly.
         */
        this.keyInParent = null;
        /**
         * Whether this setting exists in its parent struct or record.
         */
        this.exists = ko.observable(true);
        this.schema = schema;
        this.hasCustomValue = hasCustomValue;
        this.isValid = ko.pureComputed(() => this.validationError() === null);
        this.hasValidationErrors = ko.pureComputed(() => {
            if (this.validationError() !== null) {
                return true;
            }
            if (this.isIterable()) {
                //Examine child settings.
                for (const child of this.items()) {
                    if (child.hasValidationErrors()) {
                        return true;
                    }
                }
            }
            return false;
        });
    }
    getSchema() {
        return this.schema;
    }
    isWritable() {
        return true;
    }
    isKeyed() {
        return false;
    }
    isIterable() {
        return false;
    }
    /**
     * Serialize the value of this setting to a plain JS value for storage or transmission.
     *
     * Unlike `value()`, this method may intentionally drop empty optional fields or otherwise
     * transform the value to a more compact representation (depending on the schema).
     */
    serializeValue() {
        return this.value();
    }
    setKeyInParent(key) {
        if (this.keyInParent !== null) {
            throw new Error(`keyInParent is already set to "${this.keyInParent}". It cannot be changed.`);
        }
        this.keyInParent = key;
    }
    getKeyInParent() {
        return this.keyInParent;
    }
}
class SingularSetting extends Setting {
    constructor(schema, value, hasCustomValue = true) {
        super(schema, hasCustomValue);
        this.lastGoodValue = value;
        this.rawValue = ko.observable(value);
        const validationResult = ko.pureComputed(() => this.schema.safeParse(this.rawValue()));
        this.value = ko.pureComputed({
            read: () => {
                const parseResult = validationResult();
                if (parseResult.success) {
                    this.lastGoodValue = parseResult.value;
                    return this.lastGoodValue;
                }
                return this.lastGoodValue;
            },
            write: (newValue) => this.rawValue(newValue),
        });
        this.validationError = ko.pureComputed(() => {
            const parseResult = validationResult();
            if (!parseResult.success) {
                return parseResult.error;
            }
            return null;
        });
    }
    updateWithoutValidation(newValue) {
        this.lastGoodValue = newValue;
        this.rawValue(newValue);
    }
}
class StructSetting extends Setting {
    constructor(schema, value, hasCustomValue = true) {
        super(schema, hasCustomValue);
        this.fields = {};
        const items = [];
        for (const [property, propertySchema] of Object.entries(schema.fieldSchemas)) {
            let propertyValue, hasCustomValueForProperty = false, fieldExists = false;
            if ((value !== null) && value.hasOwnProperty(property)) {
                fieldExists = true;
                propertyValue = value[property];
                hasCustomValueForProperty = hasCustomValue; //True only if the struct itself has a custom value.
            }
            else {
                propertyValue = null;
            }
            const newSetting = createSettingFromSchema(propertySchema, propertyValue, hasCustomValueForProperty);
            newSetting.setKeyInParent(property);
            newSetting.exists(fieldExists);
            this.fields[property] = newSetting;
            items.push(newSetting);
        }
        this.items = ko.observableArray(items);
        this.value = ko.pureComputed({
            read: () => {
                const result = {};
                for (const setting of this.items()) {
                    if (setting.exists()) {
                        const property = setting.getKeyInParent();
                        if (property === null) {
                            throw new Error('StructSetting: setting has no keyInParent set. This should never happen.');
                        }
                        result[property] = setting.value();
                    }
                }
                return result;
            },
            write: (newValue) => {
                if ((typeof newValue !== 'object') || (newValue === null)) {
                    const displayType = (newValue === null) ? 'null' : typeof newValue;
                    throw new Error(`Expected an object for StructSetting, got ${displayType} (${newValue})`);
                }
                for (const property of Object.keys(this.schema.fieldSchemas)) {
                    const setting = this.fields[property];
                    if (newValue.hasOwnProperty(property)) {
                        setting.exists(true);
                        setting.value(newValue[property]);
                    }
                    else {
                        setting.exists(false);
                    }
                }
            }
        }).extend({ deferred: true });
        const structValidationError = ko.pureComputed(() => {
            //Validate only the struct itself, not its children. Child validation errors are checked
            //later, in the validationError computed.
            const parseResult = this.schema.safeParse(this.value(), [], true);
            if (!parseResult.success) {
                return parseResult.error;
            }
            return null;
        });
        this.validationError = ko.pureComputed(() => {
            const structError = structValidationError();
            if (structError) {
                return structError;
            }
            for (const setting of this.items()) {
                const childError = setting.validationError();
                if (childError) {
                    return childError.copyWithPrependedPath([setting.getKeyInParent()]);
                }
            }
            return null;
        });
    }
    /**
     * Add a new field to the struct.
     *
     * This is mainly intended for use with "virtual" structs that are validated client-side.
     * Trying to submit a struct with fields that are not defined in the original schema will
     * usually result in those fields being stripped out.
     */
    addSetting(property, setting) {
        if (this.fields.hasOwnProperty(property)) {
            throw new Error(`Field "${property}" already exists in StructSetting.`);
        }
        this.fields[property] = setting;
        this.schema.fieldSchemas[property] = setting.getSchema();
        setting.setKeyInParent(property);
        setting.exists(true);
        this.items.push(setting);
    }
    removeSetting(property) {
        if (!this.fields.hasOwnProperty(property)) {
            throw new Error(`Field "${property}" does not exist in StructSetting.`);
        }
        const setting = this.fields[property];
        delete this.fields[property];
        delete this.schema.fieldSchemas[property];
        this.items.remove(setting);
        setting.exists(false);
    }
    getChildByPath(path) {
        const segments = path.split('.');
        let current = this;
        for (const segment of segments) {
            if (!current.isKeyed()) {
                return none;
            }
            const childOption = current.getChild(segment);
            if (childOption.isEmpty()) {
                return none;
            }
            current = childOption.get();
        }
        if (!(current instanceof Setting)) {
            return none;
        }
        return some(current);
    }
    getChild(property) {
        if (!this.fields.hasOwnProperty(property)) {
            return none;
        }
        if (!this.fields[property].exists()) {
            return none;
        }
        return some(this.fields[property]);
    }
    isKeyed() {
        return true;
    }
    isIterable() {
        return true;
    }
    updateWithoutValidation(newValue) {
        for (const property of Object.keys(this.schema.fieldSchemas)) {
            const setting = this.fields[property];
            if (newValue.hasOwnProperty(property)) {
                setting.exists(true);
                setting.updateWithoutValidation(newValue[property]);
            }
            else {
                setting.exists(false);
            }
        }
    }
    serializeValue() {
        const result = {};
        for (const setting of this.items()) {
            if (setting.exists()) {
                const property = setting.getKeyInParent();
                if (property === null) {
                    throw new Error('StructSetting: setting has no keyInParent set. This should never happen.');
                }
                const childValue = setting.serializeValue();
                const childSchema = setting.getSchema();
                //Drop optional fields that are empty, to avoid cluttering the serialized output.
                //Exception: If the field has a non-empty default value, we want to keep the empty
                //custom value so that it overrides the default.
                if (!this.schema.isFieldRequired(property)
                    && childSchema.isEmptyValue(childValue)
                    && !childSchema.hasNonEmptyDefaultValue()) {
                    continue;
                }
                result[property] = childValue;
            }
        }
        return result;
    }
}
class SparseStructSetting extends StructSetting {
    constructor() {
        super(new _schemas_js__WEBPACK_IMPORTED_MODULE_0__.StructSchema({}), null, false);
    }
}
class RecordSetting extends Setting {
    constructor(schema, value, hasCustomValue = true) {
        super(schema, hasCustomValue);
        this.fields = {};
        const items = [];
        if (value !== null) {
            for (const [property, propertyValue] of Object.entries(value)) {
                const setting = createSettingFromSchema(schema.valueSchema, propertyValue, hasCustomValue);
                setting.setKeyInParent(property);
                this.fields[property] = setting;
                items.push(setting);
            }
        }
        this.items = ko.observableArray(items);
        this.value = ko.pureComputed({
            read: () => {
                const result = {};
                for (const setting of this.items()) {
                    const key = setting.getKeyInParent();
                    if (key === null) {
                        throw new Error('RecordSetting: setting has no keyInParent set. This should not happen.');
                    }
                    result[key] = setting.value();
                }
                return result;
            },
            write: (newValue) => {
                if ((typeof newValue !== 'object') || (newValue === null)) {
                    const displayType = (newValue === null) ? 'null' : typeof newValue;
                    throw new Error(`Expected a non-null object for RecordSetting, got ${displayType}`);
                }
                const newItems = [];
                for (const [property, propertyValue] of Object.entries(newValue)) {
                    const existing = this.fields[property];
                    if (existing) {
                        existing.value(propertyValue);
                        newItems.push(existing);
                    }
                    else {
                        const newSetting = createSettingFromSchema(this.schema.valueSchema, propertyValue, hasCustomValue);
                        newSetting.setKeyInParent(property);
                        newItems.push(newSetting);
                    }
                }
                this.fields = Object.fromEntries(newItems.map(s => [s.getKeyInParent(), s]));
                this.items(newItems);
            }
        }).extend({ deferred: true }); //Defer to unnecessary re-evaluations when multiple fields are updated at once.
        this.validationError = ko.pureComputed(() => {
            for (const setting of this.items()) {
                const childError = setting.validationError();
                if (childError) {
                    return childError;
                }
            }
            return null;
        });
    }
    addItem(property, value) {
        if (this.fields.hasOwnProperty(property)) {
            throw new Error(`Field "${property}" already exists in RecordSetting.`);
        }
        const newSetting = createSettingFromSchema(this.schema.valueSchema, value, this.hasCustomValue);
        newSetting.setKeyInParent(property);
        this.fields[property] = newSetting;
        this.items.push(newSetting);
    }
    removeItem(item) {
        const key = item.getKeyInParent();
        if (key === null) {
            throw new Error('RecordSetting: setting has no keyInParent set. This should not happen.');
        }
        delete this.fields[key];
        this.items.remove(item);
    }
    getChild(property) {
        if (!this.fields.hasOwnProperty(property)) {
            return none;
        }
        return some(this.fields[property]);
    }
    isKeyed() {
        return true;
    }
    isIterable() {
        return true;
    }
    updateWithoutValidation(newValue) {
        const newItems = [];
        for (const [property, propertyValue] of Object.entries(newValue)) {
            const existing = this.fields[property];
            if (existing) {
                existing.updateWithoutValidation(propertyValue);
                newItems.push(existing);
            }
            else {
                const newSetting = createSettingFromSchema(this.schema.valueSchema, propertyValue, this.hasCustomValue);
                newSetting.setKeyInParent(property);
                newItems.push(newSetting);
            }
        }
        this.fields = Object.fromEntries(newItems.map(s => [s.getKeyInParent(), s]));
        this.items(newItems);
    }
}
class ArraySetting extends Setting {
    constructor(schema, value, hasCustomValue = true) {
        super(schema, hasCustomValue);
        this.items = ko.observableArray((value ?? []).map(item => createSettingFromSchema(schema.itemSchema, item, hasCustomValue)));
        this.value = ko.pureComputed({
            read: () => {
                return this.items().map(item => item.value());
            },
            write: (newValue) => {
                if (!Array.isArray(newValue)) {
                    throw new Error(`Expected array, got ${typeof newValue}`);
                }
                this.items(newValue.map(item => createSettingFromSchema(schema.itemSchema, item, hasCustomValue)));
            }
        });
        this.validationError = ko.pureComputed(() => {
            for (const item of this.items()) {
                const childError = item.validationError();
                if (childError) {
                    return childError;
                }
            }
            return null;
        });
    }
    isIterable() {
        return true;
    }
    addItem(value) {
        const newItem = createSettingFromSchema(this.schema.itemSchema, value, this.hasCustomValue);
        this.items.push(newItem);
    }
    removeItem(item) {
        this.items.remove(item);
    }
    updateWithoutValidation(newValue) {
        if (!Array.isArray(newValue)) {
            throw new Error(`Expected array, got ${typeof newValue}`);
        }
        this.items(newValue.map(item => createSettingFromSchema(this.schema.itemSchema, item, this.hasCustomValue)));
    }
}
function createSettingFromSchema(schema, value, hasCustomValue = true) {
    if (typeof value === 'undefined') {
        value = null;
        hasCustomValue = false;
    }
    if (schema instanceof _schemas_js__WEBPACK_IMPORTED_MODULE_0__.StructSchema) {
        if ((typeof value !== 'object') && (value !== null)) {
            throw new Error(`Expected object or null for StructSchema, got ${typeof value}`);
        }
        return new StructSetting(schema, value, hasCustomValue);
    }
    else if (schema instanceof _schemas_js__WEBPACK_IMPORTED_MODULE_0__.RecordSchema) {
        if ((typeof value !== 'object') && (value !== null)) {
            throw new Error(`Expected object or null for RecordSchema, got ${typeof value}`);
        }
        return new RecordSetting(schema, value, hasCustomValue);
    }
    else if (schema instanceof _schemas_js__WEBPACK_IMPORTED_MODULE_0__.ArraySchema) {
        if ((value !== null) && !Array.isArray(value)) {
            throw new Error(`Expected array for ArraySchema, got ${typeof value}`);
        }
        return new ArraySetting(schema, value, hasCustomValue);
    }
    else {
        return new SingularSetting(schema, value, hasCustomValue);
    }
}
function deserializeSettingsPackToStruct(serializedPack) {
    const schemaDeserializer = new _schemas_js__WEBPACK_IMPORTED_MODULE_0__.SchemaDeserializer(serializedPack.sharedSchemas);
    const structSetting = new SparseStructSetting();
    for (const [path, serializedSetting] of Object.entries(serializedPack.settings)) {
        const schema = schemaDeserializer.deserialize(serializedSetting.schema);
        const value = serializedSetting.value;
        const hasCustomValue = typeof value !== 'undefined';
        const segments = path.split('.');
        const finalSegment = segments.pop();
        const parent = ensureStructPath(structSetting, segments);
        if (!(parent instanceof StructSetting)) {
            throw new Error(`Cannot add field to non-StructSetting at "${segments.join('.')}"`);
        }
        if (parent.getChild(finalSegment).isDefined()) {
            throw new Error(`Setting "${path}" conflicts with an existing setting in the struct.`);
        }
        const newSetting = createSettingFromSchema(schema, value, hasCustomValue);
        parent.addSetting(finalSegment, newSetting);
    }
    return structSetting;
}
function ensureStructPath(struct, segments) {
    let current = struct;
    for (const [i, segment] of segments.entries()) {
        if (!current.isKeyed()) {
            throw new Error(`Cannot traverse into non-traversable setting at "${segments.slice(0, i).join('.')}"`);
        }
        let child = current.getChild(segment);
        if (child.isDefined()) {
            current = child.get();
        }
        else {
            if (!(current instanceof SparseStructSetting)) {
                throw new Error(`Cannot add child setting to non-SparseStructSetting at "${segments.slice(0, i + 1).join('.')}"`);
            }
            const struct = new SparseStructSetting();
            current.addSetting(segment, struct);
            current = struct;
        }
    }
    return current;
}
//# sourceMappingURL=settings.js.map

/***/ },

/***/ "./customizables/revised-ui/ui-pack.js"
/*!*********************************************!*\
  !*** ./customizables/revised-ui/ui-pack.js ***!
  \*********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   UiPack: () => (/* binding */ UiPack)
/* harmony export */ });
/* harmony import */ var _settings_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./settings.js */ "./customizables/revised-ui/settings.js");
/* harmony import */ var _registry_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./registry.js */ "./customizables/revised-ui/registry.js");
/* harmony import */ var _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../shared-dsl/client/wire-dsl.js */ "./shared-dsl/client/wire-dsl.js");



class UiPack {
    constructor(ui, registry = null, customizer = null) {
        this.settings = (0,_settings_js__WEBPACK_IMPORTED_MODULE_0__.deserializeSettingsPackToStruct)(ui.settingsPack);
        if (!registry) {
            registry = _registry_js__WEBPACK_IMPORTED_MODULE_1__.ServiceRegistry.init();
        }
        this.rootComponent = {
            name: 'ame-rev-general-structure',
            params: {
                ...ui.interfaceStructure,
                registry: registry,
                context: new _shared_dsl_client_wire_dsl_js__WEBPACK_IMPORTED_MODULE_2__.Context(null, null, this.settings),
                customizer: customizer
            }
        };
        this.hasValidationErrors = ko.pureComputed(() => {
            return this.settings.hasValidationErrors();
        });
    }
}
//# sourceMappingURL=ui-pack.js.map

/***/ },

/***/ "./shared-dsl/client/wire-dsl.js"
/*!***************************************!*\
  !*** ./shared-dsl/client/wire-dsl.js ***!
  \***************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Context: () => (/* binding */ Context),
/* harmony export */   Expression: () => (/* binding */ Expression),
/* harmony export */   Literal: () => (/* binding */ Literal),
/* harmony export */   looksLikeSerializedExpression: () => (/* binding */ looksLikeSerializedExpression),
/* harmony export */   looksLikeSerializedMinimalBinding: () => (/* binding */ looksLikeSerializedMinimalBinding),
/* harmony export */   unserializeExpression: () => (/* binding */ unserializeExpression),
/* harmony export */   unserializeMinimalBinding: () => (/* binding */ unserializeMinimalBinding)
/* harmony export */ });
class Context {
    constructor(currentItem = null, parent = null, rootSettingContainer = null) {
        this.currentItem = currentItem;
        this.parent = parent;
        this.rootSettingContainer = rootSettingContainer;
        if (parent && !this.rootSettingContainer) {
            this.rootSettingContainer = parent.getRootSettingContainer();
        }
        if (parent) {
            this.resolvers = parent.resolvers;
        }
        else {
            this.resolvers = new Map();
            this.resolvers.set('binding', new BindingResolutionStrategy());
        }
        this.functions = new Map();
    }
    registerFunction(name, callback) {
        const dslFunction = new DslFunction(name, callback);
        this.functions.set(name, dslFunction);
    }
    getFunction(name) {
        const func = this.functions.get(name);
        if (func) {
            return func;
        }
        if (this.parent) {
            return this.parent.getFunction(name);
        }
        return undefined;
    }
    resolveValue(ref) {
        const resolution = this.resolve(ref);
        if (!resolution) {
            throw new Error(`Unable to resolve reference: ${JSON.stringify(ref)}`);
        }
        return resolution.value();
    }
    resolve(ref) {
        const strategyKey = ref.getResolutionStrategyKey();
        const strategy = this.resolvers.get(strategyKey);
        if (!strategy) {
            throw new Error(`No resolution strategy registered for key: ${strategyKey}`);
        }
        return strategy.resolve(ref, this);
    }
    getRootSettingContainer() {
        return this.rootSettingContainer;
    }
    createChildContext(currentItem) {
        if (typeof currentItem === 'undefined') {
            currentItem = this.currentItem;
        }
        return new Context(currentItem, this, this.rootSettingContainer);
    }
}
//region Expressions
class Expression {
}
class Literal extends Expression {
    constructor(value) {
        super();
        this.$value = value;
    }
    evaluate(_ctx) {
        return this.$value;
    }
}
class ArrayExpression extends Expression {
    constructor(items) {
        super();
        this.items = items;
    }
    evaluate(ctx) {
        return this.items.map(item => item.evaluate(ctx));
    }
}
class DslFunction {
    constructor(name, callback) {
        this.name = name;
        this.callback = callback;
    }
    invoke(ctx, args) {
        return this.callback(ctx, args);
    }
}
class FunctionCall extends Expression {
    constructor(functionName, args) {
        super();
        this.functionName = functionName;
        this.args = args;
    }
    evaluate(ctx) {
        const evaluatedArgs = this.evaluateArgs(ctx, this.args);
        return this.invokeFunction(ctx, evaluatedArgs);
    }
    evaluateArgs(ctx, args) {
        if (Array.isArray(args)) {
            return args.map(arg => arg.evaluate(ctx));
        }
        return Object.keys(args).reduce((result, key) => {
            result[key] = args[key].evaluate(ctx);
            return result;
        }, {});
    }
    invokeFunction(ctx, args) {
        const dslFunction = ctx.getFunction(this.functionName);
        if (!dslFunction) {
            throw new Error(`Function "${this.functionName}" is not registered.`);
        }
        return dslFunction.invoke(ctx, args);
    }
}
class Reference extends Expression {
    evaluate(ctx) {
        return ctx.resolveValue(this);
    }
}
class BindingRef extends Reference {
    constructor(bindingString) {
        super();
        this.bindingString = bindingString;
    }
    getResolutionStrategyKey() {
        return 'binding';
    }
}
class ResolutionOf extends Expression {
    constructor(ref) {
        super();
        this.ref = ref;
    }
    evaluate(ctx) {
        const resolution = ctx.resolve(this.ref);
        if (!resolution) {
            throw new Error(`Unable to resolve reference: ${JSON.stringify(this.ref)}`);
        }
        return resolution;
    }
}
function unserializeExpression(serialized) {
    switch (serialized.t) {
        case 'literal':
            return new Literal(serialized.value);
        case 'array':
            return new ArrayExpression(serialized.items.map(unserializeExpression));
        case 'funcCall':
            const args = Array.isArray(serialized.args)
                ? serialized.args.map(unserializeExpression)
                : Object.fromEntries(Object.entries(serialized.args).map(([key, value]) => [key, unserializeExpression(value)]));
            return new FunctionCall(serialized.functionName, args);
        case 'binding':
            return new BindingRef(serialized.bind);
        case 'resolve':
            return new ResolutionOf(unserializeMinimalBinding(serialized.ref));
        default:
            throw new Error(`Unknown serialized expression type: ${serialized.type}`);
    }
}
function unserializeMinimalBinding(binding) {
    if (typeof binding === 'string') {
        binding = { t: 'binding', bind: binding };
    }
    if (!('t' in binding) || (binding.t !== 'binding')) {
        throw new Error(`Invalid binding format: ${JSON.stringify(binding)}`);
    }
    return new BindingRef(binding.bind);
}
function looksLikeSerializedMinimalBinding(obj) {
    if (typeof obj === 'string') {
        return true;
    }
    if (typeof obj !== 'object' || obj === null) {
        return false;
    }
    return obj.t === 'binding' && typeof obj.bind === 'string';
}
function looksLikeSerializedExpression(obj) {
    if (typeof obj !== 'object' || obj === null) {
        return false;
    }
    const type = obj.t;
    if (typeof type !== 'string') {
        return false;
    }
    switch (type) {
        case 'literal':
            return 'value' in obj;
        case 'array':
            return Array.isArray(obj.items);
        case 'funcCall':
            return typeof obj.functionName === 'string' && ('args' in obj);
        case 'binding':
            return typeof obj.bind === 'string';
        case 'resolve':
            return ('ref' in obj) && looksLikeSerializedMinimalBinding(obj.ref);
        default:
            return false;
    }
}
class ResolutionStrategy {
}
class BindingResolutionStrategy extends ResolutionStrategy {
    resolve(ref, ctx) {
        const path = ref.bindingString.split('.');
        //console.log('Resolving binding ref:', path, 'in context:', ctx);
        return this.resolvePath(path, ctx);
    }
    resolvePath(path, ctx) {
        let current = ctx.getRootSettingContainer();
        for (const segment of path) {
            if (segment === BindingResolutionStrategy.CURRENT_ITEM_KEY) {
                current = ctx.currentItem;
            }
            else if (current && current.isKeyed()) {
                current = current.getChild(segment).orNull();
            }
            else {
                current = null;
            }
            if (!current) {
                break;
            }
        }
        return current ? current : null;
    }
}
BindingResolutionStrategy.CURRENT_ITEM_KEY = '$item';
//endregion
//# sourceMappingURL=wire-dsl.js.map

/***/ }

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./modules/tweaks/tweak-manager.ts"));
/******/ }
]);
//# sourceMappingURL=tweak-manager.bundle.js.map