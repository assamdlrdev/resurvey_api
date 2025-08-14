<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Chitha Entry Esigned Pdf View</title>
</head>

<body>
    <div class="container pb-4">
        <script>
            function goback() {
                window.history.go(-2);
            }
        </script>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <p class="text-center mb-4">
                    <h5 class="text-center"><b>Chitha Entry Esigned Pdf View</b></h5>
                </p>
                <button class="btn btn-info" type="button" onclick="goback()">Go Back</button>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-12 cl-lg-12">
                <?php if ($file_path): ?>
                    <iframe src="<?= $file_path ?>" frameborder="0" style="overflow:hidden;height:800px;width:100%" height="800" width="100%">
                    <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    -->

</body>

</html>