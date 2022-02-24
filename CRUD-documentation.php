<?php

delete {
	controllers> Welcome.php;
	views > welcome_message.php;
}

<!-- --------------------   CRUD APP   ------------------------- -->
config->routes.php {
	//$route['default_controller'] = 'welcome';

	$route['news'] = 'news';
}

create	views {
	folder teplates {
		header.php
		footer.php
	}

	folder news {
		index.php
		edit.php
		update.php
		delete.php
	}
}

controllers {
	create file -> News.php {
		<?php 

		defined('BASEPATH') OR exit('No direct script access allowed!');

		class News extends CI_Controller {

			public function __construct() {
				parent::__construct();
			}

			public function index() {
				$data['title'] = "All news worked!";

				$this->load->view('templates/header', $data);
				$this->load->view('news/index', $data);
				$this->load->view('templates/footer');
			}
		}

		?>
	}
	
}

<!-- ----------------------Подключение к Database------------------- -->
models-> create News_model.php {
	<?php 
	class News_model extends CI_Model {
		public function __construct() {
			$this->load->database();
		}

		public function getNews($slug = FALSE) {
			if($slug === FALSE) {
				$query = $this->db->get('news');
				return $query->result_array();
			}

			$query = $thiis->db->get_where('news', array('slug' => $slug));
			return $query->row_array();
		}
	}
	 
}

<!-- --------------------CRUD: Просмотр каждой новости по отдельности ------------------------- -->
config-> routes.php {
	$route['news/(:any)'] = 'news/view/$1';
}

controllers-> News.php {
	public function view($slug = NULL) {
		$data['news_item'] = $this->news_model->getNews($slug);

		if(empty($data['news_item'])) {
			show_404;
		}

		$data['title'] = $data['news_item']['title'];
		$data['content'] = $data['news_item']['text'];
	}  
}

news-> create view.php {
	<h3>
		<?php echo $title ?>
	</h3>
	<p>
		<br><?php echo $content ?>
	</p>
}


<!-- ---------------------------------Frienly URL------------------------ --><?php
create file .htaccess {
	<IfModule mod_rewrite.c>
	        RewriteEngine On
	        RewriteBase /

	        # Removes index.php from ExpressionEngine URLs
	        RewriteCond %{THE_REQUEST} ^GET.*index\.php [NC]
	        RewriteCond %{REQUEST_URI} !/system/.* [NC]
	        RewriteRule (.*?)index\.php/*(.*) /$1$2 [R=301,NE,L]

	        # Directs all EE web requests through the site index file
	        RewriteCond %{REQUEST_FILENAME} !-f
	        RewriteCond %{REQUEST_FILENAME} !-d
	        RewriteRule ^(.*)$ /index.php/$1 [L]        
	</IfModule>
}						/**/

views>news>index.php {

	<?php foreach ($news as $key => $value): ?>
		<a href="news/view/<?php echo $value['slug'] ?>">
			<?php echo $value['title'].'<br>'; ?>
		</a>
	<?php endforeach ?>

}

<!-- -----------CRUD: Страница создания новостей на сайте---------- -->

views/news/create.php {
	<form action="news/create/" method="post">
		<input type="input" name="slug" placeholder="slug">
		<input type="input" name="title" placeholder="title">
		<textarea name="text" id="" cols="30" rows="10" placeholder="text"></textarea>
		<input type="submit" name="submit">
	</form>
}

config>routes.php {
	$route['news/create'] = 'news/create';
}

controllers>News.php {
		public function create() {
			$data['title'] = "Add News";

			/*получить данные из формы*/
			if ($this->input->post('slug') && $this->input->post('title') && $this->input->post('text')) {

				$slug = $this->input->post('slug');
				$title = $this->input->post('title');
				$text = $this->input->post('text');

				if ($this->news_model->setNews($slug, $title, $text)) {
					$this->load->view('templates/header', $data);
					$this->load->view('news/success', $data);
					$this->load->view('templates/header');
				}
			} 

			else {
				$this->load->view('templates/header', $data);
				$this->load->view('news/create', $data);
				$this->load->view('templates/footer');
			}

		}
	}

}

