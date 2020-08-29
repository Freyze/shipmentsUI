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

    .in-job {
        background-color: rgb(193, 255, 201);
    }

</style>

<script>
    function setType(id, type) {
        $.ajax({
            url: 'ajax_requests.php',
            method: 'POST',
            data: {id: id, status: type},
            success: function (data) {
                if (data !== "true") {
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
            $(el.parentElement.parentElement).children().removeClass("in-job");
            $(el.parentElement.parentElement).children().addClass("deleted");
            setType(id, 4)
        }
    }

    function setGreen(el) {
        let tr = $(el.parentElement.parentElement);
        $(el.parentElement.parentElement).children().removeClass("deleted");
        $(el.parentElement.parentElement).children().addClass("in-job");


        let id = tr.attr('data-id');
        let cityFrom = tr.attr('data-city-from');
        let cityTo = tr.attr('data-city-to');
        let date = tr.attr('data-date');
        let content = tr.attr('data-content');

        let s = `${date}, ${id}, ${cityFrom} — ${cityTo}, ${content}`;
        copy(s.replace(/<.*>/, " —"));
        setType(id, 6)
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

    function copy(text) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(text);
        $temp.select();
        document.execCommand("copy");
        $temp.remove();
        // console.log('Тест скопирован!');
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
<!--    <input type="text" placeholder="Телефон" style="width: 100%;" name="phone">-->
<!--    <input type="text" placeholder="Компания" style="width: 100%;" name="company-name">-->
    <a href="index.php"><button style="width: 100%;">Сбросить</button></a>
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