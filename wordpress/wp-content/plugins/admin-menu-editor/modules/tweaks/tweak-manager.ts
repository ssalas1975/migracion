/// <reference path="../../js/knockout.d.ts" />
/// <reference path="../../js/jquery.d.ts" />
/// <reference types="@types/lodash" />
/// <reference path="../../modules/actor-selector/actor-selector.ts" />
/// <reference path="../../js/jquery.biscuit.d.ts" />
/// <reference path="../../js/ko-extensions.ts" />

import SaveFormConfigFromServer = AmeKoFreeExtensions.SaveFormConfigFromServer;
import SaveSettingsForm = AmeKoFreeExtensions.SaveSettingsForm;
import {SerializedUiPack, UiPack} from '../../customizables/revised-ui/ui-pack.js';
import {ServiceRegistry} from '../../customizables/revised-ui/registry.js';
import {AnySerializedElement, Container, SerializedControl} from '../../customizables/revised-ui/controls.js';
import {
	ArraySetting,
	createSettingFromSchema,
	Setting,
	StructSetting
} from '../../customizables/revised-ui/settings.js';
import {SerializedResolutionOf} from '../../shared-dsl/client/wire-dsl.js';
import {registerRevisedUiComponents} from '../../customizables/revised-ui/component-collection.js';
import {
	AmeRevEventButton
} from '../../customizables/revised-ui/components/ame-rev-event-button/ame-rev-event-button.js';

let ameTweakManager: AmeTweakManagerModule;
declare const wsTweakManagerData: AmeTweakManagerScriptData;
interface AmeTweakManagerScriptData {
	isProVersion: boolean;

	lastUserTweakSuffix: number;
	defaultCodeEditorSettings: Record<string, any>;

	uiPack: SerializedUiPack;
	editAdminCssUiPack: SerializedUiPack;
	addUrlBlockUiPack?: SerializedUiPack;

	preferenceCookiePath: string;
	saveFormConfig: SaveFormConfigFromServer;
}

class AmeTweakManagerModule {
	static _ = wsAmeLodash;
	static readonly openSectionCookieName = 'ame_tmce_open_sections';
	static readonly tweaksSettingsId = 'ws_ame_tweak_settings--tweaks';
	static readonly featureDataSettingId = 'ws_ame_tweak_settings--featureData';

	readonly actorSelector: AmeActorSelector;

	public readonly tweaksUi: UiPack;
	readonly saveSettingsForm: SaveSettingsForm;

	readonly adminCssEditorDialog: AmeEditAdminCssDialogV3;
	private lastUserTweakSuffix: number = 0;
	private adminCssSection: Container | null = null;

	readonly addUrlBlockDialog: AmeAddUrlBlockDialog | null = null;

	public readonly cssHighlightingOptions: Record<string, any>;

	constructor(scriptData: AmeTweakManagerScriptData) {
		const _ = AmeTweakManagerModule._;

		this.actorSelector = new AmeActorSelector(AmeActors, scriptData.isProVersion);
		//Reselect the previously selected actor.
		this.actorSelector.setSelectedActorFromUrl();

		//Set syntax highlighting options.
		this.cssHighlightingOptions = _.merge(
			{},
			scriptData.defaultCodeEditorSettings,
			{
				'codemirror': {
					'mode': 'css',
					'lint': true,
					'autoCloseBrackets': true,
					'matchBrackets': true
				}
			}
		);

		const collapsibleSectionStateStore = new AmeCollapsibleStateStore(
			AmeTweakManagerModule.openSectionCookieName,
			scriptData.preferenceCookiePath,
			AmeTweakManagerModule.openSectionCookieName
		);

		const registry = ServiceRegistry.init()
			.register('actorSelector', this.actorSelector)
			.register('collapsibleStateStore', collapsibleSectionStateStore);

		const featureStrategy = new AmeActorFeatureStrategy({
			roleCombinationMode: AmeRoleCombinationMode.Some,
			getSelectedActor: this.actorSelector.getActorObservable(ko),
			getAllActors: () => this.actorSelector.getVisibleActors()
		});

		this.tweaksUi = new UiPack(scriptData.uiPack, registry, (c) => {
			switch (c.t) {
				case 'container':
					if (!(c.role === 'group')) {
						c = {...c, component: 'ame-rev-postbox-section'};
					}
					//Register the admin CSS section container so we can add new tweaks to it later.
					if (c.id === 'twm-section_admin-css' && c.t === 'container') {
						c.registerSelf = (container: Container) => {
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

		const sectionIds: string[] = [];
		this.getDefaultSlotContent(scriptData.uiPack.interfaceStructure).forEach((child) => {
			if (child.t === 'container' && (typeof child.id === 'string')) {
				sectionIds.push(child.id);
			}
		});

		//By default, open the first section and close the rest.
		if (!collapsibleSectionStateStore.hasAnyStoredStates()) {
			const sectionStates: { [sectionId: string]: boolean } = {};
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

	onEditTweak(_unused: unknown, event: BaseJQueryEventObject) {
		const detail = this.getCustomEventDetail(event);

		if (!(detail.data instanceof Setting)) {
			throw new Error('Edit Tweak event did not contain a valid Setting in its detail: ' + JSON.stringify(detail));
		}

		this.adminCssEditorDialog.selectedTweak = detail.data;
		this.adminCssEditorDialog.open();
	}

	onDeleteTweak(_unused: unknown, event: BaseJQueryEventObject) {
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

	addAdminCssTweak(settingToCopy: StructSetting) {
		this.lastUserTweakSuffix++;
		const value = settingToCopy.value();
		if (!value) {
			throw new Error('Invalid setting value'); //This should never happen.
		}

		const label = (value as any)['label'] || 'New Snippet';
		let slug = AmeTweakManagerModule.slugify(label);
		if (slug !== '') {
			slug = '-' + slug;
		}

		const newTweakId = 'utw-' + this.lastUserTweakSuffix + slug;
		const settingPath = AmeTweakManagerModule.tweaksSettingsId + '.' + newTweakId;

		const tweaks = this.getTweaksStructSetting();
		tweaks.addSetting(newTweakId, createSettingFromSchema(settingToCopy.getSchema(), value));

		//Create the control: the actor feature checkbox with a nested code editor.
		const defaultEditorParams = this.getDefaultCodeEditorParams();
		const newControl: SerializedControl = {
			t: 'control',
			label: {t: 'binding', bind: settingPath + '.label'},
			component: 'ame-rev-actor-feature',
			bindings: {value: settingPath + '.enabledForActor'},
			hasTweakActions: true,
			objectId: newTweakId,
			slots: {
				default: [
					{
						...defaultEditorParams,
						t: 'control',
						label: '',
						component: 'ame-rev-code-editor',
						bindings: {value: settingPath + '.css'},
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
						} satisfies SerializedResolutionOf
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

	private getAdminCssSection(): Container {
		if (this.adminCssSection) {
			return this.adminCssSection;
		}
		throw new Error('Admin CSS section container is not registered yet or does not exist.');
	}

	private getTweaksStructSetting(): StructSetting {
		const tweaks = this.tweaksUi.settings
			.getChild(AmeTweakManagerModule.tweaksSettingsId).getOrThrow();
		if (!(tweaks instanceof StructSetting)) {
			throw new Error('Tweaks setting is not a StructSetting');
		}
		return tweaks;
	}

	private getCustomEventDetail(event: BaseJQueryEventObject): any {
		if (event.originalEvent instanceof CustomEvent) {
			return event.originalEvent.detail;
		}
		return null;
	}

	private getDefaultSlotContent(element: AnySerializedElement): AnySerializedElement[] {
		if (element.slots && element.slots['default']) {
			return element.slots['default'];
		} else {
			return [];
		}
	}

	private cachedCodeEditorParams: Partial<AnySerializedElement> | null = null;

	private getDefaultCodeEditorParams(): Partial<AnySerializedElement> {
		if (this.cachedCodeEditorParams) {
			return this.cachedCodeEditorParams;
		}

		//Find the default admin CSS tweak. This is a linear search, but it's only done once,
		//and I expect it will only need to look at <50 nodes in the UI tree, so it should be fine.
		const path = ['twm-section_admin-css', 'ame-tweak-default-admin-css'];
		let currentElement: AnySerializedElement = this.tweaksUi.rootComponent.params;
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
		const {id, bindings, ...paramsWithoutIdAndBindings} = firstChild;
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

	onToggleUrlBlockEditor(_unused: unknown, event: BaseJQueryEventObject): void {
		const $container = jQuery(event.target).closest('.ame-rui-block-by-url-container');
		const $panel = $container.find('.ame-rui-block-by-url-edit-panel').first();
		$panel.toggle().removeClass('hidden');

		if (event.originalEvent instanceof CustomEvent) {
			const detail = event.originalEvent.detail;
			if (detail && (detail.eventButton instanceof AmeRevEventButton)) {
				const isOpen = $panel.is(':visible');
				detail.eventButton.customLabel(isOpen ? 'Close' : 'Edit');
				detail.eventButton.customIconClass(
					isOpen ? 'dashicons dashicons-arrow-up' : 'dashicons dashicons-arrow-right'
				);
			}
		}
	}

	onDeleteUrlBlock(_unused: unknown, event: BaseJQueryEventObject): void {
		if (!confirm('Delete this URL block?')) {
			return;
		}

		const blocks = this.getBlocksArraySetting();
		const detail = this.getCustomEventDetail(event);
		if (detail && (detail.data instanceof Setting)) {
			const blockToDelete = detail.data;
			blocks.removeItem(blockToDelete);
		} else {
			console.warn('Delete URL Block event did not contain a valid Setting in its detail:', detail);
		}
	}

	private getBlocksArraySetting(): ArraySetting {
		return this.tweaksUi.settings.getChildByPath('ws_ame_tweak_settings--featureData.url-blocks.blocks')
			.map((blocks) => {
				if (blocks instanceof ArraySetting) {
					return blocks;
				} else {
					throw new Error('Expected "blocks" to be an ArraySetting, but it was not: ' + blocks);
				}
			})
			.getOrThrow('blocks setting is not defined');
	}

	//endregion

	static slugify(input: string): string {
		const _ = AmeTweakManagerModule._;
		let output = _.deburr(input);
		output = output.replace(/[^a-zA-Z0-9 _\-]/g, '');
		return _.kebabCase(output);
	}
}

class AmeEditAdminCssDialogV3 extends AmeBaseKnockoutDialog {
	autoCancelButton: boolean = true;

	isConfirmButtonEnabled: KnockoutComputed<boolean>;
	confirmButtonLabel: KnockoutObservable<string | null>;

	selectedTweak: Setting | null = null;
	panelUi: UiPack;

	constructor(uiConfig: SerializedUiPack, private readonly manager: AmeTweakManagerModule) {
		super();

		this.options.minWidth = 400;
		this.options.classes = {
			'ui-dialog': 'ui-corner-all ame-twm-admin-css-dialog',
		}
		this.panelUi = new UiPack(uiConfig);

		this.confirmButtonLabel = ko.observable<string | null>('Add Snippet');

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
		} else {
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
		} else {
			this.manager.addAdminCssTweak(this.getTempCssSnippetSetting());
		}
		this.close();
	}

	onClose() {
		this.selectedTweak = null;
	}

	private getTempCssSnippetSetting(): StructSetting {
		const opt = this.panelUi.settings.getChild('tempAdminCssSnippet');
		if (!opt.isDefined()) {
			throw new Error('tempAdminCssSnippet setting is not defined');
		}
		const tempSnippetSetting = opt.get();
		if (tempSnippetSetting instanceof StructSetting) {
			return tempSnippetSetting;
		} else {
			throw new Error('Expected tempAdminCssSnippet to be a StructSetting, but it was not: ' + tempSnippetSetting);
		}
	}
}

interface NewBlockData {
	title: string;
	urlList: string;
	matchType: string;
	enabledForActor: Record<string, unknown>;
}

class AmeAddUrlBlockDialog extends AmeBaseKnockoutDialog {
	readonly confirmButtonLabel: KnockoutObservable<string | null>;
	isConfirmButtonEnabled: KnockoutObservable<boolean>;

	readonly panelUi: UiPack;

	constructor(ui: SerializedUiPack, private readonly add: (blockData: NewBlockData) => void) {
		super();
		this.options.minWidth = 400;

		this.panelUi = new UiPack(ui);

		this.confirmButtonLabel = ko.observable<string | null>('Add Block');
		this.isConfirmButtonEnabled = ko.pureComputed(() => {
			return !this.panelUi.hasValidationErrors();
		});
	}

	private getTempUrlBlockSetting(): StructSetting {
		const opt = this.panelUi.settings.getChild('tempUrlBlock');
		if (!opt.isDefined()) {
			throw new Error('tempUrlBlock setting is not defined');
		}
		const tempBlockSetting = opt.get();
		if (tempBlockSetting instanceof StructSetting) {
			return tempBlockSetting;
		} else {
			throw new Error('Expected tempUrlBlock to be a StructSetting, but it was not: ' + tempBlockSetting);
		}
	}

	onConfirm(event: JQueryEventObject) {
		super.onConfirm(event);

		const blockData = {
			...this.getTempUrlBlockSetting().value() as NewBlockData,
			enabledForActor: {}
		};

		this.add(blockData);
		this.isOpen(false);
	}

	onOpen(): void {
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
	registerRevisedUiComponents(ko);

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



