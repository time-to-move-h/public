<?php
declare(strict_types=1);
$event_title = $result['EVT_TITLE'];
$address_title = 'Lieu';
$address_venue = $result['ADDRESS_VENUE'];
$address_street = $result['ADDRESS_STREET'];
$address_streetn = $result['ADDRESS_STREETN'];
$address_pcode = $result['ADDRESS_PCODE'];
$address_city = $result['ADDRESS_CITY'];
$address_state = $result['ADDRESS_STATE'];
$address_country = $result['ADDRESS_COUNTRY'];
$period_title = 'Date et Heure';
$period_formatted = '';

// Date Formatted
if ($result['EVT_DATBEG'] !== null) {
    $date_start = new DateTime($result['EVT_DATBEG'],new \DateTimeZone($result['TIMEZONE_BEG']));
    $date_end = null;
    if ($result['EVT_DATEND'] !== null) {
        $date_end = new DateTime($result['EVT_DATEND'],new \DateTimeZone($result['TIMEZONE_END']));
    }
    $dateformat = new \Moviao\Util\DateTimeFormat();
    $allday = false;
    if (isset($result['EVT_ALLDAY']) && $result['EVT_ALLDAY'] === '1') {
        $allday = true;
    }
    $period_formatted = $dateformat->formatDate($date_start,$date_end,"fr-BE",$allday,false); // TODO: Change user language
}

$order_title = 'Commande';
$order_number = $result['TICORDER'];
$buyer_fname = $result['BUYER_FNAME'];
$buyer_lname = $result['BUYER_LNAME'];
$buyer_ordered = 'Commandé par '. $buyer_fname . ' ' . $buyer_lname;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Title of the document</title>
    <style type="text/css">
        <!--
        div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;}
        h1 { padding: 0; margin: 0; color: #DD0000; font-size: 7mm; }
        h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
        -->
    </style>
</head>
<body>

<table style="width: 99%;border: none;" cellspacing="4mm" cellpadding="0">

        <tr>
            <td colspan="2" style="width: 100%">
                <div class="zone" style="height: 0mm;position: relative;font-size: 5mm;">

                    <h2><?php echo $event_title; ?></h2>
                    <!--                    &nbsp;&nbsp;&nbsp;&nbsp;<b>Valable le --><?php //echo $date; ?><!-- à 20h30</b><br>-->
                    <!--                    <img src="logo.gif" alt="logo" style="margin-top: 3mm; margin-left: 20mm">-->
                    <hr>

                    <div style="left: 90mm; bottom: 20mm; text-align: left; font-size: 4mm;">
                        <b><?php echo $period_title; ?></b><br>
                        <?php echo $period_formatted; ?><br><br>
                    </div>

                    <div style="left: 3mm; bottom: 0mm; text-align: left; font-size: 4mm; ">
                        <b><?php echo $address_title; ?></b><br>
                        <?php echo $address_venue; ?><br>
                        <?php echo $address_street; ?>
                        <?php
                            if (! empty($address_streetn)) {
                                echo ' ' . $address_streetn;
                            }

                        ?><br>
                        <?php
                            if (! empty($address_pcode)) {
                                echo $address_pcode . ' ';
                            }
                            echo $address_city;

                            if (! empty($address_state)) {
                                echo '<br>' . $address_state;
                            }
                        ?>
                        <br>
                        <?php echo $address_country; ?>
                        <br><br>
                    </div>

                    <div style="left: 90mm; bottom: 0mm; text-align: left; font-size: 4mm;">
                        <b><?php echo $order_title; ?></b><br>
                        #<?php echo $order_number; ?><br>
                        <?php echo $buyer_ordered; ?><br>
                    </div>

                </div>

            </td>
        </tr>

        <tr>

            <td style="width: 25%;">
                <div class="zone" style="height: 40mm;vertical-align: middle;text-align: center;">
                    <qrcode value="<?php echo $order_number; ?>" ec="Q" style="width: 37mm; border: none;" ></qrcode>
                </div>
            </td>

            <td style="width: 75%">
                <div class="zone" style="height: 40mm;vertical-align: middle; text-align: justify">
                    <b>Conditions d'utilisation du billet</b><br>
                    Le ticket est soumis aux conditions générales de vente que vous avez
                    acceptées avant l'achat du billet. Le ticket d'entrée est uniquement
                    valable s'il est imprimé sur du papier A4 blanc, vierge recto et verso.
                    L'entrée est soumise au contrôle de la validité de votre billet. Une bonne
                    qualité d'impression est nécessaire. Les tickets partiellement imprimés,
                    souillés, endommagés ou illisibles ne seront pas acceptés et seront
                    considérés comme non valables. En cas d'incident ou de mauvaise qualité
                    d'impression, vous devez imprimer à nouveau votre fichier. Pour vérifier
                    la bonne qualité de l'impression, assurez-vous que les informations écrites
                    sur le billet, ainsi que les pictogrammes (code à barres 2D) sont bien
                    lisibles. Ce titre doit être conservé jusqu'à la fin de la manifestation.
                    Une pièce d'identité pourra être demandée conjointement à ce billet. En
                    cas de non respect de l'ensemble des règles précisées ci-dessus, ce billet
                    sera considéré comme non valable.<br>
                    <br>
                    <i>Ce billet est reconnu électroniquement lors de votre
                        arrivée sur site. A ce titre, il ne doit être ni dupliqué, ni photocopié.
                        Toute reproduction est frauduleuse et inutile.</i>
                    <br><br>


                    <barcode type="C39" value="<?php echo $order_number; ?>" style="color: #000000" ></barcode>

                </div>

            </td>
        </tr>
    </table>
</body>
</html>