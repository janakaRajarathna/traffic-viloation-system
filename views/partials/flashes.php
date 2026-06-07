<?php foreach (get_flashes() as $label => $messages): ?>
    <?php foreach ($messages as $message): ?>
        <div class="rounded-xl px-4 py-3 text-sm font-medium mb-4 <?= $label === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
            <?= e($message) ?>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
