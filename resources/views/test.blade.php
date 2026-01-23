<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Products</h1>
    <table>
        <thead>
            <tr>
                <th>Product Id</th>
                <th>Product Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }} price in india</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
