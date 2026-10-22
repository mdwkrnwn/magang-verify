const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

const source = fs.readFileSync('components/login/Script.php', 'utf8');
const scriptMatch = source.match(/<script>([\s\S]*?)<\/script>/);
assert.ok(scriptMatch, 'login component script exists');

function element(dataset = {}) {
    const listeners = {};
    const classes = new Set();
    return {
        dataset,
        value: '',
        type: 'password',
        required: false,
        hidden: false,
        placeholder: '',
        inputMode: '',
        attributes: {},
        classes,
        listeners,
        classList: {
            add: (...names) => names.forEach((name) => classes.add(name)),
            remove: (...names) => names.forEach((name) => classes.delete(name)),
        },
        addEventListener(name, callback) {
            listeners[name] = callback;
        },
        setAttribute(name, value) {
            this.attributes[name] = value;
        },
    };
}

const roles = ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik', 'mitra'];
const roleButtons = roles.map((role) => element({ role }));
const roleInput = element();
const identityLabel = element();
const identityInput = element();
const passwordSection = element();
const passwordInput = element();
const togglePassword = element();
const elements = {
    role: roleInput,
    identityLabel,
    login_id: identityInput,
    passwordSection,
    password: passwordInput,
    togglePassword,
};
let ready;
const document = {
    addEventListener(name, callback) {
        if (name === 'DOMContentLoaded') ready = callback;
    },
    querySelectorAll(selector) {
        return selector === '[data-role]' ? roleButtons : [];
    },
    getElementById(id) {
        return elements[id] ?? null;
    },
};

vm.runInNewContext(scriptMatch[1], { document, console });
assert.equal(typeof ready, 'function', 'login behavior waits for DOM readiness');
ready();

assert.equal(roleInput.value, 'mahasiswa');
assert.equal(passwordSection.hidden, false);
assert.equal(passwordInput.required, true);
assert.equal(identityInput.value, '', 'login id is not prefilled');
assert.equal(passwordInput.value, '', 'password is not prefilled');

identityInput.value = 'user-entered-id';
passwordInput.value = 'user-entered-password';
const mitraButton = roleButtons.find((button) => button.dataset.role === 'mitra');
mitraButton.listeners.click();
assert.equal(roleInput.value, 'mitra');
assert.equal(identityLabel.textContent, 'Kode Mitra');
assert.equal(identityInput.placeholder, 'Kode Mitra');
assert.equal(identityInput.inputMode, 'text');
assert.equal(identityInput.value, '', 'switching role clears prior account identifier');
assert.equal(passwordSection.hidden, true, 'Mitra does not see the password field');
assert.equal(passwordInput.required, false, 'Mitra can submit without a password');
assert.equal(passwordInput.disabled, true, 'hidden password does not participate in form submission');
assert.equal(passwordInput.value, '', 'switching role clears prior password');
assert.equal(mitraButton.attributes['aria-checked'], 'true');

for (const role of ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik']) {
    const button = roleButtons.find((candidate) => candidate.dataset.role === role);
    button.listeners.click();
    assert.equal(roleInput.value, role);
    assert.equal(passwordSection.hidden, false, `${role} sees the password field`);
    assert.equal(passwordInput.required, true, `${role} still requires the password`);
    assert.equal(passwordInput.disabled, false, `${role} submits the password field`);
}

console.log('Login role behavior checks passed (synthetic DOM; no server/database).');
