/**
 * Run: node --test adminApp/tests/ (Node 22.18+, which strips the TypeScript types itself).
 */
/* global console, setTimeout */ // the shared ESLint config gives globals to .js/.ts only
import assert from 'node:assert/strict';
import {test} from 'node:test';

import {deleteAllSettled} from '../src/utils/concurrency.ts';

console.error = () => {}; // the helper logs every failure it swallows

const restError = (status, code = 'ngg_boom') => ({code, message: `boom ${status}`, data: {status}});

test('one failure does not stop the rest of the batch', async () => {
	const attempted = [];
	const failures = await deleteAllSettled([1, 2, 3, 4, 5, 6], 5, async id => {
		attempted.push(id);
		if (id === 3) {
			throw restError(500);
		}
	});

	assert.deepEqual(attempted.sort(), [1, 2, 3, 4, 5, 6]);
	assert.equal(failures.length, 1);
	assert.equal(failures[0].error.data.status, 500);
	assert.equal(failures[0].item, 3);
});

test('a not-found 404 counts as deleted, not as a failure', async () => {
	const failures = await deleteAllSettled([1, 2], 5, async id => {
		if (id === 1) {
			throw restError(404, 'rest_image_not_found');
		}
	});

	assert.deepEqual(failures, []);
});

test('a 404 that is not the entity being missing is still a failure', async () => {
	// A WAF stripping the method override makes WordPress answer rest_no_route.
	const failures = await deleteAllSettled([1, 2], 5, async () => {
		throw restError(404, 'rest_no_route');
	});

	assert.equal(failures.length, 2);
});

test('never more than `limit` requests in flight', async () => {
	let inFlight = 0;
	let peak = 0;

	await deleteAllSettled(Array.from({length: 20}, (_, i) => i), 5, async () => {
		inFlight += 1;
		peak = Math.max(peak, inFlight);
		await new Promise(resolve => setTimeout(resolve, 1));
		inFlight -= 1;
	});

	assert.equal(peak, 5);
});
