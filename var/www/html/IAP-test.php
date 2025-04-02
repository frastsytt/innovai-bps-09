<?php
set_time_limit(0);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_endpoint = $_POST['api_endpoint'];
    $api_key = $_POST['api_key'];
    $api_method = $_POST['api_method'];
    $api_data = json_encode($_POST['api_data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $call_api_cmd = "curl -X {$api_method} -H 'Authorization: Bearer {$api_key}' '{$api_endpoint}' -d '{$api_data}'";
    $api_response = shell_exec($call_api_cmd);

    $prompt = <<<prompt
            You are an AI assistant skilled in analyzing and summarizing structured data from API responses. Your task is to examine the provided JSON data, identify its key components, and generate a natural language summary that explains its content in a clear and concise manner. 

            ### **Instructions:**
            1. Identify the type of data (e.g., products, employees, company details, financial records, user feedback, etc.).
            2. Extract and highlight the most relevant details while avoiding excessive technical jargon.
            3. If the data contains numerical values, provide insights by summarizing trends or key figures.
            4. If the data includes names, dates, or categories, mention their significance where applicable.
            5. If the structure is complex or hierarchical, describe its relationships in an intuitive way.
            6. Keep the explanation engaging and natural, as if explaining to a non-technical user.

            ### **Example Output Formats:**
            - If the data is about products:  
            *"The dataset contains a list of products, including their names, prices, and availability. For example, Product A is priced at $49.99 and is currently in stock, while Product B is out of stock. The data also includes customer ratings, indicating user satisfaction trends."*

            - If the data is about employees:  
            *"The data represents employee records for a company. It includes details such as employee names, job titles, departments, and salaries. For example, John Doe is a senior engineer in the AI department with a salary of $90,000 per year."*

            - If the data is about financial records:  
            *"This dataset includes financial transactions with timestamps, transaction amounts, and payment methods. The majority of transactions fall between $50 and $200, and credit cards appear to be the most common payment method."*

            Now, please analyze the following JSON data and generate an insightful summary.
            
            ### **Data for Analysis:**
            ```{$api_response}```
            prompt;

    $api_data = json_encode([
        "model" => "llama3.2:3b",
        "prompt" => $prompt,
        "stream" => false
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $call_llm_cmd = "curl -X POST http://127.0.0.1:11434/api/generate -H 'Content-Type: application/json' -d " . escapeshellarg($api_data);;

    $llm_response = shell_exec($call_llm_cmd);
    $res = json_decode($llm_response);
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
                <form action="#" method="POST">
                    <div class="mb-3">
                        <label for="api_endpoint" class="form-label">API Endpoint</label>
                        <input type="text" name="api_endpoint" class="form-control" id="api_endpoint" value="https://<?=$_SERVER['HTTP_HOST']?>/api/products.php" required>
                    </div>
                    <div class="mb-3">
                        <label for="api_key" class="form-label">API Key</label>
                        <input type="text" name="api_key" class="form-control" id="api_key">
                    </div>
                    <div class="mb-3">
                        <label for="api_method" class="form-label">API Method</label>
                        <select type="text" name="api_method" class="form-control" id="api_method" required>
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                            <option value="PUT">PUT</option>
                            <option value="DELETE">DELETE</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="api_data" class="form-label">API Data</label>
                        <textarea class="form-control" name="api_data" id="api_data" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </form>
                <?php if (isset($res)): ?>
                    <hr>
                    <h3>Response</h3>
                    <pre><?= $res->response; ?></pre>
                <?php else: ?>
                    <hr>
                    <h3>How to test api?</h3>
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
