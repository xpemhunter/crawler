<!DOCTYPE html>
<html>
    <head>
        <title>Crawler Bot Report</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            .list {
            border-collapse: collapse;
            width: 100%;
            border-top: 1px solid #DDDDDD;
            border-left: 1px solid #DDDDDD;
            margin-bottom: 20px;
            }
            .list td {
                    border-right: 1px solid #DDDDDD;
                    border-bottom: 1px solid #DDDDDD;
            }
            .list thead td {
                    background-color: #EFEFEF;
                    padding: 0px 5px;
            }
            .list thead td a, .list thead td {
                    text-decoration: none;
                    color: #222222;
                    font-weight: bold;
            }
            .list tbody td a {
                    text-decoration: underline;
            }
            .list tbody td {
                    vertical-align: middle;
                    padding: 0px 5px;
                    background: #FFFFFF;
            }
            .list .left {
                    text-align: left;
                    padding: 7px;
            }
            .list .right {
                    text-align: right;
                    padding: 7px;
            }
            .list .center {
                    text-align: center;
                    padding: 7px;
            }
        </style>
    </head>
    <body>
        <div>
            <table class="list">
                <thead>
                    <tr>
                        <td>адрес страницы</td>
                        <td>кол-во тегов</td>
                        <td>длительность обработки страницы (сек.)</td>
                    </tr>
                </thead>
                <tbody>
                    <? if(!empty($content)): ?>
                        <? foreach($content as $url => $info): ?>
                            <tr>
                                <td><?= $url ?></td>
                                <td><?= $info['cnt'] ?></td>
                                <td><?= number_format($info['time'], 2) ?></td>
                            </tr>
                        <? endforeach; ?>
                    <? endif; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>