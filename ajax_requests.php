<?php

include_once ('config.php');

if (isset($_POST['id'])) {

    $conn = mysqli_connect($host, $user, $password, $database, $port);

    if (isset($_POST['note'])) {
        if (updateNotation($conn, $_POST['id'], $_POST['note'])) {
            echo "true";
        }
    }

    if (isset($_POST['status'])) {
        if (updateShipmentStatus($conn, $_POST['id'], $_POST['status'])) {
            echo "true";
        }
    }

    $conn->close();
}

function updateNotation($conn, $shipmentId, $notation) {

    if (isEmpty($conn) || isEmpty($shipmentId)) return false;

    $shipmentId = $conn->real_escape_string($shipmentId);
    $notation = $conn->real_escape_string($notation);

    $updateSQL = "UPDATE `shipments_info` SET `notation`='$notation' WHERE `shipment_id`='$shipmentId'";
    $updateResult = $conn->query($updateSQL);

    if ($updateResult)
        return true;
    else
        return false;

}

function updateShipmentStatus($conn, $shipmentId, $statusId) {

    if (isEmpty($conn) || isEmpty($shipmentId) || $statusId === false) return false;

    $shipmentId = $conn->real_escape_string($shipmentId);
    $statusId = $conn->real_escape_string($statusId);

    $updateSQL = "UPDATE `shipments` SET `parser_status` = '$statusId' WHERE `id`='$shipmentId'";
    $updateResult = $conn->query($updateSQL);

    if ($updateResult)
        return true;
    else
        return false;
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