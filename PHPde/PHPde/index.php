<?php
session_start();


$db_host = 'localhost';

$db_name = 'ec';

$db_user = 'root';

$db_pass = 'tyoriu1125';



// データベースへ接続する

$link = mysqli_connect( $db_host, $db_user, $db_pass, $db_name );

if ( $link !== false ) {


 //画面左側の「ログイン」ボタンが押された時
    if( $_REQUEST["cmd"] == "do_login" )
    {
    $query = "SELECT * FROM m_customers "

                   . " WHERE customer_code = "

                   . "'" . mysqli_real_escape_string( $link, $_REQUEST["login_id"] ) ."', "

                   . "AND pass= "

                   . "'" . mysqli_real_escape_string( $link, $_REQUEST["login_pass"] ) . "'";

        $is_login = 0;
        $res    = mysqli_query( $link,$query );

        while( $row = mysqli_fetch_assoc( $res ) ) 
        {
            $_SESSION["customer_code"] = $_REQUEST["login_id"];
            $_SESSION["name"] = $row["name"];
            $is_login = 1;
        }
        $res->free();
    }

//ログイン後に、画面左側の「ログアウト」ボタンが押された時
    if( $_REQUEST["cmd"] == "do_logout" )
    {
        $_SESSION = array();
        if ( isset( $_COOKIE[ session_name( ) ] ) )
            {
            setcookie( session_name(), "", time( ) - 42000, "/");
            }
        session_destroy();
     }


?>



<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>商品一覧</title>
        <link href="common/css/base.css" rel="stylesheet" type="text/css" />
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
                        <h2>商品一覧</h2>
                    <!-- 商品リスト -->
            <div class="list clearfix">
                <?php

    $query = "SELECT * FROM m_items WHERE del_flag = '0' ";

if( $_REQUEST["item_name"] != "" )
{
    // 正しくは「%」「_」もエスケープする必要があります。
    $query = $query . " AND item_name LIKE '%". "'" . mysqli_real_escape_string( $link,$_REQUEST["item_name"] ) . "%' ";
}

// もしも「管楽器」「弦楽器」「打楽器」のいずれかのチェックボックスに
// チェックが入っていた場合、以下の if 文に入ります。
if( $_REQUEST["cat_kan"] == "1" ||
    $_REQUEST["cat_gen"] == "1" ||
    $_REQUEST["cat_da"] == "1" )
{
    $in = "";
    if( $_REQUEST["cat_kan"] == "1" )
    {
        $in = $in . "1,";
    }
    if( $_REQUEST["cat_gen"] == "1" )
    {
        $in = $in . "2,";
    }
    if( $_REQUEST["cat_da"] == "1" )
    {
        $in = $in . "3,";
    }
    $in = preg_replace( "/,$/", "", $in );
    $query = $query . " AND category IN ( $in ) ";
}
$res    = mysqli_query( $link,$query );
$data = array();

    while( $row = mysqli_fetch_assoc( $res ) )
    {
        array_push( $data, $row);
    }
    foreach( $data as $key => $val ){

                ?>
              <dl class="products">
                <dt><a href="item_detail.php?code=<?php print( htmlspecialchars( $val["item_code"] ) ); ?>"><img src="img/thumb/<?php print( htmlspecialchars( $val["image"], ENT_QUOTES ) ); ?>" alt="" /><br />
                <?php print( htmlspecialchars( $val["item_name"], ENT_QUOTES ) ); ?></a></dt>
                <dd>&yen;<?php print( htmlspecialchars( $val["price"], ENT_QUOTES ) ); ?></dd>
              </dl>

<?php
    }
$res->free();
} else {

    echo "データベースの接続に失敗しました";

}

// データベースへの接続を閉じる

mysqli_close( $link ); 
?>
            </div>
            <!-- /商品リスト -->
          </div>
          <!-- /メイン部分 各ページごとに作成-->

          <!-- ↓↓共通部分↓↓ -->
          <!-- フッター -->
          <div id="footer">
            <p class="copy">Copyright &copy; 2008 oh yeah !! All Rights Reserved.</p>
          </div>
          <!-- /フッター -->
        </div>
        <!-- /メイン部分 -->
      </div>
    </div>
    <!-- 右コンテンツ -->
                <?php
//left_pane.php の読み込み
require_once("include/left_pane.php");
                ?>
  </div>
</div>
</body>
</html>