/**
 * GitHub #1164: "Reload Preview" re-requested the identical preview URL, so a URL-keyed page cache or CDN
 * in front of the site kept serving the first render. Every preview load now carries a fresh `_cache` token.
 *
 * Run: node --experimental-strip-types --test tests/   (from adminApp/, Node 22.6+)
 */
import assert from 'node:assert/strict';
import {test} from 'node:test';

import {
	PREVIEW_CACHE_PARAM,
	createPreviewCacheToken,
	withPreviewCacheBuster
} from '../src/utils/previewCacheBuster.ts';

const galleryUrl =
	'https://example.com/?ngg-preview=1&nonce_preview=abc123&display_type=photocrati-nextgen_basic_thumbnails&gallery_ids=7&images_per_page=20';

test('a gallery preview URL gets a cache-busting token', () => {
	const busted = new URL(withPreviewCacheBuster(galleryUrl, 'tok1'));

	assert.equal(PREVIEW_CACHE_PARAM, '_cache');
	assert.equal(busted.searchParams.get('_cache'), 'tok1');
});

test('reloading with a new token requests a different URL', () => {
	const first = withPreviewCacheBuster(galleryUrl, createPreviewCacheToken(0));
	const second = withPreviewCacheBuster(galleryUrl, createPreviewCacheToken(1));

	assert.notEqual(first, galleryUrl);
	assert.notEqual(first, second);
});

test('tokens differ between reloads even within the same millisecond', () => {
	assert.notEqual(createPreviewCacheToken(0), createPreviewCacheToken(1));
});

test('every other preview parameter is kept unchanged and in order', () => {
	const original = new URL(galleryUrl);
	const busted = new URL(withPreviewCacheBuster(galleryUrl, 'tok1'));
	busted.searchParams.delete('_cache');

	assert.equal(busted.origin + busted.pathname, original.origin + original.pathname);
	assert.deepEqual([...busted.searchParams.entries()], [...original.searchParams.entries()]);
});

test('an existing _cache value (album previews) is replaced, not duplicated', () => {
	const albumUrl = 'https://example.com/?ngg-preview=1&src=albums&ids=3&_cache=1-2-3';
	const busted = new URL(withPreviewCacheBuster(albumUrl, 'tok2'));

	assert.deepEqual(busted.searchParams.getAll('_cache'), ['tok2']);
	assert.equal(busted.searchParams.get('ids'), '3');
});

test('a URL without a query string gets one', () => {
	assert.equal(
		withPreviewCacheBuster('https://example.com/', 'tok3'),
		'https://example.com/?_cache=tok3'
	);
});
