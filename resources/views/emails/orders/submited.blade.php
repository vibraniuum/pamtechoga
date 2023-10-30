<!DOCTYPE html>
<html>
<head>
    <title>New Order Notification</title>
</head>
<body>
<h2>New Order Placed from {{ $organization }}</h2>
<p>Dear Admin,</p>
<p>A new order has been placed by a customer. Here are the order details:</p>

<table>
    <thead>
    <tr>
        <th>Product</th>
        <th>Volume</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $product }}</td>
            <td>{{ $volume }}</td>
        </tr>
    </tbody>
</table>

<p>Customer Information:</p>
<p>Name: {{ $organization }}</p>
<p>Email: {{ $organizationEmail }}</p>

<p>Check admin dashboard for more details.</p>
</body>
</html>

