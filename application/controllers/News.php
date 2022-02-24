<?php 

defined('BASEPATH') OR exit('No direct script access allowed!');

class News extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('news_model');
	}

	public function index() {
		$data['title'] = "First news page!";
		$data['news'] = $this->news_model->getNews();

		$this->load->view('templates/header', $data);
		$this->load->view('news/index', $data);
		$this->load->view('templates/footer');
	}

	public function view($slug = NULL) {
		$data['news_item'] = $this->news_model->getNews($slug);

		if(empty($data['news_item'])) {
			show_404();
		}

		$data['title'] = $data['news_item']['title'];
		$data['content'] = $data['news_item']['text'];

		$this->load->view('templates/header', $data);
		$this->load->view('news/view', $data);
		$this->load->view('templates/footer');
	}

/*--------------CREATE----------------------------*/
	public function create() {
		$data['title'] = "Add News";

		/*получить данные из формы*/
		if ($this->input->post('slug') && $this->input->post('title') && $this->input->post('text')) {

			$slug = $this->input->post('slug');
			$title = $this->input->post('title');
			$text = $this->input->post('text');

			if ($this->news_model->setNews($slug, $title, $text))
				echo "News successfully added!";
		} 

		else {
			$this->load->view('templates/header', $data);
			$this->load->view('news/create', $data);
			$this->load->view('templates/footer');
		}

	}

	/*----------------------EDIT NEWS----------------------*/
	public function edit($slug = NULL) {
		$data['title'] = "Edit news";
		$data['news_item'] = $this->news_model->getNews($slug);

		/*if(empty($data['news_item'])) {
			show_404();
		}*/

		$data['title_news'] = $data['news_item']['title'];
		$data['content_news'] = $data['news_item']['text'];
		$data['slug_news'] = $data['news_item']['slug'];

		if ($this->input->post('slug') && $this->input->post('title') && $this->input->post('text')) {

			$slug = $this->input->post('slug');
			$title = $this->input->post('title');
			$text = $this->input->post('text');

			if($this->news_model->updateNews($slug, $title, $text)) {
				echo "News successfully edited!";
			}

		}

		$this->load->view('templates/header', $data);
		$this->load->view('news/edit', $data);
		$this->load->view('templates/footer');
	}

	/*----------------------DELETE NEWS----------------------*/
	public function delete($slug = NULL) {
		$data['news'] = $this->news_model->getNews($slug);

		if(empty($data['news'])) {
					show_404();
		}

		$data['title'] = "Delete news";
		$data['result'] = "Deletion error".$data['news']['title'];

		if($this->news_model->deleteNews($slug)) {
			$data['result'] = $data['news']['title']." successfully deleted";
		}

		$this->load->view('templates/header', $data);
		$this->load->view('news/delete', $data);
		$this->load->view('templates/footer');
	}


}

?>