<h1>All news</h1>
<a href="news/create/" style="text-decoration: none; font-size: 1.2em; color: black;">Add News</a><br>
<?php foreach ($news as $key => $value): ?>
	<a href="news/view/<?php echo $value['slug'] ?>"><?php echo $value['title'] ?></a> | <a href="news/edit/<?php echo $value['slug'] ?>">edit</a> | <a href="news/delete/<?php echo $value['slug'] ?>">X</a><br><br>
<?php endforeach ?>

