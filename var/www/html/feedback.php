<?php
include 'util/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $message = $_POST["message"];

    $stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (:username, :message)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':message', $message);
    
    if ($stmt->execute()) {
        echo "<script>alert('The message has been submitted successfully!');</script>";
    } else {
        echo "Error: Unable to submit message";
    }
}

$stmt = $conn->prepare("SELECT username, message FROM messages ORDER BY id DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            section{
                height: 100vh;
            }
            .message-box {
                border: 1px solid #ddd;
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 15px;
                background-color: rgba(100,100,100,0.2);
            }
            .message-input {
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 15px;
            }
            .message-user {
                font-weight: bold;
                color: #007b66;
            }
            .message-content {
                margin-top: 5px;
            }
            .message-form textarea {
                border-radius: 10px;
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
        <section class="projects-section bg-light" id="feedback">
            <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex flex-column justify-content-center w-100 h-75">
                    <form action="#" method="POST">
                        <div class="d-flex">
                            <div class="w-75">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nick Name</label>
                                    <input type="text" name="username" class="form-control message-input" id="username" required placeholder="Nick Name">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control message-input" rows="3" name="message" id="message" placeholder="Message"></textarea>
                                </div>
                            </div>
                            <div class="w-25 p-3 d-flex flex-column justify-content-end">
                                <button type="submit" class="btn btn-primary h-50 w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h3>Feedbacks</h3>
                    <div class="messages mt-4">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $row): ?>
                            <div class="message-box">
                                <p class="message-user"><?php echo $row['username']; ?></p>
                                <p class="message-content"><?php echo $row['message']; ?></p>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <p>No feedback.</p>
                        <?php endif; ?>
                    </div>
                </div>
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
