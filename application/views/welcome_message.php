
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mini Chat GPTr</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                background-color: #f8f9fa;
            }

            .container {
                margin-top: 50px;
                padding: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            h1 {
                text-align: center;
                color: #333;
            }

            #queryResults table {
                width: 100%;
                border-collapse: collapse;
            }

            #queryResults th, #queryResults td {
                padding: 8px;
                text-align: left;
                border: 1px solid #ddd;
            }

            #queryResults th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Mini Chat GPT</h1>
            <form id="queryForm">
                <div class="form-group">
                    <label for="prompt">Enter your request:</label>
                    <textarea class="form-control" id="prompt" name="prompt" rows="4" placeholder="E.g., Get all users who registered today"></textarea>
                </div>
                <button type="button" class="btn btn-primary" onclick="generate()">Send</button>
            </form>

            <div class="form-group mt-4">
                <label for="sqlQuery">Result:</label>
                <textarea class="form-control" id="sqlQuery" name="sqlQuery" rows="10"></textarea>
            </div>
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Function to generate the result from AI
            function generate() {
                // $('#sqlQuery').val(''); // Clear old content
                const prompt = $('#prompt').val(); // Get the prompt from the input field

                if (prompt.trim() === "") {
                    alert("Please enter a valid prompt.");
                    return;
                }

                // Send the prompt to the server for SQL query generation
                $.ajax({
                    url: '<?= base_url('index.php/welcome/generate') ?>', // The endpoint to generate SQL query
                    type: 'POST',
                    data: { prompt: prompt },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            console.log('Generated SQL Query:', response.query);
                            $('#sqlQuery').val(''); // Clear old content
                            $('#sqlQuery').val(response.query);
                        } else {
                            alert('Error generating SQL: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('An error occurred while generating the SQL query.');
                    }
                });
            }
        </script>
    </body>
</html>
