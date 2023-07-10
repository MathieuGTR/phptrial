<?php
  include ("./functions.php");
  include ("./init.php");

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

      $lineContent = array_map("str_getcsv", explode("\n", $file_content));

      $headers = $lineContent[0];

      $jsonArray = array();
      $rowCount = count($lineContent);
      for ($i=1;$i<$rowCount;$i++) {
          foreach ($lineContent[$i] as $key => $column) {
              $jsonArray[$i][$headers[$key]] = $column;
          }
      } 

      echo "Array conversion : <br/>";
      print_r($jsonArray);
      echo "<br/><br/>";

      $final_json = json_encode(array_values($jsonArray), JSON_PRETTY_PRINT);

      echo "Final JSON file: <br/>";
      var_dump($final_json);
      echo "<br/><br/>";
    }

    //_________________________GET JSON CONTENT
    else if ($file_extension == "json"){
      echo "Final JSON file : <br />";
      $final_json = file_get_contents($file_tmp_name);
      var_dump($final_json);
      echo "<br/>";
    }

    //_________________________JSON CONTENT CHECK
    //_____TODO
    echo "<br/><br/>Final File-to-check: <br/>";
    $final_json_tocheck = json_decode($final_json, true);
    print_r ($final_json_tocheck);

    echo "<br/>________________<br/>";

    $file_content_ok = true;
    for ($i=0; $i < count($final_json_tocheck); $i++) { 
      if (
        !array_key_exists("name",$final_json_tocheck[$i])
        || !array_key_exists("surname",$final_json_tocheck[$i])
        || !array_key_exists("city",$final_json_tocheck[$i])
      )
      {
        echo "Il manque une valeur dans l'objet position ".$i." !<br/>";
        $file_content_ok = false;
      }
      else {
        echo "Valeurs OK dans l'objet position ".$i." !<br/>";
      }
    }

    //_________________________POST REQUEST
    if ($file_content_ok){

      $data = '{"data": '.$final_json.', "token": "'.API_TOKEN.'"}';
      var_dump($data);
      // initiate the curl request
      $request = curl_init();

      curl_setopt($request, CURLOPT_URL,API_URL);
      curl_setopt($request, CURLOPT_POST, 1);
      curl_setopt($request, CURLOPT_POSTFIELDS,$data);

      // catch the response
      curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($request);

      curl_close ($request);

      // do processing for the $response
      echo "<br/><br/>POST Request response : <br/>";
      print_r($response);

    }

    else{
      echo "<br/>Fichier invalide, requête POST non envoyée.";
    }
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