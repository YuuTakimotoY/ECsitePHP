<?php
session_start( );


$db_host = 'localhost';

$db_name = 'ec';

$db_user = 'root';

$db_pass = 'tyoriu1125';

// �f�[�^�x�[�X�֐ڑ�����

$link = mysqli_connect( $db_host, $db_user, $db_pass );
mysqli_select_db($link, $db_name);

mysqli_query($link,'SET NAMES utf8'  );

if ( $link !== false ) {
    // if ���̒��� !isset �ƋL�q���鎖�ŁA�ϐ������݂��Ȃ��ꍇ�� if ���ɓ���B
    if( !isset( $_SESSION["cart"] ) )
    {
        // array() ���߂ŁA��̔z����쐬����B
        $_SESSION["cart"] = array();
    }


    // ���N�G�X�g cmd �̒��g���A�uadd_cart�v�ł������ꍇ�̏����B
    // �ڍ׉�ʂŁu�J�[�g�ɂ����v�{�^���������ꂽ���ɏ������s���B

    if( $_REQUEST["cmd"] == "add_cart")
    {
        $is_already_exists  = 0;
        for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ )
        {
            if( $_SESSION["cart"][$i]["item_code"] == $_REQUEST["code"] )
            {
                // �ǉ����鏤�i���J�[�g�Ɋ��ɑ��݂���Ȃ�΁A���ʂ����Z�B
                $_SESSION["cart"][$i]["num"] = $_SESSION["cart"][$i]["num"] + $_REQUEST["num"];
                $is_already_exists = 1;
            }
        }
        // �ǉ����鏤�i���J�[�g�ɑ��݂��Ȃ��ꍇ�A�J�[�g�ɐV�K�o�^�B
        if( $is_already_exists == 0 )
        {
            $query = "select * from m_items where item_code like '".$_REQUEST["code"]."'";
            $res    = mysqli_query( $link,$query );
            if( $row = mysqli_fetch_assoc( $res ) )
            {
                $item["item_code"] = $_REQUEST["code"];
                $item["num"] = $_REQUEST["num"];
                $item["image"] = $row["image"];
                $item["item_name"] = $row["item_name"];
                $item["price"] = $row["price"];
                array_push( $_SESSION["cart"], $item );
            }
        }
    }


    //���N�G�X�g cmd �̒��g���A�udel�v�ł������ꍇ�̏����B
    //�J�[�g��ʂŁu�폜�v�{�^���������ꂽ���ɏ������s���B
    if( $_REQUEST["cmd"] == "del")
    {
        for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ )
        {
            if( $_SESSION["cart"][$i]["item_code"] == $_REQUEST["code"] )
            {
                // unset ���߂́A�ϐ���j������B
                unset( $_SESSION["cart"][$i] );
            }
        }
        // �폜����Ɣz��̔ԍ����������ɂȂ邽�߁A�ȉ��̏����Ŕz��̔ԍ��𐮗��������B
        $_SESSION["cart"] = array_merge($_SESSION["cart"]);
    }

    //���N�G�X�g cmd �̒��g���A�ucommit_order�v�ł������ꍇ�̏����B
    //�J�[�g��ʂŁu�����m��v�{�^���������ꂽ���ɏ������s���B
    //�����m��{�^���̓��O�C���ς̎��̂݁A�\�������B

    if( $_REQUEST["cmd"] == "commit_order" )
    {
        // �J�[�g���̍��v���z���v�Z����B
        foreach( $_SESSION["cart"] as $cart )
        {
            $total_price += $cart["price"] * $cart["num"];
        }

        // d_purchase �e�[�u���ւ̑}��
        $query = " insert into d_purchase( customer_code, purchase_date, total_price) ";
        $query.= " values( '".$_SESSION["customer_code"]."' , now() , ".$total_price." ) ;";
        $res = mysqli_query( $link,$query );

        // d_purchase �e�[�u���ɑ}������ ID ���擾�B
        $order_id = mysqli_insert_id($link);

        // $_SESSION["cart"] ��������x���[�v���A���[�v���ŏڍ׏����擾����
        // ���� d_purchse_detail �� insert ����B
        foreach( $_SESSION["cart"] as $cart )
        {
            $query = " insert into d_purchase_detail( order_id, item_code, price, num ) ";
            $query.= " values( ".$order_id.", '".$cart["item_code"]."' , ".$cart["price"].", ".$cart["num"]." ) " ;
            $res = mysqli_query( $link,$query );
        }
        unset( $_SESSION["cart"] );
        // $is_order_done �ϐ��́A��ʏ�Ɂu�������������܂����v
        // ���b�Z�[�W��\�����邽�߂Ɏg�p����B
        $is_order_done = 1;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>�J�[�g</title>
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

                        <!-- ���C������ �e�y�[�W���Ƃɍ쐬-->
                        <div id="mainbox" class="clearfix">
                            <h2>�J�[�g</h2>
                            <?php
    if( $is_order_done == 1 )
    {
                            ?>
                            <br />
                            �������������܂����B
                            <a href="index.php">���i�ꗗ�֖߂�</a>
                            <br />
                            <?php
    }
                            ?>
                            <div class="list clearfix">
                                <table class="cartlist" cellpadding="0" cellspacing="0">
                                    <?php
    // $_SESSION["cart"] �����[�v���A�J�[�g�̏��i��\������B
    if( isset ( $_SESSION["cart"] ) )
    {
        foreach( $_SESSION["cart"] as $cart )
        {
                                    ?>
                                    <tr>
                                        <td class="tc1">
                                            <img src="img/thumb2/<?php print( $cart["image"] ); ?>" />
                                        </td>
                                        <td class="tc2">
                                            <?php print( $cart["item_name"] ); ?>(<?php print( $cart["num"] ); ?>��)
                                        </td>
                                        <td class="tc3">
                                            &yen;<?php print( $cart["price"] ); ?>
                                        </td>
                                        <td class="tc4">
                                            <a href="itemdetail.php?code=<?php print( $cart["item_code"] ); ?>">�ڍ�</a>
                                        </td>
                                        <td class="tc5">
                                            <a href="cart.php?cmd=del&code=<?php print( $cart["item_code"] ); ?>">�폜</a>
                                        </td>
                                    </tr>
                                    <?php
        }
    }
                                    ?>
                                </table>
                                <br />
                                <?php
    if( $_SESSION["customer_code"] != "" && count( $_SESSION["cart"] ) > 0 )
    {
                                ?>
                                <form name="cart_form" action="cart.php" method="post">
                                    <input type="hidden" name="cmd" value="commit_order" />
                                    <input type="submit" class="fix" value="�����m��" />
                                </form>
                                <?php
    }

} else {

    echo "�f�[�^�x�[�X�̐ڑ��Ɏ��s���܂���";

}

// �f�[�^�x�[�X�ւ̐ڑ������

mysqli_close( $link );

                                ?>
                            </div>
                        </div>
                        <!-- /���C������ �e�y�[�W���Ƃɍ쐬-->

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
            require_once("include/left_pane.php");
            ?>
        </div>
    </div>
</body>
</html>

