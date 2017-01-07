<?php
require_once('model/m_articles.php');
require_once('functions/sql.php');
require_once('functions/view_helper.php');

class C_ConsoleEditor extends C_Base
{

    // Консоль редактора
    public function action_index()
    {
        $this->title_page = 'Консоль редактора';    // Заголовок страницы
        $this->title .= '::' . $this->title_page;   // Заголовок сайта

        // Проверка существования ГЕТ запроса
        if(isset($_GET['delete'])) {

            // Удаление статьи
            if(articles_delete($_GET['delete'])) {

                // Запись сообщения об успешном удалении и редирекет
                $_SESSION['notice'] = 'Статья успешно удаленна';
                $this->redirect('index.php?c=editor&act=editor');
            }
            else {
                // Запись сообщения в случаи ошибки
                $_SESSION['notice'] = 'Ошибка';
            }
        }

        // Выборка всех статей в виде списка
        $articles = articles_getList();

        // Шаблон консоли редактора
        $this->content = $this->template('view/templates/v_editor.php', ['articles' => $articles]);
    }

    // Страница создания новой статьи
    public function action_new()
    {
        $this->title_page = 'Новая статья';         // Заголовок страницы
        $this->title .= '::' . $this->title_page;   // Заголовок сайта

        // Проверка отправки формы
        if(!empty($_POST) && isset($_POST['title']) && isset($_POST['content'])) {

            // Проверка введенных данных
            if(articles_check($_POST['title'], $_POST['content'])) {

                // Добавление данных в БД
                articles_add($_POST['title'], $_POST['content']);

                // Запись в сессию сообщеня об успешной загрузке
                $_SESSION{'notice'} = 'Статья успешно загружена';
                $this->redirect('index.php?c=editor&act=editor');
            } else {

                // Если данные не прошли проверку, сохраняем их для повторного вывода в форму
                $_SESSION['title'] = $_POST['title'];
                $_SESSION['content'] = $_POST['content'];
                $this->redirect('index.php?c=editor&act=new');
            }
        }

        // Шаблон добавления новой статьи
        $this->content = $this->template('view/templates/v_new.php');
    }

    // Страница редактирования статьи
    public function action_edit()
    {
        $this->title_page = 'Редактирование статьи';    // Заголовок страницы
        $this->title .= '::' . $this->title_page;       // Заголовок сайта

        // Редирект, если id не передан
        if(empty($_GET['id'])) {
            $this->redirect('index.php?c=editor&act=editor');
        }

        // Выборка одной статьки, по id
        $article = articles_getOne($_GET['id']);
        $id = $_GET['id'];

        // Проверка отправки формы
        if(!empty($_POST) && isset($_POST['title']) && isset($_POST['content'])) {

            // Сохрание введенных данных в переменную
            $title_new = $_POST['title'];
            $content_new = $_POST['content'];

            // Проверка введенных данных
            if(articles_check($title_new, $content_new)) {

                // Обновление введенных данных в БД
                articles_update($id, $title_new, $content_new);

                // Запись в сессию сообщеня об успешном редактировании
                $_SESSION['notice'] = 'Статья успешно отредактирована';
                $this->redirect('index.php?c=editor&act=editor');
            } else {
                $this->redirect("index.php?c=editor&act=edit&id=$id");
            }
        }

        // Шаблон редактирования статьи
        $this->content = $this->template('view/templates/v_edit.php', ['article' => $article]);
    }
}