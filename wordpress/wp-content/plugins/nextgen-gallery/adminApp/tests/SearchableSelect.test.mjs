/**
 * The Imagely block's gallery/album picker closes when the user leaves it (#1268).
 *
 * Run: yarn --cwd adminApp test   (node --test, jsdom, no browser, no WordPress)
 *
 * SearchableSelect and useClickOutside are transpiled with the app's Babel presets and rendered
 * with React into a jsdom page. The block editor renders blocks inside an iframed canvas, so most
 * tests mount the picker inside an iframe while the global `document` is the parent page, the
 * same split the editor has: events raised in the canvas never reach the parent document.
 */
import assert from 'node:assert/strict';
import {readFileSync} from 'node:fs';
import {createRequire} from 'node:module';
import {dirname, resolve} from 'node:path';
import {after, afterEach, test} from 'node:test';
import {fileURLToPath} from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const appRequire = createRequire(resolve(here, '../package.json'));
const babel = appRequire('@babel/core');
const {JSDOM} = appRequire('jsdom');

const dom = new JSDOM('<!doctype html><html><body></body></html>', {pretendToBeVisual: true});
for (const key of ['window', 'document', 'HTMLElement', 'Node', 'Element']) {
	globalThis[key] = dom.window[key];
}
globalThis.IS_REACT_ACT_ENVIRONMENT = true;
const page = dom.window.document;

const React = appRequire('react');
const {createRoot} = appRequire('react-dom/client');
const {act} = React;

function load(relativePath, stubs) {
	const file = resolve(here, '../src', relativePath);
	const {code} = babel.transformSync(readFileSync(file, 'utf8'), {
		filename: file,
		babelrc: false,
		configFile: false,
		presets: [
			[appRequire.resolve('@babel/preset-env'), {targets: {node: 'current'}, modules: 'commonjs'}],
			appRequire.resolve('@babel/preset-react'),
			appRequire.resolve('@babel/preset-typescript')
		]
	});
	const module = {exports: {}};
	const localRequire = id => {
		if (id === 'react') {
			return React;
		}
		if (id in stubs) {
			return stubs[id];
		}
		throw new Error(`unexpected import ${id}`);
	};
	new Function('require', 'module', 'exports', code)(localRequire, module, module.exports);
	return module.exports;
}

const hookModule = load('hooks/useClickOutside.ts', {});
const SearchableSelect = load('components/Common/SearchableSelect.tsx', {
	'@src/components/Icons': {ChevronDownIcon: () => null, SearchIcon: () => null},
	'@src/hooks/useClickOutside': hookModule,
	'@wordpress/i18n': {__: text => text, sprintf: (format, ...args) => `${format} ${args.join(' ')}`},
	'./LabelInput': {default: ({children}) => React.createElement('label', null, children)}
}).default;

const options = [
	{value: 1, label: 'Alpha'},
	{value: 2, label: 'Beta'},
	{value: 3, label: 'Gamma'}
];

// Compare booleans, never DOM nodes: a failing assert would util.inspect the whole jsdom graph.
const isOpen = picker => Boolean(picker.listbox());

let cleanup = () => {};
afterEach(() => {
	cleanup();
	cleanup = () => {};
});
// jsdom's visual timers would keep the process alive.
after(() => dom.window.close());

/**
 * Mount the picker, with a focusable "next block" after it, inside an iframe (the editor canvas)
 * or straight in the page.
 */
function mount({inIframe = true, onChange = () => {}} = {}) {
	let doc = page;
	let frame = null;
	if (inIframe) {
		frame = page.createElement('iframe');
		page.body.appendChild(frame);
		doc = frame.contentDocument;
	}
	const container = doc.createElement('div');
	const nextBlock = doc.createElement('p');
	nextBlock.setAttribute('contenteditable', 'true');
	nextBlock.textContent = 'Paragraph below the block';
	doc.body.append(container, nextBlock);

	const root = createRoot(container);
	act(() => {
		root.render(
			React.createElement(SearchableSelect, {
				id: 'gallery',
				name: 'gallery',
				value: null,
				onChange,
				options
			})
		);
	});
	cleanup = () => {
		act(() => root.unmount());
		container.remove();
		nextBlock.remove();
		frame?.remove();
	};

	const win = doc.defaultView;
	const input = container.querySelector('[role="combobox"]');
	const listbox = () => container.querySelector('[role="listbox"]');
	const chevron = () => container.querySelectorAll('button')[1];
	// Returns false when the handler cancelled the event (dispatchEvent's own return value).
	const mousedown = el => {
		let notCancelled = true;
		act(() => {
			notCancelled = el.dispatchEvent(
				new win.MouseEvent('mousedown', {bubbles: true, cancelable: true})
			);
		});
		return notCancelled;
	};
	// A pointer press as Safari does it: a script-fired mousedown never moves focus, so apply the
	// browser default by hand. Unless cancelled, the press takes focus off the field without
	// focusing the pressed button (relatedTarget null), then the click fires.
	const pressWithoutFocusingButton = el => {
		if (mousedown(el)) {
			act(() => doc.activeElement?.blur());
		}
		click(el);
	};
	const click = el =>
		act(() => {
			el.dispatchEvent(new win.MouseEvent('click', {bubbles: true, cancelable: true}));
		});
	const focus = el => act(() => el.focus());
	const open = () => {
		focus(input);
		assert.ok(Boolean(listbox()), 'the list opens when the field is focused');
	};

	return {
		doc,
		container,
		nextBlock,
		input,
		listbox,
		chevron,
		mousedown,
		click,
		pressWithoutFocusingButton,
		focus,
		open
	};
}

test('focus moving to the next block closes the open list', () => {
	const picker = mount();
	picker.open();

	picker.focus(picker.nextBlock);

	assert.equal(isOpen(picker), false, 'the list must close once focus leaves the picker');
});

test('a mousedown on the next block inside the editor canvas closes the open list', () => {
	const picker = mount();
	picker.open();

	// The next block is not focusable here, so only the outside-click path can close the list.
	picker.nextBlock.removeAttribute('contenteditable');
	picker.mousedown(picker.nextBlock);

	assert.equal(isOpen(picker), false, 'the click-outside listener must watch the canvas document');
});

test('useClickOutside listens on the document its element lives in', () => {
	const frame = page.createElement('iframe');
	page.body.appendChild(frame);
	const doc = frame.contentDocument;
	const container = doc.createElement('div');
	const outside = doc.createElement('div');
	doc.body.append(container, outside);

	let calls = 0;
	const Probe = () => {
		const ref = hookModule.useClickOutside(() => calls++, true);
		return React.createElement('div', {ref}, 'inside');
	};
	const root = createRoot(container);
	act(() => root.render(React.createElement(Probe)));
	cleanup = () => {
		act(() => root.unmount());
		frame.remove();
	};

	const fire = el =>
		act(() => {
			el.dispatchEvent(new doc.defaultView.MouseEvent('mousedown', {bubbles: true}));
		});
	fire(container.firstChild);
	assert.equal(calls, 0, 'a click inside does not count');
	fire(outside);
	assert.equal(calls, 1, 'a click outside, in the same iframe document, calls the handler');
});

test('picking an option still selects it and closes the list', () => {
	let picked = null;
	const picker = mount({onChange: value => (picked = value)});
	picker.open();

	const beta = [...picker.listbox().querySelectorAll('[role="option"]')][1];
	picker.mousedown(beta);

	assert.equal(picked, 2);
	assert.equal(isOpen(picker), false);
});

test('focus moving inside the picker keeps the list open', () => {
	const picker = mount();
	picker.open();

	// Browsers that focus a clicked button move focus from the field to the chevron.
	picker.focus(picker.chevron());

	assert.equal(isOpen(picker), true, 'moving between the field and its buttons is not leaving');
});

test('the chevron closes the open list without the field losing focus first', () => {
	const picker = mount();
	picker.open();

	// The chevron keeps focus on the field, so no blur closes the list before the click
	// toggles it (which would reopen it on browsers that do not focus a clicked button).
	assert.equal(picker.mousedown(picker.chevron()), false, 'the chevron cancels its mousedown');
	picker.pressWithoutFocusingButton(picker.chevron());

	assert.ok(picker.doc.activeElement === picker.input, 'focus stays on the field');
	assert.equal(isOpen(picker), false, 'the chevron closes the list instead of reopening it');
});

test('a press on the open list keeps focus on the field', () => {
	const picker = mount();
	picker.open();

	// The list's own area (scrollbar, "Loading more…" row) must not blur the field and close it.
	assert.equal(picker.mousedown(picker.listbox()), false, 'the list cancels its mousedown');
	assert.equal(isOpen(picker), true);
});

test('the chevron opens the list and moves focus to the field', () => {
	const picker = mount();

	picker.pressWithoutFocusingButton(picker.chevron());

	assert.equal(isOpen(picker), true, 'the chevron opens the list');
	assert.ok(
		picker.doc.activeElement === picker.input,
		'focus is on the field, so leaving it later closes the list'
	);

	picker.focus(picker.nextBlock);
	assert.equal(isOpen(picker), false);
});

test('outside the iframe, a click elsewhere on the page still closes the list', () => {
	const picker = mount({inIframe: false});
	picker.open();

	const elsewhere = page.createElement('div');
	page.body.appendChild(elsewhere);
	picker.mousedown(elsewhere);
	elsewhere.remove();

	assert.equal(isOpen(picker), false);
});
