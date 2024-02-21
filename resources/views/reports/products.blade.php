<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Product Report
    </title>
</head>

<style>
    table {
        border-spacing: 0;
    }
</style>

<body>
    <h1>Lovingshopz Products</h1>

    <table class="table" border="1" width="100%">
        <tr>
            <th>NO</th>
            <th>IMAGE URL</th>
            <th>PRODUCT NAME</th>
            <th>CATEGORY</th>
            <th>QUANTITY</th>
            <th>PRICE</th>
            <th>DESCRIPTION</th>
        </tr>
        @foreach($products as $product)
        <tr>
            <th>{{$loop->index + 1}}</th>
            <th></th>
            <td>{{$product->name}}</td>
            <td>{{$product->category->name}}</td>
            <td>{{$product->qty}}</td>
            <td>{{$product->price}}</td>
            <td>{{strip_tags($product->description)}}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>