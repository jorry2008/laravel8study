<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>文件上传</title>
</head>
<body>

<form action="/test/file" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="file" accept="image/*" name="test_file">

    <button type="submit">Submit</button>
</form>
<div>{{ $name }}</div>

</body>
</html>
