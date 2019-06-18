<div class="container-app" id="products-app">
    <form action="#" id="add-product-form" method="post" @submit.prevent="createProduct">
        <div class="input-group">
            <div>
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" placeholder="Manzana" required>
            </div>
            <div>
                <label for="price">Precio</label>
                <input type="number" name="price" id="price" min="1" value="1" required>
            </div>
            <div>
                <label for="measurement_units">Unidad medida</label>
                <select name="measurement_units" id="measurement_units" required>
                    <option value selected disabled>Seleccione una...</option>
                    <option v-for="(unit,index) in measurement_units" :key="index" :value="unit.id">{{unit.name}}</option>
                </select>
            </div>
            <div>
                <label for="category">Categoria</label>
                <select name="category" id="category" required>
                    <option value selected disabled>Seleccione una...</option>
                    <option
                        v-for="(category,index) in categories"
                        :key="index"
                        :value="category.id"
                    >{{category.name}}</option>
                </select>
            </div>
        </div>
        <div>
            <div style="margin-bottom:10px">
                <strong>Incluir ITBIS</strong>
            </div>
            <div>
            <span style="margin-right:30px;">
              <input type="radio" name="itbis" value="1" id="itebis_si" checked>
              &Tab;
              <strong>
                <label for="itebis_si">SI</label>
              </strong>
            </span>
                <span>
              <input type="radio" name="itbis" value="0" id="itebis_no">
              &Tab;
              <strong>
                <label for="itebis_no">NO</label>
              </strong>
            </span>
                <div
                    class="information"
                    style="margin:10px 0px;"
                >El porcentaje de ITBIS se asigna en ajustes.</div>
                <div class="input-group">
                    <div>
                        <label for="description">Descripción</label>
                        <textarea
                            name="description"
                            id="description"
                            placeholder="Agregue aquí una descripción breve, esto es opcional"
                            rows="4"
                        ></textarea>
                    </div>
                </div>
                <div style="margin-top:20px;margin-bottom:20px;">
                    <button class="button primary small">
                        <i class="fa fa-save"></i>&Tab;Guardar
                    </button>
                    <button class="button danger small right">
                        <i class="fa fa-window-close"></i>&Tab;Cancelar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?= Assets::setAssets('js/vuejs/vue.js') ?>"></script>
<script type="text/javascript" src="<?= Assets::setAssets('js/axios/axios.js') ?>"></script>
<script type="text/javascript" src="<?= Assets::setAssets('js/ligne_route/ligne_route.js') ?>"></script>
<script type="text/javascript" src="<?= Assets::setAssets('js/products_app/products.js') ?>"></script>
<script>
    productsApp.getMeasurementUnits();
    productsApp.getCategories();
</script>