<?php

use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;?>


Здесь представлены разные таблицы и ситуации. Но функция для фиксирования заголовков/столбцов универсальная. <button onclick="FixAction(this)">Зафиксировать</button>

<p>Простой случай. Зафиксирована 1 строка и 1 столбец. Объединенных ячеек нет.</p>
<table id="t1" style="width:520px;">
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>1</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>


<p>Здесь зафиксировано 2 строки и 1 столбец. Простое объединение ячеек</p>
<table id="t2" style="width:640px;">
    <tr><td rowspan=2 class="cntr">ФИО</td> <td colspan=4 class="cntr">2015 год</td> <td colspan=4 class="cntr">2016</td> </tr>
    <tr><td class="cntr">1-й квартал</td><td class="cntr">2-й квартал</td><td class="cntr">3-й квартал</td><td class="cntr">4-й квартал</td>
        <td class="cntr">1-й квартал</td><td class="cntr">2-й квартал</td><td class="cntr">3-й квартал</td><td class="cntr">4-й квартал</td></tr>
    <tr><td>Оливер Куин</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Лорел Лэнс</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Фелисити Смоук</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Тея Куин</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Малкольм Мерлин</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Слейд Уилсон</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Рой Харпер</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Квентин Лэнс</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Рас Аль Гул</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Нисса</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Рэй Палмер</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Барри Аллен</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Джон Дигл</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Сара Лэнс</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Мойра Куин</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Изабель Рошев</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>?</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>!</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>,</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>


<p>Фиксировано 10 строк и 6 столбцов. Сложное объединение ячеек, особенно где 2014 год, cells.length везде дает разную величину</p>
<table id="t3" style="width:1380px;">
    <tr>
        <td colspan=2 rowspan=2></td> <td colspan=4 class="cntr">2012</td> <td colspan=4 class="cntr">2013</td>
        <td colspan=4 class="cntr">2014</td><td colspan=4 class="cntr">2015</td>
    </tr>
    <tr>
        <td>1-й квартал</td><td>2-й квартал</td><td colspan=2>3-й квартал</td> <td>1-й квартал</td><td>2-й квартал</td><td>3-й квартал</td><td>4-й квартал</td>
        <td>1-й квартал</td><td colspan=3 style="font-weight:700">2-й квартал</td> <td>1-й квартал</td><td>2-й квартал</td><td>3-й квартал</td><td>4-й квартал</td>
    </tr>
    <tr><td rowspan=4>Производственная выручка</td> <td>Базовый план</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Актуальный план</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Факт</td><td></td> <td></td> <td></td> <td></td><td></td> <td></td> <td></td><td></td><td colspan=4 style="font-weight:700"></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Отклонение от баз.плана</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td rowspan=4>Актированная выручка</td> <td>Базовый план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Актуальный план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Факт</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Отклонение от баз.плана</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td rowspan=4>Внешние затраты</td> <td>Базовый план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Актуальный план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Факт</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Отклонение от баз.плана</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td rowspan=4>ПО и лицензии</td> <td>Базовый план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Актуальный план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Факт</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Отклонение от баз.плана</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td rowspan=4>Услуги подрядчиков</td> <td>Базовый план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Актуальный план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Факт</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Отклонение от баз.плана</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td rowspan=4>Оборудование и пр.</td> <td>Базовый план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Актуальный план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Факт</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Отклонение от баз.плана</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td rowspan=4>Функциональные затраты</td> <td>Базовый план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Актуальный план</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Факт</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><td>Отклонение от баз.плана</td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>


<p>Здесь зафиксирован только заголовок</p>
<table id="t4" style="width:324px;">
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
</table>


<p>А здесь первые 2 столбца</p>
<table id="t5" style="width:520px;">
    <tr><td></td><td></td><td></td><td></td>
        <td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>
</table>

<br><br>

<style>
    * {font:13px arial,sans-serif;}
    p {margin:28px 0 3px 0;}
    .divFixHeaderCol {
        position:relative;
        border:0;
        border-left:1px solid #d0d0d0;
        border-bottom:1px solid #d0d0d0;
        overflow:hidden;
    }
    .divFixHeaderCol table {border-collapse:collapse;}
    .divFixHeaderCol td {
        font:400 11px tahoma,arial,sans-serif;
        padding:2px;
        border:1px solid #d0d0d0;
        border-left:0;
        background:#fff;
        min-height:14px; /* если высота клиентской части в ячейке меньше 14px, то IE криво отрисовывает горизонтальные линии в фиксированной области */
        /* Самое интересное, что padding ни на что не влияет. Можно, например, сделать padding=16px и если клиентская высота < 14px, то всё равно IE криво отрисует линии */
        /* Если padding=0, а клиентская высота >= 14px, то будет нормально, IE ровно отрисует линии */
    }
    .divFixHeaderCol .fixRegion td {
        background:#fff no-repeat;
        background-image:linear-gradient(#eee,#eee);
        background-position:1px 1px;
    }
    .cntr {text-align:center;}

</style>


<script>
    function gid(i) {return document.getElementById(i);}
    function CEL(s) {return document.createElement(s);}
    function ACH(p,c) {p.appendChild(c);}

    function getScrollWidth() {
        var dv = CEL('div');
        dv.style.overflowY = 'scroll'; dv.style.width = '50px'; dv.style.height = '50px'; dv.style.position = 'absolute';
        dv.style.visibility = 'hidden';//при display:none размеры нельзя узнать. visibility:hidden - сохраняет геометрию, а выше было position=absolute - не сломает разметку
        ACH(document.body,dv);
        var scrollWidth = dv.offsetWidth - dv.clientWidth;
        document.body.removeChild(dv);
        return (scrollWidth);
    }

    /*function setSum(tbl, rr, cc) {
        var rowCount = tbl.rows.length, sum = '';
        for (var i=rr; i<rowCount; i++) {
            var row = tbl.rows[i];
            for (var j=cc; j < row.cells.length; j++) {
                sum = Math.floor(Math.random()*10000) + '';
                row.cells[i,j].innerHTML = sum.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ');
                row.cells[i,j].style.textAlign = 'right';
            }
        }
    }*/

    function FixAction(el) {
        FixHeaderCol(gid('t1'),1,1,400,170);
        FixHeaderCol(gid('t2'),2,1,400,214);
        FixHeaderCol(gid('t3'),10,6,1000,415);
        FixHeaderCol(gid('t4'),1,0,340,120);
        FixHeaderCol(gid('t5'),0,2,340,150);

        el.parentNode.removeChild(el);
    }

    function FixHeaderCol(tbl, fixRows, fixCols, ww, hh) {
        var scrollWidth = getScrollWidth(), cont = CEL('div'), tblHead = CEL('table'), tblCol = CEL('table'), tblFixCorner = CEL('table');
        cont.className = 'divFixHeaderCol';
        cont.style.width = ww + 'px'; cont.style.height = hh + 'px';
        tbl.parentNode.insertBefore(cont,tbl);
        ACH(cont,tbl);

        var rows = tbl.rows, rowsCnt = rows.length, i=0, j=0, colspanCnt=0, columnCnt=0, newRow, newCell, td;

        // Берем самую первую строку (это rows[0]) и получаем истинное число столбцов в ТАБЛИЦЕ (учитывается colspan)
        for (j=0; j<rows[0].cells.length; j++) {columnCnt += rows[0].cells[j].colSpan;}
        var delta = columnCnt - fixCols;

        // Пробежимся один раз по всем строкам и построим наши фиксированные таблицы
        for (i=0; i<rowsCnt; i++) {
            columnCnt = 0; colspanCnt = 0;
            newRow = rows[i].cloneNode(true), td = rows[i].cells;
            for (j=0; j<td.length; j++) {
                columnCnt += td[j].colSpan;//кол-во столбцов в данной строке с учетом colspan
                if (i<fixRows) {//ну и заодно фиксируем заголовок
                    newRow.cells[j].style.width = getComputedStyle(td[j]).width;
                    ACH(tblHead,newRow);
                }
            }

            newRow = CEL('tr');
            for (j=0; j<fixCols; j++) {
                if (!td[j]) continue;
                colspanCnt += td[j].colSpan;
                if (columnCnt - colspanCnt >= delta) {
                    newCell = td[j].cloneNode(true);
                    newCell.style.width = getComputedStyle(td[j]).width;
                    newCell.style.height = td[j].clientHeight - parseInt(getComputedStyle(td[j]).paddingBottom) - parseInt(getComputedStyle(td[j]).paddingTop) + 'px';
                    ACH(newRow,newCell);
                }
            }
            if (i<fixRows) {ACH(tblFixCorner,newRow);}
            ACH(tblCol,newRow.cloneNode(true));
        } // Закончили пробегаться один раз по всем строкам и строить наши фиксированные таблицы

        tblFixCorner.style.position = 'absolute'; tblFixCorner.style.zIndex = '3'; tblFixCorner.className = 'fixRegion';
        tblHead.style.position = 'absolute'; tblHead.style.zIndex = '2'; tblHead.style.width = tbl.offsetWidth + 'px'; tblHead.className = 'fixRegion';
        tblCol.style.position = 'absolute'; tblCol.style.zIndex = '2'; tblCol.className = 'fixRegion';

        cont.insertBefore(tblHead,tbl);
        cont.insertBefore(tblFixCorner,tbl);
        cont.insertBefore(tblCol,tbl);

        var bodyCont = CEL('div');
        bodyCont.style.cssText = 'position:absolute;';

        // Горизонтальная прокрутка
        var divHscroll = CEL('div'), d1 = CEL('div');
        divHscroll.style.cssText = 'width:100%; bottom:0; overflow-x:auto; overflow-y:hidden; position:absolute; z-index:3;';
        divHscroll.onscroll = function () {
            var x = -this.scrollLeft + 'px';
            bodyCont.style.left = x;
            tblHead.style.left = x;
        }

        d1.style.width = tbl.offsetWidth + scrollWidth + 'px';
        d1.style.height = '2px';

        ACH(divHscroll,d1);
        ACH(cont,divHscroll);
        ACH(bodyCont,tbl);
        ACH(cont,bodyCont);

        // Вертикальная прокрутка
        var divVscroll = CEL('div'), d2 = CEL('div');
        divVscroll.style.cssText = 'height:100%; right:0; overflow-x:hidden; overflow-y:auto; position:absolute; z-index:3';
        divVscroll.onscroll = function () {
            var y = -this.scrollTop + 'px';
            bodyCont.style.top = y;
            tblCol.style.top = y;
        }

        d2.style.height = tbl.offsetHeight + scrollWidth + 'px';
        d2.style.width = scrollWidth + 'px';

        ACH(divVscroll,d2);
        ACH(cont,divVscroll);

        cont.addEventListener('wheel', myWheel);
        function myWheel(e) {
            e = e || window.event;
            var delta = e.deltaY || e.detail || e.wheelDelta;
            var z = delta > 0 ? 1 : -1;
            divVscroll.scrollTop = divVscroll.scrollTop + z*17;
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);
        }
    } //FixHeaderCol

    //setSum(gid('t1'),0,0);
    setSum(gid('t2'),2,1);
    setSum(gid('t3'),2,2);
    setSum(gid('t4'),0,0);
    setSum(gid('t5'),0,0);

</script>