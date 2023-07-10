<?php
  include ("functions.php");
  include ("init.php");

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $file_tmp_name = $_FILES["file"]["tmp_name"];
    $file_name = $_FILES["file"]["name"];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_content = file_get_contents($file_tmp_name);

    // PROCESS THE FILE AND POST CONTENT ✉

    //_________________________SIZE CHECK
    if ($_FILES["file"]["size"] > 2097152){
      echo "<p>File too big !</p> <br/>";
      echo "<a href='index.php'>Click here to retry !</a> <br/>";
      exit();
    }

    //_________________________EXTENSION CHECK
    //_________________________Note : check already done in HTML form below
    if ($file_extension != "csv" && $file_extension != "json"){
      echo "<p>Wrong file extension</p>";
      echo "<a href='index.php'>Click here to retry !</a> <br/>";
      exit();
    }

    //_________________________CSV TO JSON CONVERSION
    if ($file_extension == "csv"){
      echo "CSV file : <br/>";
      print_r($file_content);
      echo "<br/><br/>";
      // $csv = file_get_contents($file);
      $array = array_map("str_getcsv", explode("\n", $file_content));
      $file_content_json = json_encode($array);
      echo "converted to JSON : <br/>";
      print_r($file_content_json);
    }
    else if ($file_extension == "json"){
      echo "JSON file : <br />";
      $file_content_json = file_get_contents($file_tmp_name);
      print_r($file_content_json);
    }

    //_________________________JSON CONTENT CHECK
    if (!(array_key_exists('name', $file_content_json)) || 
      !(array_key_exists('surname', $file_content_json)) ||
      !(array_key_exists('city', $file_content_json))){
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
  <!--CSS Styles-->
  <link rel="stylesheet" href="style/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

  <body>
    <!--Title-->
    <section id="title">
      <h1>File handler</h1>
    </section>
    <!--Form-->
    <section id="form">
    <form method="POST" enctype="multipart/form-data">
      <!--HTML file extension validation added-->
      <input type="file" name="file" id="fileInput" type="file" accept=".csv,.json">
      <button type="submit">GO</button>
    </form>
    </section>
    <!--Informations-->
    <section id="infos"></section>
    
  </body>
</html>