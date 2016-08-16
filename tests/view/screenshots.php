<?php
require __DIR__ . '/lib.php';
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

    <title>xTest Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>

    <style>

        .steps li {
            padding: 3px;
            margin: 4px;
            padding-left: 10px;
        }

        .scenarios .alert {
            margin: 4px;
            padding-left: 10px;
        }

        ul {
            list-style-type: none;
        }

        h3, h4 {
            cursor: pointer;
        }

        select {
            -webkit-appearance: none;
        }

    </style>

    <link rel="stylesheet" type="text/css" href="print.css" media="print" />

</head>
<body>

<!-- Wrap all page content here -->
<div id="wrap">

    <div class="container">
        <div class="page-header">
            <h1>xTest Screenshots</h1>
        </div>

        <?php $jsonfile = get_behat_result_dir() . '/log.json'; ?>

        <?php $json = json_decode(file_get_contents($jsonfile)); ?>

        <ul class="features">
            <?php foreach ($json AS $suiteName => $testClass): ?>
                <li>
                    <?php foreach ($testClass->__tests AS $_test): ?>

                        <?php if (count($_test->screenshots)) : ?>

                            <div>

                                <h3><?php echo $_test->testName; ?></h3>

                                <ul class="images">
                                    <?php foreach ($_test->screenshots AS $img => $title) : ?>

                                        <li>

                                            <h4 onclick="jQuery(this).parent().find('img').toggle()"><?php echo $title; ?></h4>

                                            <img class="img-thumbnail annotatable" title="<?php htmlentities($title); ?>"
                                                 src="images.php?file=<?php echo urlencode(
                                                     $img . '.png'
                                                 ); ?>&time=<?php echo $_REQUEST['time']; ?>" />
                                        </li>

                                    <?php endforeach; ?>
                                </ul>

                            </div>

                        <?php endif; ?>
                    <?php endforeach; ?>

                </li>

            <?php endforeach; ?>

        </ul>

    </div>

</div>


<div id="footer">
    <div class="container">
        <p class="text-muted">www.code-x.de</p>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

<script type="text/javascript">
    jQuery('h4.alert-success').parent().children('ul').hide();
</script>

</body>

</html>