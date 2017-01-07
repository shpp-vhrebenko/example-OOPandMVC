<?php

// Выборка всех статей (у меня нигде не используется)
function articles_getAll()
{
    $sql = "SELECT * FROM `articles` ORDER BY `id_article` DESC";
    return sql_select($sql);
}

// Выборка всех статей, для списка (id и title)
function articles_getList()
{
    $sql = "SELECT `id_article`, `title` FROM `articles` ORDER BY `id_article` DESC";
    return sql_select($sql);
}

// Выборка всех статей в виде превью
function articles_getIntro($sub, $page, $app)
{
    $sub = (int)$sub;       // кол-во символов которое требуется вернуть
    $page = (int)$page;     // номер страницы

    // Постраничная навигация
    $page = !empty($page) ? $page : 1;
    $skip = ($page-1) * $app;

    $sql = "SELECT `id_article`, `title`, `date_time`, SUBSTRING(`content`, 1, '$sub') AS `content` FROM `articles` ORDER BY `id_article` DESC LIMIT $skip, $app";
    return sql_select($sql);
}

// возвращает кол-во статей в БД
function articles_count()
{
    $sql = "SELECT COUNT(*) AS `count` FROM `articles`";
    return sql_select($sql)['0']['count'];
}

// выборка одной статьи по id
function articles_getOne($id)
{
    $id = (int)$id;
    $sql = "SELECT * FROM `articles` WHERE `id_article` = '" . $id . "'";
    return sql_one_select($sql);
}

// Удаление статьи по ее id
function articles_delete($id)
{
    $id = (int)$id;
    $sql = "DELETE FROM `articles` WHERE `id_article` = '" . $id . "'";
    return sql_query($sql);
}

// Добавление статьи
function articles_add($title, $content)
{
    $sql = "INSERT INTO `articles` (`title`, `content`) VALUES ('%s', '%s')";
    $query = sprintf($sql, sql_escape($title), sql_escape($content));
    return sql_query($query);
}

// Редактирование статьи
function articles_update($id, $title, $content)
{
    $id = (int)$id;
    $sql = "UPDATE `articles` SET `title`='%s', `content`='%s' WHERE  `id_article`='%s'";
    $query = sprintf($sql, sql_escape($title), sql_escape($content), $id);
    return sql_query($query);
}


// Функция проверки введенных данных
function articles_check($title, $content)
{
    $title = trim($title);
    $content = trim($content);


    // Проверка на пустую строку заголовка
    if(mb_strlen($title) == '') {
        $_SESSION['notice'] = 'Вы не ввели заголовок';
        return false;
    }

    // Проверка на пустую строку текста
    if(mb_strlen($content) == '') {
        $_SESSION['notice'] = 'Вы не ввели текст статьи';
        return false;
    }

    // Проверка на длинну заголовка
    if(mb_strlen($title) > 100) {
        $_SESSION['notice'] = 'Заголовок не должен превышать 100 символов';
        return false;
    }
    return true;
}