<?php
header('Content-Type: text/html; charset=utf8');
session_start();
if (isset($_SESSION['done'])) {
	$arr = $_SESSION['done'];
}
if (isset($_SESSION['original'])) {
	$original = $_SESSION['original'];
}
?>

<head>
    <title>Longman</title>
    <script type="text/javascript" src="files/scripts.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="files/styles.css">
</head>

<body>
    <div class="container-fluid allbody zindex-fixed">
        <div class="container-fluid col-sm-8 all">
            <div class="container col-sm-12 boxnhap">
                <form method="post" action="xuli/xuli.php">
                    <div class="form-row align-items-center justify-content-center">
                        <input type="text" class="col-sm-9 col-form-label" id="word" name="word" autofocus="autofocus" placeholder="Nhập từ cần tra">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php if (isset($_SESSION['original'])) {?>
            <div class="card">
                <div class="card-body">
                    <?php echo $original; ?>
                </div>
            </div>
            <?php }?>
            <?php
if (isset($_SESSION['done'])) {
	?>
            <table class="table table-bordered dulieu">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" width="10%">STT</th>
                        <th scope="col" width="25%">Từ Gốc</th>
                        <th scope="col" width="40%">Phiên Âm</th>
                        <th scope="col" width="25%">Phát Âm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$count = 1;
	foreach ($arr as $ele) {
		?>
                    <tr>
                        <td scope="col" class="align-middle">
                            <?php echo $count ?>
                        </td>
                        <td class="align-middle">
                            <?php echo $ele["word"] ?>
                        </td>
                        <td class="align-middle">
                            <?php echo $ele["pa"] ?>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-danger" onClick="anh('anh<?php echo $count ?>')">Giọng Anh</button>
                                <audio id="anh<?php echo $count ?>">
                                    <source src="<?php echo $ele["paanh"] ?>" type="audio/mpeg">
                                </audio>
                                <button type="button" class="btn btn-primary" onClick="my('my<?php echo $count ?>')">Giọng Mỹ</button>
                                <audio id="my<?php echo $count ?>">
                                    <source src="<?php echo $ele["pamy"] ?>" type="audio/mpeg">
                                </audio>
                            </div>
                        </td>
                    </tr>
                    <?php $count++;}?>
                </tbody>
            </table>
            <?php
}
?>
        </div>
    </div>
</body>