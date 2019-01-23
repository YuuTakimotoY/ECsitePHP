<?php
    session_start();

    $db_host = 'localhost';

    $db_name = 'ec';

    $db_user = 'root';

    $db_pass = 'tyoriu1125';



    // �f�[�^�x�[�X�֐ڑ�����

    $link = mysqli_connect( $db_host, $db_user, $db_pass );
    mysqli_select_db($link, $db_name);

    mysqli_query($link,'SET NAMES utf8'  );


    if ( $link !== false ) {


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>商品詳細リスト</title>
    <link href="common/css/base.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="wrap">
        <div id="contents">
            <!-- �E�R���e���c -->
            <div id="rightbox">
                <div id="main">
                    <div id="main2">
                        <!-- �����^�C�g���ȊO���ʕ������� -->
                        <!-- �����^�C�g���ȊO���ʕ������� -->
                        <?php

	                     // �ꗗ��ʂ����ʑJ�ڂł���悤��
	                    $query = "select * from m_items where item_code =  "
                                . mysqli_real_escape_string( $link, $_REQUEST["code"] );
                        $res = mysqli_query( $link,$query );

	                    $data = array();

                        while( $row = mysqli_fetch_assoc( $res ) )
                        {
                        array_push( $data, $row);
                        }
                        foreach( $data as $key => $val ){
                        ?>
                        <form name="detail_form" action="cart.php" method="get">
                        <input type="hidden" name="cmd" value="add_cart"/>
                        <input type="hidden" name="code" value="<?php print( htmlspecialchars( $val["item_code"] ) ); ?>"/>
                        <!-- ���C������ �e�y�[�W���Ƃɍ쐬-->
                        <div id="mainbox" class="clearfix">
                            <h2>商品詳細リスト</h2>
                            <div class="list clearfix">
                               <h3><?php print( htmlspecialchars( $val["item_name"] ) ); ?></h3>
                               <p class="photo"><img src="img/<?php print( htmlspecialchars( $val["image"] ) ); ?>" width="400" height="400"/></p>
                                <p class="text"><?php print( htmlspecialchars( $val["detail"] ) ); ?></p>
                               <div class="buy">
                                <p class="price">価格：<strong>&yen;<?php print( htmlspecialchars( $val["price"] ) ); ?></strong></p>
                                個数：
                                <select name="num">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                </select>
                                <input type="submit" value="カートにいれる"/>
                                <input type="button" value="前の画面へ戻る" onclick="history.back()"/>
                               </div>
                            </div>
                        </div>
                        </form>
                        <!-- /���C������ �e�y�[�W���Ƃɍ쐬-->
                        <?php
	                    }
	                    
                        } else {

                            echo "データベースの接続に失敗しました";

                        }

                        // �f�[�^�x�[�X�ւ̐ڑ������

                        mysqli_close( $link ); 

                        ?>

                        <!-- �������ʕ������� -->
                        <!-- �t�b�^�[ -->
                        <div id="footer">
                            <p class="copy">Copyright &copy; 2008 oh yeah !! All Rights Reserved.</p>
                        </div>
                        <!-- /�t�b�^�[ -->
                    </div>
                    <!-- /���C������ -->
                </div>
            </div>
            <!-- �E�R���e���c -->
            <?php
                //left_pane.php �̓ǂݍ���
                require_once("include/left_pane.php");
            ?>
        </div>
    </div>
</body>
</html>