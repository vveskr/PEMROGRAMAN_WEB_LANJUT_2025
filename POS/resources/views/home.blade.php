<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome! This is the home page of POS.</h1>
    <h2>Product Categories</h2>
    <ul>
        <li><a href="{{ route('category.food-beverage') }}">Food & Beverages</a></li>
        <li><a href="{{ route('category.beauty-health') }}">Beauty & Health</a></li>
        <li><a href="{{ route('category.home-care') }}">Home Care</a></li>
        <li><a href="{{ route('category.baby-kid') }}">Baby & Kids</a></li>
    </ul>
</body>
</html>