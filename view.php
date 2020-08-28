<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Della Cargo</title>
<!--    <link type="text/css" rel="stylesheet" href="./vendor/bootstrap.css">-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<style>

    table {
        width: 100%;
        max-width: 100%;
        text-align: center;
    }
    th {
        font-size: 13px;
        font-weight: normal;
        background: #b9c9fe;
        border-top: 4px solid #aabcfe;
        border-bottom: 1px solid #fff;
        color: #039;
        padding: 8px;
    }
    td {
        background: #e8edff;
        border-bottom: 1px solid #fff;
        color: #669;
        border-top: 1px solid transparent;
        padding: 8px;
    }
    tr:hover td {background: #ccddff;}

    input {
        font-size: 18px;
        padding: 8px;
    }

    .note {
        height: 100%;
        width: 100%;
        border: none;
        resize: none;
        overflow: auto;
    }

    .pointer {
        cursor: pointer;
    }

    .hover-darkred:hover {
        color: darkred;
    }

    .deleted {
        background-color: rgb(245, 220, 220);
    }

</style>

<script>
    function setType(id, type) {
        $.ajax({
            url: 'ajax_requests.php',
            method: 'POST',
            data: {id: id, status: type},
            success: function (data) {
                if (data.code !== 0) {
                    alert('Произошла ошибка. Попробуйте перезагрузить страницу.')
                }
            },
            error: function () {
                alert('Произошла ошибка. Попробуйте перезагрузить страницу.')
            }
        })
    }

    function setRed(el) {
        var table = el.parentElement.parentElement;
        var id = table.getAttribute('data-id');
        if (confirm('Удалить ID ' + id + '?')){
            $(table).css('background-color', '#f5dcdc');
            setType(id, 3)
        }
    }

    function setGreen(el) {
        var table = $(el.parentElement.parentElement);
        var id = table.attr('data-id');
        table.css('background-color', '#c1ffc9');
        let s = `${table.data('date')}, ${table.data('id')}, ${table.data('city-from')} — ${table.data('city-to')}, ${table.data('cargo')}`;
        copy(s.replace(/<.*>/, " —"));
        setType(id, 2)
    }

    function setWhite(el) {
        var table = el.parentElement.parentElement;
        var id = table.getAttribute('data-id');
        $(table).css('background-color', 'white');
        setType(id, 1)
    }

    function note(el) {
        var table = el.parentElement.parentElement;
        var id = table.getAttribute('data-id');
        var value = el.value;
        $.ajax({
            url: 'ajax_requests.php',
            data: {id: id, note: value},
            method: 'POST',
            success: function (data) {
                if (data !== "true") {
                    alert('Произошла ошибка. Попробуйте перезагрузить страницу. Возможно превышен лимит нотации.')
                }
            },
            error: function () {
                alert('Произошла ошибка. Попробуйте перезагрузить страницу. Возможно превышен лимит нотации.')
            }
        })
    }
</script>
<body>
<form style="display: flex" method="get">
    <input type="text" placeholder="Id" style="width: 50%;" name="id">
<!--    <input type="text" placeholder="Della Id" style="width: 100%;" name="request-id">-->
    <input type="text" placeholder="Город от" style="width: 75%;" name="city-from">
    <input type="text" placeholder="Город куда" style="width: 75%;" name="city-to">
    <input type="text" placeholder="Область от" style="width: 100%;" name="area-from">
    <input type="text" placeholder="Область куда" style="width: 100%;" name="area-to">
    <input type="text" placeholder="Телефон" style="width: 100%;" name="phone">
    <input type="text" placeholder="Компания" style="width: 100%;" name="company-name">
    <button type="submit" style="width: 100%;">Поиск</button>
</form>
<table>
    <tr>
        <th>ID</th>
        <th>Дата</th>
        <th>Откуда</th>
        <th>Куда</th>
        <th>Груз</th>
        <th>Тип кузова</th>
        <th>Оплата</th>
        <th>Клиент</th>
        <th>Заметка</th>
        <th>Статус</th>
    </tr>
    <?php echo $shipmentsHTML; ?>
</table>
<span><?php echo previousPageBtn($offset); ?></span>

<span><?php echo nextPageBtn($offset); ?></span>
</body>
</html>