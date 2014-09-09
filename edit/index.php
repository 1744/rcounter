<?php
require_once("../../common/common_define.php");
require_once("../../dboperation/dboperation.php");
?>
<?php
    $errmsg = "";
    
    // GETにきたpageidを取得する
    $pageid = "";
    if( empty($_GET["p"]) ){
        echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
        echo "ページが存在しません";
        echo '</body>';
        exit;
    }
    else{
        $pageid = $_GET["p"];
    }
    
    // GETにきたmodeを取得する
    $mode = "";
    if( empty($_GET["m"]) ){
        $mode = "";
    }
    else{
        $mode = $_GET["m"];
    }

    $dbop = new DBoperation(DB_USER_NAME,DB_PASS,DB_HOST,DB_NAME);
    $sql = "";

    // 更新処理を行う
    if($mode==="edit")
    {
        // POSTの処理モードを取得する
        $mode = "";
        if( !empty($_POST["edit"]) ){
            $mode = "edit";
        }
        if( !empty($_POST["delete"]) ){
            $mode = "delete";
        }

        if($mode==="edit")
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

            // テーブルを更新する
            $sql = "";
            $sql = $sql . "UPDATE t_rcounter ";
            $sql = $sql . "SET year='".$year."', month='".$month."', day='".$day."', hour='".$hour."', minute='".$minute."', ";
            $sql = $sql . "title='".$title."', edit_key='".$editkey."' ";
            $sql = $sql . "WHERE page_id='".$pageid."' AND fdel<>1";
            $result = $dbop->GetDataWithSql($sql);
            if($result){
                // ページへ移動
?>
<script>
    document.location = "../disp/?p=<?php echo $pageid;?>";
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
        else if($mode==="delete")
        {
            // テーブルに削除フラグを立てる
            $sql = "";
            $sql = $sql . "UPDATE t_rcounter ";
            $sql = $sql . "SET fdel=1 ";
            $sql = $sql . "WHERE page_id='".$pageid."' ";
            $result = $dbop->GetDataWithSql($sql);
            if($result){
                // TOPへ移動
?>
<script>
    document.location = "..";
</script>
<?php
            }
            else
            {
                // 作成に失敗した場合エラーメッセージ表示
                echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
                echo "ごめんなさい　なぜか削除できませんでした";
                echo '</body>';
                exit;
            }            
        }
        else
        {
            // 処理なし
        }
    }
    // 登録データを取得する
    else
    {
        $sql = "SELECT year,month,day,hour,minute,title,edit_key FROM t_rcounter WHERE page_id='".$pageid."' AND fdel<>1";
        $result = $dbop->GetDataWithSql($sql);
        if (!$result) {
            echo mysql_error();
            exit;
        }
        while ($row = mysql_fetch_assoc($result)) 
        {
            $get_title = $row['title'];
            $get_editkey = $row['edit_key'];
            // 指定日付取得
            $st_year = $row['year'];
            $st_mon = $row['month'];
            $st_day = $row['day'];
            $st_hour = $row['hour'];
            $st_min = $row['minute'];
        }
        
        // 編集キーが一致するかチェックする
        $disp_editkey = $_POST["editkey"];
        if($disp_editkey!=$get_editkey)
        {
            echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
            echo "編集キーが違います";
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
        <script src="../../js/script.js" type="text/javascript"></script>
        <link href="../../style/style.css" type="text/css" rel="stylesheet">
<script type="text/javascript">
<!--
function dialogdisp(){
    if(window.confirm('このカウンターを削除してもいいですか？')){
        return true;
    }else{
        return false;
    }
}
// -->
</script>
    </head>
    <body>
        <img alt="日時カウンター rCounter" title="日時カウンター rCounter" src="../image/logo.png" border=0 width="164" height="47" /><br />
        <br />
        <font class="color_grey">修正する場合は、項目を修正して修正ボタンをクリックしてください。</font><br />
        <font class="color_grey">このカウンターを削除する場合は、削除ボタンをクリックしてください。</font><br />
        <font class="color_grey">変更せずに戻る場合は、ブラウザのバックでお戻りください。</font><br />
        <br />
        <form action="?m=edit&p=<?php echo $pageid; ?>" method="post" enctype="multipart/form-data">
            <input class="short_widht" type="text" id="year" name="year" value="<?php echo $st_year; ?>" />年
            <select name="month">
<?php
    for($cnt=1; $cnt<13; $cnt++)
    {
        if((int)$st_mon===$cnt)
        {
            echo '<option value="'.$cnt.'" selected>'.$cnt.'</option>';
        }
        else
        {
            echo '<option value="'.$cnt.'">'.$cnt.'</option>';
        }
    }
?>
            </select>月
            <select name="day">
<?php
    for($cnt=1; $cnt<32; $cnt++)
    {
        if((int)$st_day===$cnt)
        {
            echo '<option value="'.$cnt.'" selected>'.$cnt.'</option>';
        }
        else
        {
            echo '<option value="'.$cnt.'">'.$cnt.'</option>';
        }
    }
?>
            </select>日&nbsp;
            <select name="hour">
<?php
    for($cnt=0; $cnt<24; $cnt++)
    {
        if((int)$st_hour===$cnt)
        {
            echo '<option value="'.$cnt.'" selected>'.$cnt.'</option>';
        }
        else
        {
            echo '<option value="'.$cnt.'">'.$cnt.'</option>';
        }
    }
?>
            </select>時
            <select name="bigmin">
<?php
    for($cnt=0; $cnt<6; $cnt++)
    {
        if((int)$st_min[0]===$cnt)
        {
            echo '<option value="'.$cnt.'" selected>'.$cnt.'</option>';
        }
        else
        {
            echo '<option value="'.$cnt.'">'.$cnt.'</option>';
        }
    }
?>
            </select>
            <select name="litmin">
<?php
    for($cnt=0; $cnt<10; $cnt++)
    {
        if((int)$st_min[1]===$cnt)
        {
            echo '<option value="'.$cnt.'" selected>'.$cnt.'</option>';
        }
        else
        {
            echo '<option value="'.$cnt.'">'.$cnt.'</option>';
        }
    }
?>
            </select>分からカウント<br />
            <br />
            タイトル&nbsp;
            <input class="form_widht" type="text" id="title" name="title" value="<?php echo $get_title; ?>" /><br />
            <br />
            編集キー&nbsp;
            <input class="short_widht" type="text" id="editkey" name="editkey" value="<?php echo $get_editkey; ?>" /><br />
            <br />
            <input type="submit" value="修正" id="edit" name="edit" />
            <input type="submit" value="削除" id="delete" name="delete" onClick="return dialogdisp();" />
        </form>
        <br />

<link type="text/css" rel="stylesheet" href="../../style/exvalidation.css" />
<script src="../../js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/exvalidation.js"></script>
<script type="text/javascript" src="../../js/exchecker-ja.js"></script>
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
