<div class="row" id="product-list">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <input type="search" placeholder="Buscar...">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
        <div class="product-container" v-for="product in products">
            <div class="row">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="photo">
                        <img src="<?= Assets::setAssets('img/product-default-img.png') ?>" alt="">
                    </div>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 details">
                    <div class="name">
                        <a href="#" class="primary important undecoration">{{product.name}}</a>
                        <a class="success undecoration important right">Editar</a>
                    </div>
                    <div class="unit" style="color: var(--grey)">
                        {{product.measurementUnits}}
                    </div>
                    <div class="price" style="font-weight: bold;">
                        RD$ {{product.price}}
                        <a class="danger undecoration important right">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= Assets::setAssets('css/components/products.css') ?>">
<script type="text/javascript" src="<?= Assets::setAssets('js/vuejs/vue.js') ?>"></script>
<script type="text/javascript" src="<?= Assets::setAssets('js/axios/axios.js') ?>"></script>
<script type="text/javascript" src="<?= Assets::setAssets('js/ligne_route/ligne_route.js') ?>"></script>
<script type="text/javascript" src="<?= Assets::setAssets('js/products_app/list.js') ?>"></script>