<html>
<head><title>Buscar factura</title></head>
<body>
    <?php
    $dp = mysql_connect("localhost", "root", "" );
    mysql_select_db("facturas", $dp);
    ?>
 
    <script type="text/javascript">
    function change(obj) {
        var selectBox = obj;
        var selected = selectBox.options[selectBox.selectedIndex].value;
        var textarea = document.getElementById("text_area");

        if(selected === "1"){
            textarea.style.display = "block";
        }
        else{
            textarea.style.display = "none";
        }
    }

    function changeCli(obj) {
        var selectBox = obj;
        var selected = selectBox.options[selectBox.selectedIndex].value;
        var sele = selected.split("|");
        var textarea = document.getElementById("cliente1");
        var text = document.getElementById("cif1");

        if(sele[0] === "1"){
            textarea.style.display = "block";
            text.style.display = "none";
        }else if (sele[0] === ""){
            textarea.style.display = "none";
            text.style.display = "none";
        }else{
            textarea.style.display = "none";
            text.style.display = "block";
        }
        document.getElementById("cif1").value = sele[1];
    }
    </script>

    <style type="text/css">
        table { border: 1px solid black; border-collapse: collapse }
        td { border: 1px solid black }
    </style>

    <form enctype="multipart/form-data" action="" method="post">
        <input type="checkbox" name="buscar[]" value="fecha">Fecha: <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>"/><br><br>

        <input type="checkbox" name="buscar[]" value="numero">Nº de factura: <input type="number" name="num"/><br><br>

        <input type="checkbox" name="buscar[]" value="cliente"><label>Cliente:</label> 
        <select name="cli1" onchange="changeCli(this)">
            <option selected="selected"></option>
            <option value="1">Otro</option>
            <?php
            $sql = "SELECT * FROM clientes";
            $clis = mysql_query($sql);
            while ($row = mysql_fetch_assoc($clis)) {
                print("<option value='".$row[direccion]."|".$row[cif]."'>$row[direccion]</option>");
            }
            ?>
        </select>
        <input id="cif1" type="text" name="cif1" value="" style="display: none" disabled/>
        <textarea id="cliente1" name="cliente1" rows="5" style="display: none"></textarea><br><br>

        <input type="checkbox" name="buscar[]" value="concepto">
        <label>Concepto:</label> 
        <select name="conce" onchange="change(this)">
            <option selected="selected"></option>
            <option value="1">Otro</option>
            <?php
                $sql = "SELECT * FROM conceptos";
                $cons = mysql_query($sql);
                while ($row = mysql_fetch_assoc($cons)) {
                    print("<option value='".$row[concepto]."'>$row[concepto]</option>");
                }
            ?>
        </select>
        <textarea id="text_area" name="concepto" rows="1" cols="50" style="display: none"></textarea><br><br>

        <input type="checkbox" name="buscar[]" value="iva">IVA: <input type="number" name="iva" value="21" Style="width:40Px"/><br><br>
        <input type="submit" name="buscar1" value="Buscar"/>
    </form>
    <?php
    if(isset($_POST['buscar1'])){
        $sele = "";

        function IsChecked($chkname,$value){
            if(!empty($_POST[$chkname])){
                foreach($_POST[$chkname] as $chkval){
                    if($chkval == $value){
                        return true;
                    }
                }
            }
            return false;
        }

        if (IsChecked('buscar','fecha')){
            $fecha = $_POST['fecha'];
            if($sele == ""){
                $sele = "SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE facturas.cod_fac=tener_f_c.cod_fac AND facturas.fecha='$fecha' ORDER BY facturas.cod_fac";
            } else {
                $sele = $sele." AND facturas.fecha='$fecha' ORDER BY facturas.cod_fac";
            }
        }

        if (IsChecked('buscar','numero')){
            $numero = $_POST['num'];
            if($sele == ""){
                $sele = "SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE facturas.cod_fac=tener_f_c.cod_fac AND facturas.cod_fac=$numero ORDER BY facturas.cod_fac";
            } else {
                $sele = $sele." AND facturas.cod_fac=$numero ORDER BY facturas.cod_fac";
            }
        }

        if (IsChecked('buscar','cliente')){
            if($sele == ""){
                if ($_POST['cli1'] == 1) {
                    $cli1 = $_POST['cli1'];
                    $sele = "SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE facturas.cod_fac=tener_f_c.cod_fac AND facturas.cliente LIKE '%$cli1%' ORDER BY facturas.cod_fac";
                } else {
                    $cli = explode('|', $_POST['cli1']);
                    $cliente = $cli[0]."\n".$cli[1];
                    $inscli = $cli[1];
                    $sele = "SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE facturas.cod_fac=tener_f_c.cod_fac AND facturas.cliente='$inscli' ORDER BY facturas.cod_fac";
                }
            } else {
                if ($_POST['cli1'] == 1) {
                    $cli1 = $_POST['cli1'];
                    $sele = $sele." AND facturas.cliente LIKE '%$cli1%' ORDER BY facturas.cod_fac";
                } else {
                    $cli = explode('|', $_POST['cli1']);
                    $cliente = $cli[0]."\n".$cli[1];
                    $inscli = $cli[1];
                    $sele = $sele." AND facturas.cliente='$inscli' ORDER BY facturas.cod_fac";
                }
            }
        }

        if (IsChecked('buscar','concepto')){
            $concep = $_POST['conce'];
            if ($concep == 1) {
                $concepto = $_POST['concepto'];
            } else {
                $concepto = $concep;
            }
            if($sele == ""){
                $sele = "SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE tener_f_c.cod_fac=facturas.cod_fac AND tener_f_c.concepto LIKE '%ccc%' ORDER BY facturas.cod_fac";
            } else {
                $sele = $sele." AND tener_f_c.cod_fac=facturas.cod_fac AND tener_f_c.concepto LIKE '%ccc%' ORDER BY facturas.cod_fac";
            }
        }

         //"SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE tener_f_c.cod_fac=facturas.cod_fac and tener_f_c.concepto LIKE '%ccc%' ORDER BY facturas.cod_fac"

        if (IsChecked('buscar','iva')){
            $iva = $_POST['iva'];
            if($sele == ""){
                $sele = "SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE facturas.cod_fac=tener_f_c.cod_fac AND facturas.IVA=$iva ORDER BY facturas.cod_fac";
            } else {
                $sele = $sele." AND facturas.IVA=$iva ORDER BY facturas.cod_fac";
            }
        }

        if (!IsChecked('buscar','fecha') && !IsChecked('buscar','numero') && !IsChecked('buscar','cliente') && !IsChecked('buscar','concepto') && !IsChecked('buscar','iva')) {
            $sele = $sele = "SELECT facturas.cod_fac as cod_fac,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio_u as precio FROM facturas, tener_f_c WHERE facturas.cod_fac=tener_f_c.cod_fac ORDER BY facturas.cod_fac";
        }
        echo $sele;
        $selec = mysql_query($sele);
        if (mysql_num_rows($selec) == 0){
            echo "¡ERROR! No hay facturas que cumplan esas condiciones.";
        }else{
            $num_fila = 0; 
            echo "<table border=1>";
            echo "<tr bgcolor=\"bbbbbb\" align=center><th>Codigo</th><th>Fecha</th><th>Cliente</th><th>IVA</th><th>Concepto</th><th>Cantidad</th><th>Precio</th></tr>";
            while ($row = mysql_fetch_assoc($selec)) {
                echo "<tr "; 
                if ($num_fila%2==0) 
                    echo "bgcolor=#dddddd"; //si el resto de la división es 0 pongo un color 
                else 
                    echo "bgcolor=#ddddff"; //si el resto de la división NO es 0 pongo otro color 
                //,facturas.fecha as fecha, facturas.cliente as cliente, facturas.iva as iva, tener_f_c.concepto as concepto, tener_f_c.cantidad as cantidad, tener_f_c.precio as precio
                echo ">";
                echo "<td>$row[cod_fac]</td>";
                echo "<td>$row[fecha]</td>";
                echo "<td>$row[cliente]</td>";
                echo "<td>$row[iva]</td><td></td><td></td><td></td></tr>";
                $selec2 = mysql_query("SELECT concepto, cantidad, precio_u as precio FROM tener_f_c WHERE cod_fac='$row[cod_fac]'");
                while ($row2 = mysql_fetch_assoc($selec2)) {
                    echo "<tr "; 
                    if ($num_fila%2==0) 
                        echo "bgcolor=#dddddd"; //si el resto de la división es 0 pongo un color 
                    else 
                        echo "bgcolor=#ddddff"; //si el resto de la división NO es 0 pongo otro color 
                    echo ">";
                    echo "<td></td><td></td><td></td><td></td>";
                    echo "<td>$row2[concepto]</td>";
                    echo "<td>$row2[cantidad]</td>";
                    echo "<td>$row2[precio]€</td>";
                    echo "</tr>";
                }
                //echo "<td>$row[precio]€</td>";
                //echo "<td><a href=\"edit_conce.php?concepto=$row[cod_con]\"><input type=\"button\" value=\"Editar\"></a></td>";
                //echo "<td><button onclick=\"seguro($row[cod_con]);\">Delete</button></td>";
                echo "</tr>";
                $num_fila++;
            }
            echo "</table><br/>";
        }
    }
    ?>
</body>
</html>