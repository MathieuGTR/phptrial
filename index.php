<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $file_tmp_name = $_FILES["file"]["tmp_name"];
  $file_name = $_FILES["file"]["name"];
  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_content = file_get_contents($file_tmp_name);

  // PROCESS THE FILE AND POST CONTENT ✉

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File handler</title>
</head>

<body>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="file" id="fileInput" type="file">
    <button type="submit">GO</button>
  </form>
</body>

</html>