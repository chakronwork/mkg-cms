<h1><?= e($page['title']) ?></h1>
<?php foreach ($sections as $section): ?>
    <section class="mb-4">
        <h2 class="h4"><?= e($section['section_name']) ?></h2>
        <div><?= $section['content'] ?></div>
    </section>
<?php endforeach; ?>
