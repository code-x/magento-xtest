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

</head>
<body>

<!-- Wrap all page content here -->
<div id="wrap">

    <div class="container">
        <div class="page-header">
            <h1>xTest Results</h1>
        </div>

        <div class="row">

            <div class="col-md-6">

                <select class="form-control" onchange="document.location.href=this.value;">
                    <option value="#">Please select</option>

                    <?php $dirs = glob(get_behat_result_dir() . '/../*');
                    rsort($dirs); ?>
                    <?php foreach ($dirs AS $dir) : ?>
                        <?php if (is_dir($dir)) : ?>
                            <option style="color: <?php echo($has_error ? 'red' : 'green') ?>"
                                    value="?time=<?php echo basename($dir); ?>" <?php if (basename(
                                    $dir
                                ) == $_REQUEST['time']
                            ) : ?> selected="selected" <?php endif; ?> >
                                <?php echo basename($dir); ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

            </div>

            <div class="col-md-6">

                <div class="btn-group">
                    <a href="screenshots.php?time=<?php echo $_REQUEST['time']; ?>" class="btn btn-default">View
                        Screenshots</a>
                </div>

            </div>

        </div>

        <hr/>

        <?php $jsonfile = get_behat_result_dir() . '/log.json'; ?>
        <?php if (file_exists($jsonfile)) : ?>

            <?php $json = json_decode(file_get_contents($jsonfile)); ?>

            <ul class="features">
                <?php foreach ($json AS $suiteName => $testClass): ?>
                    <li>

                        <?php
                        $status = 0;
                        foreach ($testClass->__tests AS $_test) {
                            if ($_test->status) {
                                $status = 1;
                            }
                        }
                        ?>

                        <h3 class="<?php echo get_state_class($status); ?>"
                            onclick="jQuery(this).parent().children('ul').toggle(); ">
                            <?php echo $suiteName; ?>

                            <?php if (isset($testClass->tags)) foreach ($testClass->tags AS $tag) : ?>

                                <span class="badge badge-default">
                                        <?php echo $tag; ?>
                                    </span>

                            <?php endforeach; ?>

                            <span style="float: right" class="glyphicon glyphicon-info-sign"></span>

                        </h3>

                        <ul class="scenarios">

                            <li>

                                <?php foreach ($testClass->__tests AS $_test): ?>

                                    <div>

                                        <h4 class="<?php echo get_state_class($_test->status); ?>"
                                            onclick="jQuery(this).parent().children('ul').toggle(); ">
                                            <?php echo $_test->testName; ?>


                                            <?php if (isset($_test->tags)) foreach ($_test->tags AS $tag) : ?>

                                                <span class="badge badge-default">
                                                        @<?php echo $tag; ?>
                                                    </span>

                                            <?php endforeach; ?>

                                            <span style="float: right" class="glyphicon glyphicon-info-sign"></span>

                                            <?php if (count($_test->screenshots)) : ?>
                                                <span style="float: right"
                                                      class="glyphicon glyphicon-camera">&nbsp;</span>
                                            <?php endif; ?>

                                        </h4>

                                        <ul>

                                            <li> <?php echo nl2br($_test->description); ?> </li>
                                            <li> Time: <?php echo $_test->time ?> </li>
                                            <li> <?php echo $_test->exception; ?> </li>

                                            <?php if (count($_test->screenshots)) : ?>
                                                <li>
                                                    <h4>Screenshots</h4>

                                                    <ul class="images">
                                                        <?php foreach ($_test->screenshots AS $img => $title) : ?>

                                                            <li>
                                                                <img class="img-thumbnail"
                                                                     title="<?php htmlentities($title); ?>"
                                                                     src="images.php?file=<?php echo urlencode(
                                                                         $img . '.png'
                                                                     ); ?>&time=<?php echo $_REQUEST['time']; ?>"/>
                                                            </li>

                                                        <?php endforeach; ?>
                                                    </ul>
                                                </li>
                                            <?php endif; ?>
                                        </ul>

                                    </div>


                                <?php endforeach; ?>

                            </li>

                        </ul>

                    </li>

                <?php endforeach; ?>

            </ul>

        <?php endif; ?>

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
