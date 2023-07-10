<?php
  require ("functions.php");
  require ("init.php");

  echo "<p>heyo</p>";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_tmp_name = $_FILES["file"]["tmp_name"];
    $file_name = $_FILES["file"]["name"];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_content = file_get_contents($file_tmp_name);

    // PROCESS THE FILE AND POST CONTENT ✉

    //_________________________SIZE CHECK
    if (checkSize($_FILES["file"]["size"])){
      exit();
    }

    //_________________________EXTENSION CHECK
    else if ($file_extension != "csv" && $file_extension != "json"){
      echo "<p>Wrong file extension</p>";
      exit();
    }

    //_________________________CSV TO JSON CONVERSION
    if ($file_extension == "csv"){
      // $csv = file_get_contents($file);
      $array = array_map("str_getcsv", explode("\n", $file_content));
      $file_content_json = json_encode($array);
      print_r($file_content_json);
    }
    else if ($file_extension == "json"){
      $file_content_json = file_get_contents($file_tmp_name);
    }

    //_________________________JSON CONTENT CHECK
    if (!(array_key_exists('name', $file_content_json)) || 
      !(array_key_exists('name', $file_content_json)) ||
      !(array_key_exists('name', $file_content_json))){
        echo "<p>Missing JSON content</p>";
        exit();
      }

    // $request = 

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
      <input type="file" name="file" id="fileInput" type="file" accept=".csv,.json">
      <button type="submit">GO</button>
    </form>
  </body>
</html>