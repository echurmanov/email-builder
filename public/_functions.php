<?php

function getHtmlSendCode($startCid)
{
    $html = <<<HTML
    <html>
        <head></head>
        <body>

<table width="1407" cellpading=0 cellspacing=0 background="#d1bda4">
        <tr>
            <td colspan="2" style="background-image: url(cid:100005);
             height: 283px; color: #082755; font-weight: bold; padding-left: 170px;
             font-size: 20px; vertical-align: bottom; padding-bottom: 50px;"
            >
                Адрес редакции:<br/>
                Адрес: 111123, Москва, Электродный проезд, д. 6, оф. 14<br/>
                Тел./факс: +7 (495) 645-12-21<br/>
                Отдел подписки: +7 (495) 64-555-82<br/>
                Отдел подписки в Екатеринбурге: +7 (343) 383-27-88<br/>
                <big>Email: mail@eepr.ru</big>
            </td>
        </tr>

HTML;

    foreach ($_SESSION['items'] as $index => $itemData) {
        if ($itemData['type'] != 'banner') {
            $date = $itemData['date'];
            $date = explode('.', $date);
            $imageCid = $index + $startCid;
            $html .= <<<HTML
    <tr>
            <td width="220" style="background-image: url(cid:100002);
                font-weight: bold; color: #FFFFFF; vertical-align: top;
            ">
                <div style="background: url(cid:100001) no-repeat; margin: 5px 0 0px 100px; padding: 5px 25px 40px 40px">
                    {$date[0]}.{$date[1]}.<br/>
                    {$date[2]}
                </div>
            </td>
            <td width="1367" style="background-image:url(cid:100004); background-repeat:repeat-y;background-position:right;
                padding-left: 10px; padding-right: 140px;
                vertical-align: top;
            " >
                <span style="font-weight: bold; font-size: 20px; color: #F06129;">
                    {$itemData['title']}
                </span><br clear="both"/>
HTML;
            if (isset($itemData['imageIndex'])) {
                $html .= <<<HTML
        <img style='float: left; padding-right: 10px; padding-bottom: 5px;' src='cid:{$imageCid}'
                    width='{$itemData['imageWidth']}' width='{$itemData['imageHeight']}'/>
HTML;

            }
            $html .= <<<HTML
                <p style="font-weight: bold; color: #525B6C; font-size: 13px;">{$itemData['text']}</p>
                <div style="background: url(cid:100003) no-repeat right; text-align: right;
                    padding: 10px 15px 20px 0;
                    margin-right: -35px;
                ">
                    <a style="color: #FFFFFF; font-weight: bold; font-size: 13px; text-decoration: none;" target="_blank" href="{$itemData['link']}">Читать далее</a>
                </div>
            </td>
        </tr>
HTML;
        } else {
            $html .= <<<HTML
            <tr>
                <td width="220" style="background-image: url(cid:100002);
                 font-weight: bold; color: #FFFFFF; vertical-align: top;
            ">
            <td style='text-align: center;background-image: url(cid:100004); background-repeat:repeat-y; background-position: right;
                padding-left: 10px; padding-right: 140px;'><a href='{$itemData['link']}' target='_blank'>
                <img src='cid:{$imageCid}' width='{$itemData['imageWidth']}'
                height='{$itemData['imageHeight']}'/></a></td>
            </tr>
HTML;
        }

    }

    $html .= '</table></body></html>';
    return $html;
}


function getHtmlPreviewCode()
{
    $html = <<<HTML
<table width="1407" cellpading=0 cellspacing=0 background="#d1bda4">
        <tr>
            <td colspan="2" style="background-image: url('images/background-top.png');
             height: 283px; color: #082755; font-weight: bold; padding-left: 170px;
             font-size: 20px; vertical-align: bottom; padding-bottom: 50px;"
            >
                Адрес редакции:<br/>
                Адрес: 111123, Москва, Электродный проезд, д. 6, оф. 14<br/>
                Тел./факс: +7 (495) 645-12-21<br/>
                Отдел подписки: +7 (495) 64-555-82<br/>
                Отдел подписки в Екатеринбурге: +7 (343) 383-27-88<br/>
                <big>Email: mail@eepr.ru</big>
            </td>
        </tr>

HTML;

    foreach ($_SESSION['items'] as $itemData) {
        if ($itemData['type'] != 'banner') {
            $date = $itemData['date'];
            $date = explode('.', $date);
            $html .= <<<HTML
    <tr>
            <td width="220" style="background-image: url('images/background-left.png');
                font-weight: bold; color: #FFFFFF; vertical-align: top;
            ">
                <div style="background: url('images/background-date.png') no-repeat; margin: 5px 0 0px 100px; padding: 5px 35px 40px 40px">
                    {$date[0]}.{$date[1]}.<br/>
                    {$date[2]}
                </div>
            </td>
            <td style="background-image: url('images/background-right.png'); background-repeat:repeat-y; background-position: right;
                padding-left: 10px; padding-right: 140px;
                vertical-align: top;
            " >
                <span style="font-weight: bold; font-size: 20px; color: #F06129;">
                    {$itemData['title']}
                </span><br clear="both"/>
HTML;
            if (isset($itemData['imageIndex'])) {
                $html .= <<<HTML
        <img style='float: left; padding-right: 10px; padding-bottom: 5px;' src='image.php?image={$itemData['imageIndex']}'
                    width='{$itemData['imageWidth']}' width='{$itemData['imageHeight']}'/>
HTML;

            }
            $html .= <<<HTML
                <p style="font-weight: bold; color: #525B6C; font-size: 13px;">{$itemData['text']}</p>
                <div style="background: url('images/background-read.png') no-repeat right; text-align: right;
                    padding: 10px 15px 20px 0;
                    margin-right: -35px;
                ">
                    <a style="color: #FFFFFF; font-weight: bold; text-decoration: none; font-size: 13px;" target="_blank" href="{$itemData['link']}">Читать далее</a>
                </div>
            </td>
        </tr>
HTML;
        } else {
            $html .= <<<HTML
            <tr>
                <td width="220" style="background-image: url('images/background-left.png');
                 font-weight: bold; color: #FFFFFF; vertical-align: top;
            ">
            <td style='text-align: center;background-image: url("images/background-right.png"); background-repeat:repeat-y; background-position: right;
                padding-left: 10px; padding-right: 140px;'><a href='{$itemData['link']}' target='_blank'>
                <img src='image.php?image={$itemData['imageIndex']}' width='{$itemData['imageWidth']}'
                height='{$itemData['imageHeight']}'/></a></td>
            </tr>
HTML;
        }

    }

    $html .= '</table>';
    return $html;
}