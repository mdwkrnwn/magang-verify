<?php

function select($name, $label, $all, $opts, $cur)
{
?>
    <div>
        <label
            for="<?= $name ?>"
            class="block text-xs font-semibold text-slate-800 mb-1.5"
        >
            <?= $label ?>
        </label>

        <select
            id="<?= $name ?>"
            name="<?= $name ?>"
            class="w-full h-11 px-3 rounded-lg border border-slate-200 bg-white text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value=""><?= $all ?></option>

            <?php foreach ($opts as $o): ?>
                <option
                    value="<?= e($o) ?>"
                    <?= (string)$cur === (string)$o ? 'selected' : '' ?>
                >
                    <?= e($o) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
<?php
}