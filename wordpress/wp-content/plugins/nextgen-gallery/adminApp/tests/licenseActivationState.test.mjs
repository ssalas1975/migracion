/**
 * Regression cover for nextgen-gallery-pro#570: a license activated in Settings has to open the
 * gallery/album gates straight away, without a page reload.
 *
 * Run: yarn --cwd adminApp test   (node --test, no browser, no WordPress)
 *
 * LicenseTab is transpiled with the app's own Babel presets and evaluated against stubbed react /
 * api-fetch / store modules, so its real fetchCurrentLicenseStatus() and handleDeactivateLicense()
 * run. The react stub keeps state per hook slot and re-renders on demand, which is how the
 * handlers are reached: by clicking the buttons the tab actually renders.
 * licenseValidation is NOT stubbed: the assertions go through the same canCreateGalleries() the
 * "Add New Gallery" button calls.
 */
import assert from 'node:assert/strict';
import {readFileSync} from 'node:fs';
import {createRequire} from 'node:module';
import {dirname, resolve} from 'node:path';
import {test} from 'node:test';
import {fileURLToPath} from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const appRequire = createRequire(resolve(here, '../package.json'));
const babel = appRequire('@babel/core');

const STALE_SNAPSHOT = {
	license_key: '',
	status: 'inactive',
	level: 'free',
	last_check: 0,
	plugin_level: 'pro',
	is_valid: false,
	expiration: null,
	expires_soon: false,
	brand: 'photocrati'
};

const ACTIVE_RESPONSE = {
	license_key: 'abc123',
	status: 'active',
	level: 'pro',
	last_check: 1794818738,
	plugin_level: 'pro',
	is_valid: true,
	expiration: 1824335999,
	expires_soon: false
};

const proWindow = licenseData => ({
	imagelyApp: {proTypeInstalled: 'pro', nonce: 'n', licenseData: {...licenseData}}
});

function compile(relativePath) {
	const file = resolve(here, '../src', relativePath);
	return babel.transformSync(readFileSync(file, 'utf8'), {
		filename: file,
		babelrc: false,
		configFile: false,
		presets: [
			[appRequire.resolve('@babel/preset-env'), {targets: {node: 'current'}, modules: 'commonjs'}],
			appRequire.resolve('@babel/preset-react'),
			appRequire.resolve('@babel/preset-typescript')
		]
	});
}

function evaluate(relativePath, localRequire) {
	const module = {exports: {}};
	new Function('require', 'module', 'exports', compile(relativePath).code)(
		localRequire,
		module,
		module.exports
	);
	return module.exports;
}

/** Text of a rendered node and everything under it. */
function textOf(node) {
	if (node === null || node === undefined || typeof node === 'boolean') {
		return '';
	}
	if (typeof node === 'string' || typeof node === 'number') {
		return String(node);
	}
	return (node.children || []).map(textOf).join(' ');
}

/** Let the component's awaited apiFetch calls settle. */
const settle = () => new Promise(resolve => globalThis.setTimeout(resolve, 0));

/**
 * Mount LicenseTab against a given /license-actions/current response.
 *
 * Returns the real licenseValidation module, the requests the component made, a runMountEffects()
 * that fires the mount effects, and a click() that invokes a rendered button's onClick by label
 * and re-renders - which is how the real activation/deactivation handlers are reached from here.
 */
function mountLicenseTab({currentResponse}) {
	const requests = [];
	const effects = [];
	let buttons = [];
	let inputs = [];
	// Callbacks the component handed to setTimeout, fired on demand by runTimers().
	const timers = [];
	// One slot per useState call, in call order, kept across re-renders.
	const states = [];
	let slot = 0;
	let mounted = false;

	const licenseValidation = evaluate('utils/licenseValidation.ts', id => {
		if (id === '@wordpress/i18n') {
			return {__: text => text};
		}
		throw new Error(`unexpected import ${id}`);
	});

	const react = {
		// Keep a minimal node tree so a rendered button's handler can be invoked below.
		createElement: (type, props, ...children) => {
			const node = {type, props: props || {}, children: children.flat(Infinity)};
			if (type === 'button' && typeof node.props.onClick === 'function') {
				buttons.push(node);
			}
			if (type === 'input' && typeof node.props.onChange === 'function') {
				inputs.push(node);
			}
			return node;
		},
		Fragment: 'fragment',
		useState: initial => {
			const index = slot++;
			if (!(index in states)) {
				states[index] = typeof initial === 'function' ? initial() : initial;
			}
			return [
				states[index],
				value => {
					states[index] = typeof value === 'function' ? value(states[index]) : value;
				}
			];
		},
		// Mount effects are collected once and fired by the test, not on every re-render.
		useEffect: callback => {
			if (!mounted) {
				effects.push(callback);
			}
		}
	};

	const apiFetch = async ({path, data}) => {
		requests.push({path, data});

		if (path === '/nggpro/v1/license-actions/current') {
			return {status: 'success', data: currentResponse};
		}

		return {status: 'success', message: 'ok'};
	};

	// __esModule matters: without it Babel's interop wraps `default` a second time and the
	// resulting call fails inside the component's own try/catch, which would hide the regression.
	const stubs = {
		react: {...react, __esModule: true, default: react},
		'@wordpress/api-fetch': {__esModule: true, default: apiFetch},
		'@wordpress/data': {useDispatch: () => ({showNotice: () => {}})},
		'@wordpress/i18n': {__: text => text},
		'@src/components/Icons': {SpinnerIcon: 'SpinnerIcon', SuccessStatusIcon: 'SuccessStatusIcon'},
		'@src/hooks/useLicenseRecheck': {
			useLicenseRecheck: () => ({
				recheck: async () => ({status: 'success', message: 'ok'}),
				isChecking: false
			})
		},
		'@src/utils/constants': {STORE_NAME: 'imagely/main-store', PRO_TYPE_INSTALLED: 'pro'},
		'@src/utils/licenseValidation': licenseValidation,
		'@src/utils/utm': {generateUTMParams: () => ''}
	};

	const LicenseTab = evaluate('components/Pages/PageSettings/tabs/LicenseTab.tsx', id => {
		if (id in stubs) {
			return stubs[id];
		}
		if (id.startsWith('@src/')) {
			return {}; // type-only imports, removed by Babel
		}
		throw new Error(`unexpected import ${id}`);
	}).default;

	const render = () => {
		slot = 0;
		buttons = [];
		inputs = [];
		LicenseTab({});
		mounted = true;
	};

	render();

	return {
		licenseValidation,
		requests,
		render,
		runMountEffects: () => {
			effects.forEach(effect => effect());
		},
		fill: async (id, value) => {
			const input = inputs.find(node => node.props.id === id);
			assert.ok(input, `the tab rendered an input #${id}`);
			input.props.onChange({target: {value}});
			render();
		},
		click: async label => {
			const button = buttons.find(node => textOf(node).includes(label));
			assert.ok(button, `the tab rendered a "${label}" button`);
			assert.ok(!button.props.disabled, `the "${label}" button is enabled`);

			// handleActivateLicense defers its status refresh with setTimeout; capture it instead of
			// waiting a second, and let the test fire it through runTimers().
			const realSetTimeout = globalThis.setTimeout;
			globalThis.setTimeout = callback => {
				timers.push(callback);
				return 0;
			};
			try {
				await button.props.onClick();
			} finally {
				globalThis.setTimeout = realSetTimeout;
			}

			await settle();
			render();
		},
		runTimers: async () => {
			const pending = timers.splice(0, timers.length);
			for (const callback of pending) {
				await callback();
			}
			await settle();
			render();
		}
	};
}

test('a status refresh that reports an active license opens the gallery gate', async () => {
	globalThis.window = proWindow(STALE_SNAPSHOT);

	const {licenseValidation, requests, runMountEffects} = mountLicenseTab({
		currentResponse: ACTIVE_RESPONSE
	});

	assert.equal(licenseValidation.canCreateGalleries(), false, 'gate is closed before the refresh');

	runMountEffects();
	await settle();

	assert.ok(
		requests.some(request => request.path === '/nggpro/v1/license-actions/current'),
		'the tab asked the server for the current license status'
	);
	assert.equal(globalThis.window.imagelyApp.licenseData.is_valid, true, 'the window snapshot was refreshed');
	assert.equal(globalThis.window.imagelyApp.licenseData.status, 'active');
	assert.equal(licenseValidation.canCreateGalleries(), true, 'Add New Gallery is allowed');
	assert.equal(licenseValidation.canCreateAlbums(), true, 'Add New Album is allowed');
});

test('activating a license in Settings opens the gallery gate without a reload', async () => {
	globalThis.window = proWindow(STALE_SNAPSHOT);

	// /current reports the new status once manage-license has verified the key.
	const {licenseValidation, requests, fill, click, runTimers} = mountLicenseTab({
		currentResponse: ACTIVE_RESPONSE
	});

	assert.equal(licenseValidation.canCreateGalleries(), false, 'gate is closed before activating');

	// The whole #570 flow, through the tab's own form: type the key, press Activate, and let the
	// deferred status refresh that handleActivateLicense schedules run.
	await fill('license-key', 'abc123');
	await click('Activate License');

	assert.ok(
		requests.some(
			request =>
				request.path === '/nggpro/v1/license-actions/manage-license' &&
				request.data?.action_perform === 'verify'
		),
		'the real activation handler ran'
	);

	await runTimers();

	assert.equal(globalThis.window.imagelyApp.licenseData.is_valid, true, 'the window snapshot was refreshed');
	assert.equal(licenseValidation.canCreateGalleries(), true, 'Add New Gallery is allowed');
	assert.equal(licenseValidation.canCreateAlbums(), true, 'Add New Album is allowed');
});

test('refreshing the license snapshot keeps fields the response does not carry', async () => {
	globalThis.window = proWindow(STALE_SNAPSHOT);

	const {runMountEffects} = mountLicenseTab({currentResponse: ACTIVE_RESPONSE});
	runMountEffects();
	await settle();

	assert.equal(globalThis.window.imagelyApp.licenseData.is_valid, true, 'the snapshot really was refreshed');
	assert.equal(globalThis.window.imagelyApp.licenseData.brand, 'photocrati', 'the storefront brand survives');
});

test('an inactive license leaves the gate closed', async () => {
	globalThis.window = proWindow(STALE_SNAPSHOT);

	const {licenseValidation, runMountEffects} = mountLicenseTab({
		currentResponse: {...ACTIVE_RESPONSE, status: 'expired', is_valid: false}
	});
	runMountEffects();
	await settle();

	assert.equal(globalThis.window.imagelyApp.licenseData.status, 'expired');
	assert.equal(licenseValidation.canCreateGalleries(), false);
});

test('deactivating the license closes the gate again without a reload', async () => {
	globalThis.window = proWindow({...ACTIVE_RESPONSE, brand: 'imagely'});

	const {licenseValidation, requests, click} = mountLicenseTab({currentResponse: ACTIVE_RESPONSE});

	assert.equal(licenseValidation.canCreateGalleries(), true, 'gate is open to begin with');

	// "Deactivate License" lives in the edit form, which the tab opens on "Edit License".
	await click('Edit License');
	await click('Deactivate License');

	assert.ok(
		requests.some(
			request =>
				request.path === '/nggpro/v1/license-actions/manage-license' &&
				request.data?.action_perform === 'deactivate'
		),
		'the real deactivation handler ran'
	);
	assert.equal(globalThis.window.imagelyApp.licenseData.is_valid, false, 'the window snapshot was cleared');
	assert.equal(globalThis.window.imagelyApp.licenseData.license_key, '');
	assert.equal(licenseValidation.canCreateGalleries(), false, 'the gate closes without a reload');
	assert.equal(globalThis.window.imagelyApp.licenseData.brand, 'imagely', 'unrelated fields are kept');
});
