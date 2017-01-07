<?php/*
Шаблон статьи
============================
$articles - статья
content - текст
date_time - дата загрузки статьи
*/?>
<section>
    <p>
        <?php echo nl2br($article['content']); ?>
    </p>
    <small>Дата добавления: <?php echo $article['date_time']; ?></small>
</section>