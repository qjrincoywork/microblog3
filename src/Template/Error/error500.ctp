<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<title>500 Internal Error</title>

	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,700" rel="stylesheet">

    <?= $this->Html->css('error/style500');?>

</head>

<body>
    <?php
        echo $this->layout = null;
        $actual_link = (isset($_SERVER['HTTPS']) === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    ?>
    <div id="notfound">
        <div class="notfound">
            <div class="notfound-500">
                <h1>5<span></span>0</h1>
            </div>
            <h2>Error :(</h2>
            <p>It's always time for a coffee break.</p>
            <p>We should be back by the time you finish your coffee.</p>
            <a href="<?=$actual_link?>">Back to homepage</a>
        </div>
    </div>
</body>
</html>
