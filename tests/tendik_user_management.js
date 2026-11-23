'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

const template = fs.readFileSync(
    `${__dirname}/../pages/dashboard/tendik/users/create.php`,
    'utf8'
);
const scriptMatch = template.match(/<script>\s*([\s\S]*?)\s*<\/script>/);
assert.ok(scriptMatch, 'create page contains its role-dependent script');

const roleInputs = ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik'].map((value) => ({
    value,
    checked: false,
    disabled: false,
    listeners: {},
    addEventListener(event, callback) {
        this.listeners[event] = callback;
    },
}));
const makeField = () => ({ hidden: true });
const makeInput = () => ({ required: false, disabled: true });
const elements = {
    'nim-field': makeField(),
    'nidn-field': makeField(),
    'nip-field': makeField(),
    nim: makeInput(),
    nidn: makeInput(),
    nip: makeInput(),
    'role-selection-error': { hidden: true },
};
let submitHandler;
const form = {
    querySelectorAll() {
        return roleInputs;
    },
    addEventListener(event, callback) {
        if (event === 'submit') submitHandler = callback;
    },
};
const document = {
    querySelector() {
        return form;
    },
    getElementById(id) {
        return elements[id];
    },
};
vm.runInNewContext(scriptMatch[1], { document });

const choose = (role, checked = true) => {
    const input = roleInputs.find((candidate) => candidate.value === role);
    input.checked = checked;
    input.listeners.change();
};

assert.equal(elements['nip-field'].hidden, true);
assert.equal(elements.nip.required, false);
assert.equal(elements.nip.disabled, true);
assert.equal(
    roleInputs.find((input) => input.value === 'koordinator_magang').disabled,
    false,
    'Coordinator role is selectable before choosing Dosen'
);

choose('koordinator_magang');
assert.equal(roleInputs.find((input) => input.value === 'dosen').checked, true);
assert.equal(elements['nidn-field'].hidden, false, 'Coordinator uses the Dosen NIDN field');
assert.equal(elements.nidn.required, true);
assert.equal(submitHandler !== undefined, true);
choose('koordinator_magang', false);
choose('dosen', false);

choose('tendik');
assert.equal(elements['nip-field'].hidden, false, 'NIP field appears for Tendik');
assert.equal(elements.nip.required, true, 'NIP is required for Tendik');
assert.equal(elements.nip.disabled, false, 'NIP is submitted for Tendik');
assert.equal(roleInputs.find((input) => input.value === 'mahasiswa').checked, false);
assert.equal(roleInputs.find((input) => input.value === 'dosen').checked, false);

choose('dosen');
assert.equal(elements['nip-field'].hidden, true, 'NIP field hides when switching to Dosen');
assert.equal(elements.nip.required, false, 'NIP is no longer required outside Tendik');
assert.equal(elements.nip.disabled, true, 'NIP is excluded outside Tendik');
assert.equal(elements['nidn-field'].hidden, false, 'NIDN field appears for Dosen');

const submit = () => {
    let prevented = false;
    submitHandler({ preventDefault() { prevented = true; } });
    return prevented;
};
choose('dosen', false);
choose('koordinator_magang');
roleInputs.find((input) => input.value === 'dosen').checked = false;
assert.equal(submit(), true, 'coordinator-only selection is blocked');
choose('dosen');
assert.equal(submit(), false, 'Dosen plus coordinator selection is allowed');

console.log('Tendik create-form behavior checks passed.');
