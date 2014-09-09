<?php
require_once("../../common/common_define.php");
require_once("../../dboperation/dboperation.php");
?>
<?php
    // GETにきたpageidを取得する
    $pageid = "";
    if( empty($_GET["p"]) ){
        echo "ページが存在しません<br /><br />";
        exit;
    }
    else{
        $pageid = $_GET["p"];
    }
    if($pageid==="undefined")
    {
        echo "ページが存在しません<br /><br />";
        return;
    }

    // GETにきたmodeを取得する
    $mode = "";
    if( empty($_GET["m"]) ){
        $mode = "y";
    }
    else{
        $mode = $_GET["m"];
    }
    if($mode==="undefined")
    {
        $mode = "y";
    }
    
    // ページIDの情報を取得する
    $dbop = new DBoperation(DB_USER_NAME,DB_PASS,DB_HOST,DB_NAME);
    $sql = "SELECT year,month,day,hour,minute,title,edit_key FROM t_rcounter WHERE page_id='".$pageid."' AND fdel<>1";
    $result = $dbop->GetDataWithSql($sql);
    if (!$result) {
        echo mysql_error();
        exit;
    }
    $dispflag = 0;
    while ($row = mysql_fetch_assoc($result)) 
    {
        $title = $row['title'];
        echo $title;
        echo "<br />";
        echo "<br />";

        // 指定日付取得
        $st_year = $row['year'];
        $st_mon = $row['month'];
        $st_day = $row['day'];
        $st_hour = $row['hour'];
        $st_min = $row['minute'];

// For Debub
//$st_year ="2013";
//$st_mon ="1";
//$st_day ="16";
//$st_hour ="12";
//$st_min ="3";

        echo $st_year."年".$st_mon."月".$st_day."日 ".$st_hour."時".$st_min."分 から ";

        // 今日の日付
        $end_year = date("Y");
        $end_mon = date("n");
        $end_day = date("j");
        $end_hour = date("H");
        $end_min = date("i");
    //    echo $end_year."年".$end_mon."月".$end_day."日 ".$end_hour."時".$end_min."分 まで";
        echo "<br />";

        // 日表示の場合
        if($mode==="d")
        {
            // 年と月は0固定
            $diffyear = 0;
            $diffmon = 0;
            // 日数を求める
            $dt1 = mktime($end_hour, $end_min, 0, $end_mon, $end_day, $end_year);
            $dt2 = mktime($st_hour, $st_min, 0, $st_mon, $st_day, $st_year);
            $diffday = $dt1 - $dt2;
            $diffday = (int)($diffday / 86400);//1日は86400秒
        }
        // 年表示もしくは指定無しの場合
        else
        {
            // 年を引いて年数を求める
            $diffyear = $end_year-$st_year;

            // 月を引いて月数を求める
            $diffmon = $end_mon-$st_mon;
            // 月が0以下なら年をマイナス1してプラス12した月から引く
            if($diffmon<=0)
            {
                $diffyear = $diffyear-1;
                $diffmon = ($end_mon+12)-$st_mon;
            }

            // 日を引いて日数を求める
            $diffday = $end_day-$st_day;
            // 日がマイナスなら月をマイナス1して、開始日の月の最終日までの日数と今日の月の今日までの日数を足した値を日数とする
            if($diffday<0)
            {
                $diffmon = $diffmon-1;
                // 開始日の月の最終日までの日数を求める
                $work_year = $st_year;
                $work_mon = $st_mon;
                // 開始日が12月なら翌年の1月にする
                if($st_mon==="12")
                {
                    $work_year = $work_year+1;
                    $work_mon = "1";
                }
                else
                {
                    $work_mon = $work_mon+1;
                }
                $work_day = (strtotime($work_year."/".$work_mon."/1")-strtotime($st_year."/".$st_mon."/".$st_day))/(3600*24);
                $diffday = $end_day+($work_day-1); // 一日の分は引く
            }
        }

        // 時間を引いて時間を求める
        $diffhour = $end_hour-$st_hour;
        // 時間がマイナスなら日をマイナス1して、24を足して引く
        if($diffhour<0)
        {
            $diffday = $diffday-1;
            $diffhour = ($end_hour+24)-$st_hour;
        }

        // 分を引いて分を求める
        $diffmin = $end_min-$st_min;
        // 分がマイナスなら時間をマイナス1して、プラス60した分から引く
        if($diffmin<0)
        {
            $diffhour = $diffhour-1;
            // 時間がマイナスになってしまったら、日をマイナス1して、同じ時間ということなので時間に23を設定する
            if($diffhour<0)
            {
                $diffday = $diffday-1;
                $diffhour = 23;
            }
            $diffmin = ($end_min+60)-$st_min;
        }

        $len = 0;
        $cnt = 0;
        // 年数があれば年を表示する
        if(0<$diffyear)
        {
            $stryear = (string)$diffyear;
            $len = strlen($stryear);
            for($cnt=0; $cnt<$len; $cnt++)
            {
                echo '<img src="../image/'.$stryear[$cnt].'.gif">';
            }
            echo "年";
        }
        // 月数があれば月数を表示する
        if(0<$diffmon)
        {
            $strmon = (string)$diffmon;
            $len = strlen($strmon);
            for($cnt=0; $cnt<$len; $cnt++)
            {
                echo '<img src="../image/'.$strmon[$cnt].'.gif">';
            }
            echo "ヶ月";
        }
        // 日数があれば日数を表示する
        if(0<$diffday)
        {
            $strday = (string)$diffday;
            $len = strlen($strday);
            for($cnt=0; $cnt<$len; $cnt++)
            {
                echo '<img src="../image/'.$strday[$cnt].'.gif">';
            }
            echo "日";
        }
        // 年月日のどれかがあれば"と"を表示する
        if(0<$diffyear||0<$diffmon||0<$diffday)
        {
            echo "と";
        }
        // 時間があれば時間を表示する
        if(0<$diffhour)
        {
            $strhour = (string)$diffhour;
            $len = strlen($strhour);
            for($cnt=0; $cnt<$len; $cnt++)
            {
                echo '<img src="../image/'.$strhour[$cnt].'.gif">';
            }
            echo "時間";
        }
        // 分を表示する
        $strmin = (string)$diffmin;
        $len = strlen($strmin);
        for($cnt=0; $cnt<$len; $cnt++)
        {
            echo '<img src="../image/'.$strmin[$cnt].'.gif">';
        }
        echo "分 経過";
        
        echo "<br />";
        echo "<br />";
        
        // 年日切替え
        if($mode==="y")
        {
            echo "<a href=\"?p=".$pageid."&m=d\">日表示に切替え</a><br />";
            //echo "<input type=\"button\" name=\"chg\" value=\"日表示\" language=\"javascript\" onclick=\"self.location.href='?p=".$pageid."&m=d'\"><br />";
        }
        else if($mode==="d")
        {
            echo "<a href=\"?p=".$pageid."&m=y\">年表示に切替え</a><br />";
            //echo "<input type=\"button\" name=\"chg\" value=\"年表示\" language=\"javascript\" onclick=\"self.location.href='?p=".$pageid."&m=y'\"><br />";
        }
        
        echo "1分で自動更新";
        echo "<br />";

// For Debug
//echo $diffyear."年";
//echo $diffmon."ヶ月";
//echo $diffday."日";
//echo $diffhour."時間";
//echo $diffmin."分経過";
        
        // 編集機能
        echo "<br />";
        echo '<form action="../edit/?p='. $pageid .'" method="post" enctype="multipart/form-data">';
        echo "編集キー&nbsp;";
        echo '<input class="short_widht" type="text" id="editkey" name="editkey" />';
        echo '<input type="submit" value="修正"/>';
        echo "</form>";
        
        $dispflag = 1;
    }
    if($dispflag==0)
    {
        echo "ページが存在しません<br /><br />";
    }
?>
