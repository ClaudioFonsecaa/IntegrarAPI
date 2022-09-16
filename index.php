<?php

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- IMPORTAR BOOTSTRAP -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <!-- IMPORTAR CSS E OUTRAS BIBLIOTECAS -->
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/4993c2bd61.js" crossorigin="anonymous"></script>

    <title>Integraçao de API</title>

    <link rel="shortcut icon" href="/favicon_io/favicon.ico" > <!-- FAV ICON DO SITE   -->

</head>
<body>

    <div class="central">

                      <div class="titulo">
                                <table class="tabela_titulo">
                                   <th><h2 style="text-align: center">Integraçao de API</h2></th>
                               </table>
                        </div>


                <img width="200" height="100" src="covid19.png"><p>

            <form method="post">
                <input type="text" name="pais" placeholder="País">
                <input type="submit"  value="Procurar Dados">
            </form>

               <?php

               if (isset($_POST["pais"])){


                   //API Translate ------------------------------------------------------

                   $curl = curl_init();

                   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                   curl_setopt_array($curl, [
                       CURLOPT_URL => "https://text-translator2.p.rapidapi.com/translate",
                       CURLOPT_RETURNTRANSFER => true,
                       CURLOPT_FOLLOWLOCATION => true,
                       CURLOPT_ENCODING => "",
                       CURLOPT_MAXREDIRS => 10,
                       CURLOPT_TIMEOUT => 30,
                       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                       CURLOPT_CUSTOMREQUEST => "POST",
                       CURLOPT_POSTFIELDS => "source_language=pt&target_language=en&text=".$_POST["pais"]."",
                       CURLOPT_HTTPHEADER => [
                           "X-RapidAPI-Host: text-translator2.p.rapidapi.com",
                           "X-RapidAPI-Key: ",
                           "content-type: application/x-www-form-urlencoded"
                       ],
                   ]);

                   $response = curl_exec($curl);
                   $err = curl_error($curl);

                   curl_close($curl);

                   if ($err) {
                       echo "cURL Error #:" . $err;
                   } else {

                       $json = json_decode($response, true);

                       $_SESSION["pais_traduzido"]=$json["data"]["translatedText"]; // POR PARA UMA VARIAVEL O TEXTO TRADUZIDO
                   }


                   //API COVID 19 ------------------------------------------------------

                    $curl = curl_init();

                   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://covid-193.p.rapidapi.com/statistics?country=" . $_SESSION["pais_traduzido"] . "", //POR AQUI A VARIAVEL TRADUZIDA
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
                            "X-RapidAPI-Host: covid-193.p.rapidapi.com",
                            "X-RapidAPI-Key: "
                        ],
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        echo "cURL Error #:" . $err;

                    } else {
                                $json = json_decode($response, true);


                                if($json["results"]>0){
                                 ?>

                                <table class="table">
                                    <tr>
                                        <th>País</th>
                                        <td><?php echo $json["parameters"]["country"] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Continente</th>
                                        <td><?php echo $json["response"][0]["continent"] ?></td>
                                    </tr>
                                    <tr>
                                        <th>População</th>
                                        <td><?php echo $json["response"][0]["population"] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de Casos</th>
                                        <td><?php echo $json["response"][0]["cases"]["total"] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total de Mortes</th>
                                        <td><?php echo $json["response"][0]["deaths"]["total"] ?></td>
                                    </tr>

                                </table>

                                    <b>Última Atualização:</b>
                                    <p><b>Dia: </b><?php echo $json["response"][0]["day"] ?> </p>
                                    <b>Hora: </b><?php echo $json["response"][0]["time"]?>

                                    <?php }else{ //SE NÃO EXISTIREM RESULTADOS PARA O PAÍS PROCURADO?>

                                        <p>
                                            <div class="alert alert-danger" role="alert">
                                                Não existem resultados para o país que introduziu!
                                            </div>

                            <?php }

                     }

                           //API GEOCODING ------------------------------------------------------


                           $curl = curl_init();

                           curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                           curl_setopt_array($curl, [
                               CURLOPT_URL => "https://address-from-to-latitude-longitude.p.rapidapi.com/geolocationapi?address=".$_POST["pais"]."",
                               CURLOPT_RETURNTRANSFER => true,
                               CURLOPT_FOLLOWLOCATION => true,
                               CURLOPT_ENCODING => "",
                               CURLOPT_MAXREDIRS => 10,
                               CURLOPT_TIMEOUT => 30,
                               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                               CURLOPT_CUSTOMREQUEST => "GET",
                               CURLOPT_HTTPHEADER => [
                                   "X-RapidAPI-Host: address-from-to-latitude-longitude.p.rapidapi.com",
                                   "X-RapidAPI-Key: "
                               ],
                           ]);

                           $response = curl_exec($curl);
                           $err = curl_error($curl);

                           curl_close($curl);

                           if ($err) {
                               echo "cURL Error #:" . $err;
                           } else {
                               $json = json_decode($response, true);

                               $_SESSION["long"] = $json["Results"][0]["longitude"];
                               $_SESSION["lat"] = $json["Results"][0]["latitude"];

                           }



                     }?>


    </div>


    <!-- API MAPS ------------------------------------------------------ -->

    <script>
        let map;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: <?php echo $_SESSION["lat"]; ?>, lng: <?php echo $_SESSION["long"]; ?> }, //ALTERAR AS CORDENADAS
                zoom: 7,
            });
        }

        window.initMap = initMap;
    </script>

    <div id="map"></div>

    <script
            src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap&v=weekly"
            defer
    ></script>


    <div class="rodape">
        <center>
            <font style="text-align:center;font-size:15px;" face="'PT Sans', sans-serif" color="#000000" >© API - 2022 | Afonso Carvalho | Alexandra Souza | Cláudio Correia | Mônica Teixeira |</font>
        </center>
    </div>

</body>
</html>