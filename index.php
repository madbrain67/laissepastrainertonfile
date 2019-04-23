<?php

$target_dir = "uploads/";
$max_file = 1048576;
$errors = array();

if(!empty($_GET["sup"]) && file_exists($target_dir.$_GET["sup"])) {
    unlink($target_dir.$_GET["sup"]);
    header("Location: /index.php");
}


if(isset($_POST["submit"])) {

    for($p=0;$p<count($_FILES['upload']['name']);$p++) {

        $target_file = $target_dir . $_FILES["upload"]["name"][$p];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["upload"]["tmp_name"][$p]);
        if($check === false) {
            array_push($errors, "Le fichier n'est pas une image.");
            $uploadOk = 0;
        }


        if ($_FILES["upload"]["size"][$p] > $max_file) {
            array_push($errors, "le fichier depasse les 1Mo");
            $uploadOk = 0;
        }


        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            array_push($errors, "le fichier na pas l'extension autorisée JPG, JPEG, PNG & GIF");
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            array_push($errors, "Le fichier na pas pu etre uploader");
        } else {
            $rename = 'image'.uniqid().'.'.$imageFileType;
            if (move_uploaded_file($_FILES["upload"]["tmp_name"][$p], $target_dir.$rename)) {
                array_push($errors, "Le fichier ". $_FILES["upload"]["name"][$p]. " a bien été uploader sous le nom ".$rename);
            } else {
                array_push($errors, "le fichier na pas pu etre uploader.");
            }
            
        }

    }
}

$files = scandir($target_dir);
$nb_photos = 0;
for($i=0;$i<count($files);$i++) {
    if(!is_dir($files[$i])) {
        //echo '<pre>'.print_r($files, true).'</pre>';
        $nb_photos += 1;
    }
}


?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Laisse pas traîner ton File</title>
  </head>
  <body>
    <h1 class="text-center p-5">Laisse pas traîner ton File</h1>
    <div class="container">
        <div class="row">
        <?php 
        if($nb_photos > 0) {
            for($i=0;$i<count($files);$i++) {
                if(!is_dir($files[$i])) {
                    echo '<div class="col-3 p-2">
                        <div class="card">
                        <img src="/uploads/'.$files[$i].'" class="card-img-top" alt="'.$files[$i].'">
                        <div class="card-body">
                        <h5 class="card-title">'.$files[$i].'</h5>
                        <p class="card-text">url : /uploads/'.$files[$i].'</p>
                        <a href="?sup='.$files[$i].'" class="btn btn-primary">supprimer</a>
                        </div>
                        </div> 
                    </div>';
                }
            }
        }
        ?>
       </div> 
    </div>
    <div class="container mt-5">
        <div class="row">
        <div class="col-12 text-center">
        <?php
        if(isset($errors)) {
            for($e=0;$e<count($errors);$e++) {
                echo '<div class="mb-1">'.$errors[$e].'</div>';
            }
            echo '<div class="mb-5"></div>';
        }
        ?>
        <form method="post" action="#" enctype="multipart/form-data">
            <div>
                <label for="upload">Sélectionner le fichier à envoyer</label>
                <input type="file" id="upload" name="upload[]" accept="image/png, image/jpeg, image/gif, image/jpg" multiple>
                </div>
                <div>
                <input type="submit" name="submit" value="submit">
            </div>
        </form>
       </div> 
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
