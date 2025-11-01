<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Your Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
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
