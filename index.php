<?php
if (
    $_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["uploadFiles"]) and
    $_POST["uploadFiles"] == "gönder"
) {
    $dosyaSecin = null;
    $adetErr = null;
    $boyuthatası = null;
    $uzantiHatasi = null;
    $dosyaKonumhatası = null;
    $successMesajı = null;
    $dosyaUzantilari = array("image/jpg", "image/jpeg", "image/png", "image/webp");

    $filesName = $_FILES["uploads"]["name"];

    if (count($filesName) == 0) {
        $dosyaSecin = "Lütfen Dosya/lar Seçiniz";
    } else {
        if (count($filesName) <= 10) {
            foreach ($_FILES["uploads"]["size"] as $file) {
                if ($file <= (1024 * 1024 * 10)) {
                } else {
                    $boyuthatası = "Dosyalar max 10 mb olmalıdır";
                }
            }
        } else {
            $adetErr = "Max 10 adet dosya yükleyebilirsiniz";
        }
        if (empty($boyuthatası) and empty($adetErr)) {
            foreach ($_FILES["uploads"]["type"] as $fileType) {
                if (in_array($fileType, $dosyaUzantilari)) {
                } else {
                    $uzantilar = "";
                    foreach ($dosyaUzantilari as $uzanti) {
                        $uzantilar .= " $uzanti  ";
                    }
                    $uzantiHatasi = "Dosyaların uzantısı : " . $uzantilar . " olmalıdır.";
                }
            }
        }
        if (empty($boyuthatası) and empty($adetErr) and empty($uzantiHatasi)) {
            for ($i = 0; $i < count($_FILES["uploads"]["name"]); $i++) {
                if ($_FILES["uploads"]["error"][$i] == 0) {
                    $hedefDosya = "./uploads/";
                    $dosyaName = $_FILES["uploads"]["name"][$i];
                    $dosyaNameDizisi = explode(".", $dosyaName);
                    $dosyaName = md5(time() . $dosyaNameDizisi[0]) . "." . $dosyaNameDizisi[1];
                    $dosyaDes = $hedefDosya . $dosyaName;
                    $dosyaSource = $_FILES["uploads"]["tmp_name"][$i];
                    move_uploaded_file($dosyaSource, $dosyaDes);
                } else {
                    $dosyaKonumhatası = "HATALI KAYIT";
                }
            }
            if (empty($dosyaKonumhatası)) {
                $successMesajı = "Dosyalar kontrollerden geçtikten sonra istenilen konuma adı değiştirilerek kaydedilmiştir";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-8 mx-auto">
                <h1 class="text-center text-dark">Files</h1>
                <form action="<?php echo ($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    <input type="file" class="border-1 border border-secondary form-control" id="uploads" name="uploads[]" multiple="multiple">
                    <button class="btn btn-outline-dark w-100" type="submit" value="gönder" name="uploadFiles">Gönder</button>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (!empty($successMesajı)) {
                            echo ("<span class='text-success'>$successMesajı</span>");
                        } else {
                            echo ("<span class='text-danger'>" . $dosyaSecin . " " . $adetErr . " " . $boyuthatası . " " . $uzantiHatasi . " " . " " . $dosyaKonumhatası . "</span>");
                        }
                    }
                    ?>
                </form>
            </div>
        </div>

    </div>
</body>

</html>