<?php
include 'util/conn.php';

$stmt = $conn->prepare("SELECT * FROM products ORDER BY id");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>InnovAI Solutions</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="assets/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="assets/css/fonts.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="assets/css/styles.css" rel="stylesheet" />
        <style>
            #mainNav{
                background-color: #fff !important;
            }
            #mainNav .navbar-brand{
                color: #000 !important;
            }
            #mainNav .nav-link:hover{
                color: #64a19d !important;
            }
            #mainNav .nav-link{
                color: #000 !important;
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-shrink" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="index.php">InnovAI Solutions</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/products.php">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="/feedback.php">Feedback</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <section class="projects-section bg-light" id="products">
            <div class="container">
            <?php foreach ($products as $row): ?>
                <?php $attributes = unserialize($row['attributes']); ?>
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 col-md-12 mb-4">
                        <img class="img-fluid rounded" src="uploads/<?= $row['img'] ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <h2 class="text-dark"><?= htmlspecialchars($row['name']); ?></h2>
                        <p class="text-muted">
                            <?= htmlspecialchars($row['description']) ?>
                        </p>
                        <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                        <p><strong>Price:</strong> <span class="text-primary"> $<?= number_format($row['price'], 2) ?></span></p>
                        <h4 class="text-dark mt-4">Product Attributes:</h4>
                        <ul class="list-unstyled text-muted">
                        <?php foreach ($attributes as $key => $value): ?>
                            <li><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?></li>
                        <?php endforeach; ?>
                        </ul>
                        <?php if (!empty($row['api_test'])): ?>
                            <a href="<?= $row['api_test'] ?>" class="btn btn-primary mt-3">Try It!</a>
                        <?php else: ?>
                            <a class="btn btn-secondary mt-3">Contact Us</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Footer-->
        <footer class="footer bg-black small text-center text-white-50"><div class="container px-4 px-lg-5">Copyright &copy; Your Website 2023</div></footer>
        <!-- Bootstrap core JS-->
        <script src="https://code.berylia.org/bootstrap/v5.2.3/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="assets/js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="assets/js/sb-forms.js"></script>
        <script type="module">
            import Chatbot from "./assets/js/web.js"
            Chatbot.init({
                chatflowid: "ec071e85-7e7a-4ab6-9fa1-d54ad82d6b77",
                apiHost: `https://${window.location.hostname}:3000`,
            })
        </script>
    </body>
</html>
