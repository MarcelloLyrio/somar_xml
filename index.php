<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Soma XML</title>

    <script src="js/jQuery-2.2.0.min.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
   </head>
	<body> 
	<div class="container" style="width: 60%;border: 1px solid #ccc; margin-top: 15px; margin-bottom: 20px; background: #fff;">

					<table id="example" class="table table-striped " cellspacing="0" width="100%">
                        	<thead>
							<tr class="primary">
								<th width="20"><strong>Data</strong></th>
								<th width="20"><strong>NFCe</strong></th>
								<th width="20"><strong>Valor R$</strong></th>
							</tr>
                        </thead>
                     <tbody>
<?php
error_reporting(0);

$path = "xml/";
$diretorio = dir($path);
while($arquivo = $diretorio -> read()){
   
                $DOMDocument = new DOMDocument( '1.0', 'UTF-8' );
                $DOMDocument->preserveWhiteSpace = false;
                $DOMDocument->load( $path.$arquivo );

                $products     = $DOMDocument->getElementsByTagName( 'ICMSTot' );
                $ides            = $DOMDocument->getElementsByTagName( 'ide' );
                $cancelados = $DOMDocument->getElementsByTagName( 'retEvento' );
                $chaves       = $DOMDocument->getElementsByTagName( 'infProt' );

                foreach( $cancelados as $cancelado ) {
                    $canc_nfe[] = $cancelado->getElementsByTagName('chNFe')->item(0)->nodeValue;
                }

                foreach( $chaves as $chave ) {
                $chave_nfe[] = $chave->getElementsByTagName('chNFe')->item(0)->nodeValue;
                }

                foreach( $products as $product ) {
                        $vlnfe[] = $product->getElementsByTagName('vNF')->item(0)->nodeValue;
                }

                foreach( $ides as $ide ) {
                    $data = $ide->getElementsByTagName( 'dhEmi' )->item( 0 )->nodeValue;
                    $d = substr($data, 0, 10);
                    $d = explode("-", $d);

                    $data_xml[] = $d[2]."/".$d[1]."/".$d[0];
                    $nrnfe[] = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
                }

}
            $contador = count($nrnfe);
                    for($i=0;$i<$contador;$i++) {
                                $chv = $chave_nfe[$i];
                        if (in_array($chv, $canc_nfe)){
                            $valor = '0';
                            $texto = ' - Cancelada';
                            $estilo = "style='color: red'";
                        }else{
                            $valor = $vlnfe[$i];
                            $texto = '';
                            $estilo = "";
                        }
                        echo "<tr ".$estilo.">";
                        echo "<td width=\"20\">" . $data_xml[$i] . "</td>";
                        echo "<td width=\"20\">" . $nrnfe[$i] . $texto . "</td>";
                        echo "<td width=\"20\">" . number_format($valor, 2, ',', '.') . "</td>";
                        echo "</tr>";
                        $total = $total + $valor;
                        }

echo "<h2>Valor total das notas: <strong>R$ " . number_format($total, 2, ',', '.')."</strong></h2>";
echo "<hr>";
echo "<table cellspacing=\"10\" width=\"100%\" style='margin: 10px 0 10px 0'>";
echo "<tr>";
echo "<td width=\"20\" style=\"border: 1px solid #ccc; margin: 5px; padding: 5px;\">Total de: <strong>".$contador." Nota(s)</strong></td>";
echo "<td width=\"20\" style=\"border: 1px solid #ccc;margin: 5px; padding: 5px;\">Total de Notas canceladas: <strong>" . count($canc_nfe). " Nota(s)</strong></td>";
echo "<td width=\"20\" style=\"border: 1px solid #ccc;margin: 5px; padding: 5px;\">Valor total das notas: <strong>R$ " . number_format($total, 2, ',', '.')."</strong></td>";
echo "</tr>";
echo "</table>";
$diretorio -> close(); 
//var_dump($data_xml);
//var_dump($nrnfe);
//var_dump($vlnfe);
//var_dump($canc_nfe);
//var_dump($chave_nfe);
?>
                        
                        </tbody>
                     </table>
</div>
      <script>
       $(document).ready(function(){
        $('#example').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Desculpe, nada encontrado!",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro encontrado!",
                "search":         "Pesquisar:",
                "next":       "Próximo",
                "previous":   "Anterior",
                "infoFiltered": "(filtrado de um total de _MAX_ registros)"
            }
        });
    });
</script>
<script src="js/jQuery-2.2.0.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
</body>
</html>