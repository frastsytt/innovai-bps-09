<?php
set_time_limit(0);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['code_file'])) {
    $tmp_file = $_FILES['code_file']['tmp_name'];
    // here we define a safe upload directory and generate a unique filename
    $upload_dir = "files/";
    $safe_filename = uniqid('upload_', true) . '.txt'; // unique name with .txt extension
    $uploaded_file = $upload_dir . $safe_filename;

    // check if the upload directory exists and is writable
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // move the uploaded file to the safe location
    if (move_uploaded_file($tmp_file, $uploaded_file)) {
        $code_content = file_get_contents($uploaded_file);
        $encoded_code = json_encode($code_content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $prompt = <<<prompt
                You are an expert in cybersecurity, secure coding practices, and software vulnerability assessment. You will analyze a given source code file and determine whether it contains security vulnerabilities, weaknesses, or malicious intent. 

                ### Task:
                - Carefully review the provided code.
                - Identify any security vulnerabilities, coding weaknesses, or potential exploits.
                - If the code appears to be malicious, explain why and how it could be exploited.
                - If the code is legitimate but has security issues, provide specific recommendations for improvement.
                - Use clear and concise explanations, avoiding overly technical jargon unless necessary.
                - Provide code snippets demonstrating the correct and secure way to implement fixes.

                ### **Guidelines for Analysis:**
                - **Injection Vulnerabilities** (SQL Injection, Command Injection, Code Injection)
                - **Authentication & Authorization Issues** (Weak authentication, improper access control)
                - **Insecure File Handling** (Arbitrary file read/write, unrestricted file uploads)
                - **Memory Corruption** (Buffer overflows, use-after-free, race conditions)
                - **Cryptographic Weaknesses** (Weak encryption, improper key management)
                - **Hardcoded Secrets** (API keys, passwords, cryptographic secrets in plaintext)
                - **Improper Error Handling** (Revealing stack traces, sensitive system information leaks)
                - **Insecure External Interactions** (Calling unsafe functions, insecure dependencies)

                ### **Output Format:**
                - **Overview:** Summarize whether the code is safe, unsafe, or potentially malicious.
                - **Detected Issues:** List the vulnerabilities found with clear explanations.
                - **Risk Assessment:** Rate the severity (Low, Medium, High, Critical).
                - **Suggested Fixes:** Provide secure coding recommendations with examples.

                ### **Code for Analysis:**
                ```{$code_content}```
                prompt;

        $api_data = json_encode([
            "model" => "llama3.2:3b",
            "prompt" => $prompt,
            "stream" => false
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $call_llm_cmd = "curl -X POST http://127.0.0.1:11434/api/generate -H 'Content-Type: application/json' -d " . escapeshellarg($api_data);
        $llm_response = shell_exec($call_llm_cmd);
        $res = json_decode($llm_response);
        // clean up the uploaded file after processing
        unlink($uploaded_file);
    } else {
        error_log("Failed to move uploaded file: " . $uploaded_file);
    }
}

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
        pre{
            overflow-y: scroll;
            height: 20vh;
        }
    </style>
</head>
<body id="page-top">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
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
    <section class="projects-section bg-light" id="api-test">
        <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
            <div class="d-flex flex-column justify-content-center mt-5 w-100">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="code_file" class="form-label">Upload Code File</label>
                        <input type="file" name="code_file" class="form-control" id="code_file" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Analyze Code</button>
                </form>
                <?php if (isset($res->response)): ?>
                    <hr>
                    <h3>LLM Analysis Response</h3>
                    <pre><?= htmlspecialchars($res->response); ?></pre>
                <?php else: ?>
                    <hr>
                    <h3>How to analysis code?</h3>
                    You may use this webpage to test the IntelliAssist Pro service. The `api/products.php` serves as our test API; however, you are free to replace it with your own API route to observe the response. The webpage provides a simple interface for adjusting API parameters. Once everything is set, we will use the following command to retrieve API data, which will then be analyzed and explained by the AI LLM.
                    <div class="alert alert-primary mt-2" role="alert">
                    curl -X {api_method} -H 'Authorization: Bearer {api_key}' '{api_endpoint}' -d '{api_data}'
                    </div>
                <?php endif; ?>
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
