<html>
<head>
<title>Editar cliente</title>
</head>
<body>
	<script type="text/javascript">
    function change(obj,num,pan) {
        var selectBox = obj;
        var num = num;
        var pan = pan;
        var selected = selectBox.options[selectBox.selectedIndex].value;
        var sele = selected.split("|");
        var textarea = document.getElementById("text_area"+num);

        if(sele[0] === "1"){
            textarea.style.display = "block";
            document.getElementById("precio"+num).value = pan;
        }
        else{
            textarea.style.display = "none";
            document.getElementById("precio"+num).value = sele[1];
        }
    }
    </script>
<?php
$data = $_GET['cod_fac'];
$data2 = $_GET['concepto'];

$dp = mysql_connect("localhost", "root", "" );
mysql_select_db("facturas", $dp);

$sql = "SELECT * FROM facturas WHERE cod_fac=$data";
$facs = mysql_query($sql);

$num_fila = 0; 
            echo "<table border=1>";
            echo "<tr bgcolor=\"bbbbbb\" align=center><th>Codigo</th><th>Fecha</th><th>Cliente</th><th>CIF</th><th>IVA %</th><th>Concepto</th><th>Cantidad</th><th>Precio</th></tr>";
            while ($row = mysql_fetch_assoc($facs)) {
                echo "<form enctype='multipart/form-data' action='' method='post'>";
                echo "<tr "; 
                if ($num_fila%2==0) 
                    echo "bgcolor=#dddddd"; //si el resto de la división es 0 pongo un color 
                else 
                    echo "bgcolor=#ddddff"; //si el resto de la división NO es 0 pongo otro color 
                echo ">";
                echo "<td>$row[cod_fac]</td>";
                $fecha = date_format(date_create_from_format('Y-m-d', $row['fecha']), 'd/m/Y');
                echo "<td><input type='date' name='fecha' value='$row[fecha]' disabled/></td>";
                $exis = "$row[existe_cli]";
                if ($exis==0) {
                    echo "<td><select name='cli1' onchange='changeCli(this)' disabled>";
            		echo "<option value='1' selected='selected'>Otro</option>";
            		$sqlc = "SELECT * FROM clientes";
            		$clis = mysql_query($sqlc);
            		while ($row3 = mysql_fetch_assoc($clis)) {
                		print("<option value='".$row3[direccion]."|".$row3[cif]."'>$row3[direccion]</option>");
            		}
        
        			echo "</select><br/><textarea id='cliente1' name='cliente1' rows='5' disabled>$row[cliente]</textarea></td><td><input id='cif1' type='text' name='cif1' value='' style='display: none' disabled/></td>";
                }else{

                    $selec3 = mysql_query("SELECT direccion,cif FROM clientes WHERE cif='$row[cliente]'");
                    $direccion = mysql_result($selec3,0,0);
                    $cif = mysql_result($selec3,0,1);
                    echo "<td><select name='cli1' onchange='changeCli(this)' disabled>";
            		echo "<option value='1'>Otro</option>";
            		$sqlc = "SELECT * FROM clientes";
            		$clis = mysql_query($sqlc);
            		while ($row3 = mysql_fetch_assoc($clis)) {
            			if ($cif == $row3['cif']) {
            				print("<option value='".$row3[direccion]."|".$row3[cif]."' selected='selected'>$row3[direccion]</option>");
            			} else {
            				print("<option value='".$row3[direccion]."|".$row3[cif]."'>$row3[direccion]</option>");
            			}
            		}
        
        			echo "</select><br/><textarea id='cliente1' name='cliente1' rows='5' style='display: none' disabled></textarea></td><td><input id='cif1' type='text' name='cif1' value='$cif' disabled/></td>";

                }
                echo "<td><input type='number' name='iva' value='$row[IVA]' Style='width:40Px' disabled/>%</td><th>Concepto</th><th>Cantidad</th><th>Precio</th>";
				echo "<td><a href=\"edit_factura.php?cod_fac=$data\"><input type=\"button\" value=\"Editar\"></a></td>";
                echo "</tr>";
				echo "</form>";
                
                $selec2 = mysql_query("SELECT concepto, cantidad, precio_u as precio FROM tener_f_c WHERE cod_fac='$data'");
                while ($row2 = mysql_fetch_assoc($selec2)) {
                    if ($row2['concepto'] == $data2) {
                        
                        echo "<form enctype='multipart/form-data' action='' method='post'>";

                        echo "<tr "; 
                        if ($num_fila%2==0) 
                            echo "bgcolor=#dddddd"; //si el resto de la división es 0 pongo un color 
                        else 
                            echo "bgcolor=#ddddff"; //si el resto de la división NO es 0 pongo otro color 
                        echo ">";

                        echo "<td colspan=5/>";
                        //echo "<td>";
                        echo "<td><select name='concepto3' onchange='change(this,1,$row2[precio])'>";
                        echo "<option value='1' selected='selected'>Otro</option>";
                        $sql2 = "SELECT * FROM conceptos";
                        $cons = mysql_query($sql2);
                        while ($row4 = mysql_fetch_assoc($cons)) {
                            print("<option value='".$row4['concepto']."|".$row4['precio']."'>$row4[concepto]</option>");
                        }
                        
                        echo "</select><br/>";
                        echo "<textarea id='text_area1' name='concepto1' rows='3' cols='40'>$row2[concepto]</textarea><textarea name='concepto2' style='display: none'>$row2[concepto]</textarea>";
                        echo "</td>";
                        echo "<td><input type='number' name='cant1' value='$row2[cantidad]' Style='width:40Px'/></td>";
                        echo "<td><input id='precio1' type='number' name='precio1' step='any' Style='width:60Px' value='$row2[precio]'/>€</td>";
                        echo "<td><input type='submit' name='guardarc' value='Guardar'/></td>";
                        //echo "<td><a href=\"edit_con_fac.php?cod_fac=$data&concepto='$row2[concepto]'\"><input type=\"button\" value=\"Editar\"></a></td>";
                        echo "</tr>";
                        echo "</form>";
                        //$nu++;
                    } else {
                        //echo "<form enctype='multipart/form-data' action='' method='post'>";
                        echo "<tr "; 
                        if ($num_fila%2==0) 
                            echo "bgcolor=#dddddd"; //si el resto de la división es 0 pongo un color 
                        else 
                            echo "bgcolor=#ddddff"; //si el resto de la división NO es 0 pongo otro color 
                        echo ">";
                        echo "<td colspan=5></td>";
                        echo "<td><textarea rows='3' cols='40' disabled>$row2[concepto]</textarea></td>";
                        echo "<td><input type='number' value='$row2[cantidad]' Style='width:40Px' disabled/></td>";
                        echo "<td><input type='number' step='any' Style='width:60Px' value='$row2[precio]' disabled/>€</td>";
                        echo "<td><a href=\"edit_con_fac.php?cod_fac=$data&concepto=$row2[concepto]\"><input type=\"button\" value=\"Editar\"></a></td>";
                        echo "</tr>";
                        //echo "</form>";
                    }
                    
                }
                //echo "<td>$row[precio]€</td>";
                //echo "<td><a href=\"edit_conce.php?concepto=$row[cod_con]\"><input type=\"button\" value=\"Editar\"></a></td>";
                //echo "<td><button onclick=\"seguro($row[cod_con]);\">Delete</button></td>";
                //echo "</tr>";

                echo "<form enctype='multipart/form-data' action='' method='post'><tr><td colspan=5/>";
                echo "<td><select name='concepto3' onchange='change(this,2,0)'>";
                echo "<option selected='selected'></option>";
                echo "<option value='1'>Otro</option>";
                $sql3 = "SELECT * FROM conceptos";
                $adcons = mysql_query($sql3);
                while ($row5 = mysql_fetch_assoc($adcons)) {
                    print("<option value='".$row5['concepto']."|".$row5[precio]."'>$row5[concepto]</option>");
                }
                        
                echo "</select><br/>";
                echo "<textarea id='text_area2' name='concepto2' rows='3' cols='40' style='display: none'></textarea>";
                echo "</td>";
                echo "<td><input type='number' name='cant2' value='1' Style='width:40Px'/></td>";
                echo "<td><input id='precio2' type='number' name='precio2' step='any' Style='width:60Px' value=''/>€</td>";
                echo "<td><input type='submit' name='addc' value='Añadir'/></td>";
                echo "</tr></form>";

                $num_fila++;
            }
            echo "</table>";

if(isset($_POST['addc'])){
    $cantidad = $_POST['cant2'];
    $precio = $_POST['precio2'];
    if ($_POST['concepto3'] == 1) {
        $concepto = $_POST['concepto2'];
    }else{
        $conc = explode('|', $_POST['concepto3']);
        $concepto = $conc[0];
    }
    $concepto = trim(preg_replace('/\s\s+/', ' ', $concepto));
    $gehitu="INSERT INTO tener_f_c (concepto,cod_fac,cantidad,precio_u) VALUES ('$concepto',$data,$cantidad,'$precio')";
    mysql_query($gehitu);
    header("Location: edit_factura.php?cod_fac=$data");
}

if(isset($_POST['guardarc'])){
    $concepto2 = $_POST['concepto2'];
    $cantidad = $_POST['cant1'];
    $precio = $_POST['precio1'];
	if ($_POST['concepto3'] == 1) {
		$concepto = $_POST['concepto1'];
	} else {
		$conce = explode('|', $_POST['concepto3']);
		$concepto =  $conce[0];
	}
	$aldatu="UPDATE tener_f_c SET concepto='$concepto',cantidad=$cantidad,precio_u='$precio' WHERE cod_fac=$data AND concepto='$concepto2'";
	mysql_query($aldatu);

    //header("Location: manage_facturas.php");
	header("Location: edit_factura.php?cod_fac=$data");
    //header("Refresh:0");
}

mysql_close($dp);
?>
<br/>
<a href="manage_facturas.php"><input type="button" value="Atrás"></a>
</body>
</html>