<?php

function getShipmentByParams($conn, $inputGet, $offset) {

    if (isEmpty($conn)) return false;

    $selectWhereSQL = '';

    if (!isEmpty($inputGet['id'])) {

        if (strlen($selectWhereSQL) > 3) $selectWhereSQL .= " AND ";
        $shipmentId = $conn->real_escape_string($inputGet['id']);
        $selectWhereSQL .= " `shipments`.`id` = '$shipmentId' ";

    }

    if (!isEmpty($inputGet['city-from'])) {

        if (strlen($selectWhereSQL) > 3) $selectWhereSQL .= " AND ";
        $cityFrom = $conn->real_escape_string($inputGet['city-from']);
        $selectWhereSQL .= " `city_from` LIKE '%$cityFrom%' ";

    }

    if (!isEmpty($inputGet['city-to'])) {

        if (strlen($selectWhereSQL) > 3) $selectWhereSQL .= " AND ";
        $cityTo = $conn->real_escape_string($inputGet['city-to']);
        $selectWhereSQL .= " `city_to` LIKE '%$cityTo%' ";

    }

    if (!isEmpty($_GET['area-from'])) {

        if (strlen($selectWhereSQL) > 3) $selectWhereSQL .= " AND ";
        $areaFrom = $conn->real_escape_string($inputGet['area-from']);
        $selectWhereSQL .= " `area_from` LIKE '%$areaFrom%' ";

    }

    if (!isEmpty($_GET['area-to'])) {

        if (strlen($selectWhereSQL) > 3) $selectWhereSQL .= " AND ";
        $areaTo = $conn->real_escape_string($inputGet['area-to']);
        $selectWhereSQL .= " `area_to` LIKE '%$areaTo%' ";

    }

    if (!isEmpty($_GET['company-name'])) {

        if (strlen($selectWhereSQL) > 3) $selectWhereSQL .= " AND ";
        $companyName = $conn->real_escape_string($inputGet['company-name']);
        $selectWhereSQL .= " `company_name` LIKE '%$companyName%' ";

    }

    if (!isEmpty($_GET['phone'])) {

        if (strlen($selectWhereSQL) > 3) $selectWhereSQL .= " AND ";
        $phone = $conn->real_escape_string($inputGet['phone']);
        $selectWhereSQL .= " REPLACE(REPLACE(`phone`, '(', ''), ')', '') LIKE '%$phone%' ";

    }

    // Отображаем всё, если нет параметров для поиска
    if (empty($selectWhereSQL)) {
        $timeNow = time();
        $dayInSeconds = 86400;
        $selectWhereSQL = " $timeNow - `last_update_time` < $dayInSeconds ";
    }

    $selectSQL = "
                SELECT SQL_NO_CACHE `shipments`.*, `shipments_info`.*, `phones`.`phone` FROM `shipments`
                INNER JOIN `shipments_info` ON `shipments`.`id` = `shipments_info`.`shipment_id`
                LEFT JOIN `phones` ON `shipments`.`id` = `phones`.`shipment_id`
                WHERE $selectWhereSQL
                GROUP BY `shipment_id`
                ORDER BY `shipments`.`id` DESC
                LIMIT $offset, 25";
//    echo $selectSQL;
    $selectResult = $conn->query($selectSQL);

    if ($selectResult) {
        if ($selectResult->num_rows > 0) {

            $result = array();

            while($row = $selectResult->fetch_assoc()) {
                array_push($result, $row);
            }
            return $result;

        }
    }

    return false;

}