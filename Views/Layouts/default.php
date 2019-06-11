<!doctype html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="<?= Assets::setAssets('css/ligne/ligne.css') ?>">
    <link rel="stylesheet" href="<?= Assets::setAssets('css/components/menu.css') ?>">
</head>
<body>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Clients</a>
            <a href="#">Contact</a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 menu-container">
        <div class="menu-icon">
            <span style="cursor:pointer;font-weight: bold;" onclick="openNav()">&#9776;</span>
            <a href="#" class="primary important undecoration" style="margin-left: 10px;"><?= $page_title ?></a>
        </div>

    </div>
</div>
<!--No colocar la clase container al main container, distorciona el layout-->
<div style="margin-top: 20px;margin-left: 20px;margin-right: 20px;">

        <?=
        /**
         * Aqui se renderizan todas las vistas
         **/
        $content_for_layout;
        ?>
</div>
</body>
<script>
    function openNav() {
        document.querySelector("#mySidenav").style.width = "100%";
    }

    function closeNav() {
        document.querySelector("#mySidenav").style.width = "0";
    }
</script>
</html>
