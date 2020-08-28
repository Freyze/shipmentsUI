<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include_once ("config.php");
include_once ('model.php');

$conn = mysqli_connect($host, $user, $password, $database)
or die("Database connect error: " . mysqli_error($conn));

if (isset($_GET['offset']))
    $offset = $_GET['offset'];
else $offset = 0;

$shipments = getShipmentByParams($conn, $_GET, $offset);

$shipmentsHTML = createShipmentsHTML($conn, $shipments);

$conn->close();

function nextPageBtn($offset) {

    if ($offset == 0) {
        return '<a href="./?offset=25">Next page</a>';
    } else {
        return '<a href="./?offset=' . ($offset+25) . '">Next page</a>';
    }

}

function previousPageBtn($offset) {

    if ($offset >= 1) {

        if ($offset < 25) $offset = 0;
        else $offset -= 25;

        return '<a href="./?offset=' . $offset . '">Previous Page</a>';
    } else {
        return false;
    }

}

function createShipmentsHTML($conn, $lastShipments) {

    if (isEmpty($lastShipments)) return false;

    $html = '';

    foreach ($lastShipments as $shipmentInfo) {

        $contentCharacteristic = '';

        if (!isEmpty($shipmentInfo["min_mass"])) {
            $contentCharacteristic .= "<br>".$shipmentInfo["min_mass"]."тн.";
        }
        if (!isEmpty($shipmentInfo["max_mass"]) && $shipmentInfo["max_mass"] != $shipmentInfo["min_mass"]) {
            $contentCharacteristic .= "-".$shipmentInfo["max_mass"]."тн.";
        }

        if (!isEmpty($shipmentInfo["min_size"])) {
            $contentCharacteristic .= "  ".$shipmentInfo["min_size"]."м3";
        }
        if (!isEmpty($shipmentInfo["max_size"]) && $shipmentInfo["max_size"] != $shipmentInfo["min_size"]) {
            $contentCharacteristic .= "-".$shipmentInfo["max_size"]."м3";
        }

        if (!isEmpty($shipmentInfo["content_length"])) {
            $contentCharacteristic .= "<br>Длн:".$shipmentInfo["content_length"];
        }
        if (!isEmpty($shipmentInfo["content_width"])) {
            $contentCharacteristic .= " Шир:".$shipmentInfo["content_width"];
        }
        if (!isEmpty($shipmentInfo["content_height"])) {
            $contentCharacteristic .= " Выс:".$shipmentInfo["content_height"];
        }

        $time = '';
        if (!isEmpty($shipmentInfo["add_time"]) && !isEmpty($shipmentInfo["edit_time"])) {
            $time = "<br>Разм. {$shipmentInfo["add_time"]}<br>Изм. {$shipmentInfo["edit_time"]}";
        }

        if ($shipmentInfo["date_from"] == $shipmentInfo["date_to"]) {
            $date = $shipmentInfo["date_from"];
        } else {
            $date = "{$shipmentInfo["date_from"]} - {$shipmentInfo["date_to"]}";
        }

//        var_dump($shipmentInfo["shipment_status"]);
//        $deleted = ($shipmentInfo["shipment_status"] == 3 || $shipmentInfo["parser_status"] == 4) ? 'class="deleted"' : '';

        $html .= "
    <tr data-id='{$shipmentInfo["shipment_id"]}'>
        <td>{$shipmentInfo["shipment_id"]}</td>
        <td>{$shipmentInfo["request_id"]}</td>
        <td style='min-width: 120px;'>$date<br>$time</td>
        <td>{$shipmentInfo["city_from"]}<br>{$shipmentInfo["area_from"]}</td>
        <td>{$shipmentInfo["city_to"]}<br>{$shipmentInfo["area_to"]}</td>
        <td>{$shipmentInfo["content_name"]} $contentCharacteristic</td>
        <td>{$shipmentInfo["truck_type"]}</td>
        <td>{$shipmentInfo["payment_type"]}  {$shipmentInfo["payment_time"]}<br>Цена клиента: {$shipmentInfo["price"]}<br>Наша цена: {$shipmentInfo["lardi_price"]}</td>
        <td style='max-width: 200px;'><a href='{$shipmentInfo["shipment_url"]}' target='_blank'>Заявка</a><br></td>
        <td><textarea class='note' oninput='note(this)'>{$shipmentInfo["notation"]}</textarea></td>
        <td style='min-width: 70px;'><div class='pointer hover-darkred' onclick='setRed(this)'>Удалить</div><div class='pointer hover-darkred' onclick='setGreen(this)'>В работе</div></td>
    </tr>";


    }

    return $html;
}

/**
 * Check whether variable is empty
 * @param int|bool|array|string $data - anything for check on empty
 *
 * @return boolean
 */
function isEmpty($data) {

    switch ($data) {
        case "":
        case "0":
        case null:
        case false:
            return true;
        default:
            return false;
    }

}

include_once ('view.php');