<!doctype html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="<?= Assets::setAssets('css/ligne/ligne.css') ?>">
    <link rel="stylesheet" href="<?= Assets::setAssets('css/components/menu.css') ?>">
    <link rel="stylesheet" href="<?= Assets::setAssets('css/font_awesome/font-awesome.min.css') ?>">
</head>
<body>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="mySidenav" class="sidenav">
            <div class="top-container">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="user-information">
                    <div class="full-name">{{user.first_name}} {{user.last_name}}</div>
                    <div class="email">{{user.email}}</div>
                </div>
            </div>
            <div class="menu-elements">
                <a href="<?= Assets::href('products/list')?>">
                    <i class="fa fa-th-list"></i>Mi lista
                </a>
                <a to="/none">
                    <i class="fa fa-shopping-cart"></i>Registrar Compra
                </a>
                <a to="/none">
                    <i class="fa fa-history"></i>Historial de compras
                </a>
                <a to="/none">
                    <i class="fa fa-bar-chart"></i>Analisis
                </a>
                <div class="category">Ajustes</div>
                <a href="<?= Assets::href('miscellany/measurement_units_view') ?>">
                    <i class="fa fa-balance-scale"></i>Unidades de medidas
                </a>
                <a to="/none">
                    <i class="fa fa-list-ul"></i>Categorias
                </a>
                <a to="/none">
                    <i class="fa fa-cog"></i>Mas ajustes
                </a>
                <a href="<?=Assets::href('auth/logout')?>">
                    <i class="fa fa-sign-out"></i>Cerrar sesion
                </a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 menu-container">
        <div class="menu-icon">
            <span style="cursor:pointer;font-weight: bold;" onclick="openNav()">&#9776;</span>
            <a href="#" class="primary important undecoration" style="margin-left: 10px;"><?=$page_title?></a>
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
