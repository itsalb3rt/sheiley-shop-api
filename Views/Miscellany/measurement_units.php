<div>
    <div class="container-app">
        <form action="#" method="POST">
            <div class="input-group">
                <div>
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" placeholder="UNIDAD">
                </div>
            </div>
            <div>
                <button type="submit" class="button primary small">
                    <i class="fa fa-save"></i>&Tab;Guardar
                </button>
            </div>
        </form>
        <div>
            <h4>UNIDADES DE MEDIDA REGISTRADAS</h4>
            <div class="text small">
                <table class="table hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>UNIDAD MEDIDA</th>
                        <th>ACCION</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(unit,index) in measurement_units" :key="index">
                        <td>{{index + 1}}</td>
                        <td>{{unit.name}}</td>
                        <td>
                            <a class="danger undecoration" href="#">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>