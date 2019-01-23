<?php
    session_start();

    $db_host = 'localhost';

    $db_name = 'ec';

    $db_user = 'root';

    $db_pass = 'tyoriu1125';

    // データベースへ接続する
    $link = mysqli_connect( $db_host, $db_user, $db_pass );
    mysqli_select_db($link, $db_name);

    mysqli_query($link,'SET NAMES utf8'  );

    if ( $link !== false ) {

	    //会員登録画面で「更新」ボタンがクリックされた時の処理。
	    //ログイン状態に応じて、UPDATE または INSERT を実行する。

	if( $_REQUEST["cmd"] == "regist_member" )
	{
		if( $_SESSION["customer_code"] != "" )
		{
			$query  = " UPDATE m_customers SET ";
			$query .= " customer_code = ".$_REQUEST["customer_code"].",";
			$query .= " pass = ".$_REQUEST["pass"].",";
			$query .= " name = ".$_REQUEST["name"].",";
			$query .= " address = ".$_REQUEST["address"].",";
			$query .= " tel = ".$_REQUEST["tel"].",";
			$query .= " mail = ".$_REQUEST["mail"]."";
			$query .= " WHERE customer_code = ".$_SESSION["customer_code"]."";

            $res    = mysqli_query( $link,$query );
			$is_success = 1;
		}
		else
		{
			$query = "INSERT INTO m_customers( customer_code, pass, name, address, tel, mail, del_flag, reg_date ) ";
			$query .= "VALUES( ";
			$query .= " ".$_REQUEST["customer_code"].", ";
			$query .= " ".$_REQUEST["pass"].", ";
			$query .= " ".$_REQUEST["name"].", ";
			$query .= " ".$_REQUEST["address"].", ";
			$query .= " ".$_REQUEST["tel"].", ";
			$query .= " ".$_REQUEST["mail"].", ";
			$query .= " '0', ";
			$query .= " now() ) ";

            $res    = mysqli_query( $link,$query );
			$is_success = 1;
		}
	}

	// ログイン済であれば、お客様の情報をデータベースより取得。
	if( $_SESSION["customer_code"] != "" )
	{
		$query = " SELECT * FROM m_customers ";
		$query.= " WHERE customer_code like '".$_SESSION["customer_code"]."'";

		$count = 0;
        $res    = mysqli_query( $link,$query );
		$row = mysqli_fetch_assoc( $res );
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>登録情報｜楽器の通販サイト  oh yeah !!</title>
    <link href="common/css/base.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="common/js/base.js"></script>
</head>
<body>
    <div id="wrap">
        <div id="contents">
            <!-- 右コンテンツ -->
            <div id="rightbox">
                <div id="main">
                    <div id="main2">
                        <!-- ↑↑タイトル以外共通部分↑↑ -->

                        <!-- メイン部分 各ページごとに作成-->
                        <div id="mainbox" class="clearfix">
                            <h2>登録情報</h2>

                            <?php
	                        if( $is_success == 1 )
	                        {
                            ?>
                            <br />
                            <p align="center">正常に処理が完了しました。</p>
                            <?php
	                        }
                            ?>

                            <form name="member_form" action="member.php" method="post">
                                <input type="hidden" name="cmd" value="regist_member" />
                                <div class="info clearfix">
                                    <dl>
                                        <dt>ID：</dt>
                                        <dd>
                                            <input type="text" name="customer_code" <?php if( $row["customer_code"] != "" ){ print( "readonly" ); } ?> value="<?php print( htmlspecialchars( $row["customer_code"], ENT_QUOTES ) ); ?>" />
                                        </dd>
                                        <dt>パスワード：</dt>
                                        <dd>
                                            <input type="password" name="pass" value="<?php print( htmlspecialchars( $row["pass"] , ENT_QUOTES) ); ?>" />
                                        </dd>
                                        <dt>氏名：</dt>
                                        <dd>
                                            <input type="text" name="name" value="<?php print( htmlspecialchars( $row["name"], ENT_QUOTES ) ); ?>" />
                                        </dd>
                                        <dt>住所：</dt>
                                        <dd>
                                            <input type="text" name="address" value="<?php print( htmlspecialchars( $row["address"], ENT_QUOTES ) ); ?>" />
                                        </dd>
                                        <dt>電話：</dt>
                                        <dd>
                                            <input type="text" name="tel" value="<?php print( htmlspecialchars( $row["tel"], ENT_QUOTES ) ); ?>" />
                                        </dd>
                                        <dt>アドレス：</dt>
                                        <dd>
                                            <input type="text" name="mail" value="<?php print( htmlspecialchars(  $row["mail"], ENT_QUOTES ) ); ?>" size="30" />
                                        </dd>
                                    </dl>
                                    <input type="submit" class="update" value="登録" />
                                </div>
                            </form>
                        </div>
                        <!-- /メイン部分 各ページごとに作成-->
                        <?php
                                    
                                } else {

                                    echo "データベースの接続に失敗しました";

                                }

                                // データベースへの接続を閉じる

                                mysqli_close( $link );

                        ?>
                        <!-- ↓↓共通部分↓↓ -->
                        
                    </div>
                    <!-- /メイン部分 -->
                </div>
            </div>
            <?php
	// left_pane.php の読み込み
	require_once("include/left_pane.php");
            ?>
        </div>
    </div>
</body>
</html>
