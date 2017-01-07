<?php

// базовый контроллека сайта
abstract class C_Base extends C_Controller
{
	protected $title;		// заголовок сайта
	protected $content;		// содержание страницы
	protected $title_page;  // заголовок страницы

	function __construct()
	{
		// создание линка подключение к БД
		sql_connect();

		// Языковая настройка.
		setlocale(LC_ALL, 'ru_RU.UTF-8');
		mb_internal_encoding('UTF-8');

		// запуск сесси
		session_start();
	}

	protected function before()
	{
		$this->title = 'Мой сайт';	// заголовок сайта
		$this->title_page = '';		// заголовок страницы
		$this->content = '';        // содержимое страницы
	}

    // Функция для проверки ГЕТ запроса, записи его в сессию, значением по умолчанию и массивом с которым будет идти сравнение
    // $key - ключ сесси и ГЕТ запроса
    // $default - значение по умолчанию
    // $array - массив для проверки
/*    protected function check_and_record($key, $default, $array)
    {
        // проверка существования требуемого ГЕТ запроса
        if(isset($_GET[$key])) {

            // проверка пришедшего ГЕТ запроса, осуществляется поиск значения в массиве
            if(in_array($_GET[$key], $array)) {
                $_SESSION[$key] = $_GET[$key];
                $this->redirect($_SERVER['PHP_SELF']);
            }
        }

        // значение по умолчанию
        if($_SESSION[$key] == null) {
            $_SESSION[$key] = $default;
        }
    }*/


    // получаем параметр
    protected function getParam($key, $default = null, $validate = null)
    {
    	$val = isset($_GET[$key]) ? $_GET[$key] : $default;
    	if (null === $validate) {
    		return $val;
    	return $val;
    }

    // проверяем параметр
    protected function validateParam($val, $validate, $strict = false)
    {
    	if (is_array($validate)) {
    		return in_array($val, $validate);
    	}
    	return $strict ? $val === $validate : $val == $validate;
    }


	// генерация базового шаблона
	public function render()
	{/*
		if (isset($this->menuActive)) {
			$menu = $this->template('view/v_top_links.php', $menuActive = $this->menuActive);
		}*/
		$vars = ['title' => $this->title, 'content' => $this->content, 'title_page' => $this->title_page];
		$page = $this->template('view/v_main.php', $vars);
		echo $page;
	}
}