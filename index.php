<?php
require_once("../common/common_define.php");
require_once("../dboperation/dboperation.php");
?>
<?php
    // GETにきたmodeを取得する
    $mode = "";
    if( empty($_GET["m"]) ){
        $mode = "";
    }
    else{
        $mode = $_GET["m"];
    }
    
    // 登録処理を行う
    if($mode==="set")
    {
        $year = $_POST["year"];
        $month = $_POST["month"];
        $day = $_POST["day"];
        $hour = $_POST["hour"];
        $bigminute = $_POST["bigmin"];
        $litleminute = $_POST["litmin"];
        $minute = $bigminute.$litleminute;
        $title = $_POST["title"];
        $editkey = $_POST["editkey"];

        // 指定日時が未来だったらエラーメッセージを表示する
        $now = date('Y-n-j G:i');
        $checkdate = $year."-".$month."-".$day." ".$hour.":".$minute;
        if(strtotime($now) < strtotime($checkdate))
        {
            echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
            echo "未来の日時は指定できません";
            echo '</body>';
            exit;
        }
        
        // 存在しない日時（2月31日など）だったらエラーメッセージを表示する
        if(!checkdate($month, $day, $year))
        {
            echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
            echo $year."年".$month."月".$day."日は存在しません";
            echo '</body>';
            exit;
        }
                
        // ユニークなページIDを取得する
        $uniqpageid = uniqid(rand(10,99));
        
        // テーブルに登録する
        $dbop = new DBoperation(DB_USER_NAME,DB_PASS,DB_HOST,DB_NAME);
        $sql = "";
        $sql = $sql . "INSERT INTO t_rcounter ";
        $sql = $sql . "(year,month,day,hour,minute,title,edit_key,page_id) ";
        $sql = $sql . "VALUES ";
        $sql = $sql . "('" . $year . "','" . $month ."','" . $day . "','" . $hour . "','" . $minute . "',";
        $sql = $sql . "'" . $title . "','" . $editkey . "','" . $uniqpageid . "')";
        $result = $dbop->GetDataWithSql($sql);
        if($result){
            // 作成ページへ移動
?>
<script>
    document.location = "disp/?p=<?php echo $uniqpageid;?>";
</script>
<?php
        }
        else
        {
            // 作成に失敗した場合エラーメッセージ表示
            echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
            echo "ごめんなさい　なぜか作成できませんでした";
            echo '</body>';
            exit;
        }
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>rCounter</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="description" content="日時カウンター">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=2.0,user-scalable=yes" />
        <meta http-equiv=Content-Script-Type content="text/javascript">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <script src="../js/script.js" type="text/javascript"></script>
        <link href="../style/style.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <h1>
            <img alt="日時カウンター rCounter" title="日時カウンター rCounter" src="image/logo.png" border=0 />
        </h1>
        <font class="color_grey">指定日時から「＊年＊ヶ月＊日と＊時間＊分」経過したのかカウントするページを作成できます。</font><br />
        <br />
        <form action="?m=set" method="post" enctype="multipart/form-data">
            <input class="short_widht" type="text" id="year" name="year" />年
            <select name="month">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>月
            <select name="day">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
            </select>日&nbsp;
            <select name="hour">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
            </select>時
            <select name="bigmin">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <select name="litmin">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
            </select>分からカウント<br />
            <br />
            タイトル&nbsp;
            <input class="form_widht" type="text" id="title" name="title" /><br />
            <br />
            編集キー&nbsp;
            <input class="short_widht" type="text" id="editkey" name="editkey" /><br />
            <br />
            <input type="submit" value="作成"/>
        </form>
        
        <font class="color_pink">※編集キーは忘れないようお控えください。</font><br />
        <font class="color_pink">※作成後自動で作成したページへ移動します。移動先のページを忘れないように必ずブックマークしておいてください。</font><br />
        <br />
        <a href="../contact/input.php?sv=3" onclick="window.open('../contact/input.php?sv=3', 'inputwindow', 'width=400,height=400,toolbar=no,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes'); return false;">問い合わせ</a><br />
        <br />
        <a href="http://ma1744.main.jp" style="text-decoration: none"><font style="color: #cccccc;" size="1">ma1744 works</font></a>

<link type="text/css" rel="stylesheet" href="../style/exvalidation.css" />
<script src="../js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/exvalidation.js"></script>
<script type="text/javascript" src="../js/exchecker-ja.js"></script>
<script type="text/javascript">
$("form").exValidation({
    rules: {
        year: "chkrequired chknumonly chkmin4 chkmax4",
        title: "chkrequired chkmax20",
        editkey: "chkrequired chkmax20 chknocaps"
    }
});
</script>

    </body>
</html>
