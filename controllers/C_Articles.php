<?php
require_once('model/m_articles.php');
require_once('functions/sql.php');
require_once('functions/view_helper.php');

class C_Articles extends C_Base
{
	protected function before()
	{
		parent::before();
		$this->menuActive = 'action_index';
	}

	// Главная страница
	public function action_index()
	{
		$this->title_page = 'Главная';              // Заголовок страницы
		$this->title .= '::' . $this->title_page;   // Заголовок сайта

		$array_num = [3, 5, 10];                    // Допустимые значения при выборе сортировки статей
		if ($num = $this->getParam('num', 5)) {
			if ($this->validateParam($num, $array_num)) {
				$_SESSION['num'] = $num;
			} else {
				$this->redirect($_SERVER['PHP_SELF']);
			}
		}
		//$this->check_and_record('num', 5, $array_num);     // Проверка пришедшего GET запроса, запись его в сессию и установка значения по умолчанию

		$count = articles_count();                  // Подсчет кол-ва статей в БД

		// Переменная равная отношению кол-ва статей в БД к требуемому кол-ву статей на одной странице
		$n = $count / $_SESSION['num'];

		// Проверка ГЕТ запроса, содержащего номер страницы
		if(isset($_GET['page'])) {

			$num_page = (int)$_GET['page'];         // Сохранение в переменную
			$n1 = ceil($n);                         // Округление в большую сторону

			// Проверка значения
			if($num_page > $n1 || $num_page <= 1) {
				$this->redirect('index.php');
			}
		}

        // Выборка статей в виде превью
		$articles = articles_getIntro(40, $_GET['page'], $_SESSION['num']);

        // Шаблон с выбором кол-ва статей на одной странице
        $sort = $this->template('view/templates/block/v_block_sort.php');

        // Шаблон постраничной навигации
        $nav = $this->template('view/templates/block/v_block_nav.php', ['n' => $n]);

        // Шаблон главной страницы
		$this->content = $this->template('view/templates/v_index.php', ['articles' => $articles, 'nav' => $nav, 'sort' => $sort]);
	}

	// Страница просмотра одной статьи
	public function action_article()
	{

        // Выборка одной статьи
		$article = articles_getOne($_GET['id']);

		$this->title_page = $article['title'];      // Заголовок страницы
		$this->title .= '::' . $this->title_page;   // Заголовок сайта

        // Шаблон одной статьи
		$this->content = $this->template('view/templates/v_article.php', ['article' => $article]);
	}
}