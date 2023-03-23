<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My User Table</title>
    <?php include('css_js.php'); ?>
</head>

<body>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users AS $key => $row){ ?>
                <tr>
                    <td><?=$row['id']?></td>
                    <td><?=$row['name']?></td>
                    <td><?=$row['email']?></td>
                    <td><?=$row['address']?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</body>

</html>