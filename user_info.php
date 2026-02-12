<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Your Information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Enter Your Information</h2>
    <form action="index.php" method="get">
        <div class="mb-3">
            <label for="user_email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="user_email" name="user_email" required>
        </div>
        <div class="mb-3">
            <label for="user_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="user_name" name="user_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Continue</button>
    </form>
</div>
</body>
</html>
