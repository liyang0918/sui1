<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");

$mailto = $_GET["mailto"];
$title = urldecode($_GET["title"]);
$mail_type = 0; // �ʼ�����: 0��ͨ�ʼ� 1д����ʦ����ѯ�ʼ�
$mail_type = intval($_GET["type"]);
if ($mail_type < 0 or $mail_type > 1)
    $mail_type = 0;

$mailto_name = $mailto;
if ($mail_type == 1) {
    // ��д��ѯ�ʼ�ʱ,�ռ���дΪ��ʦ��
    $link = db_connect_web();
    $sql = "SELECT lawyer_name FROM lawyer WHERE creator='$mailto'";
    $result = mysql_query($sql, $link);
    if ($row = mysql_fetch_array($result)) {
        $mailto_name = $row["lawyer_name"]."��ʦ";
    }
}

$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];

?>
    <div class="ds_box border_bottom">
        <a onclick="return go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        д�ʼ�
    </div><!--<End ds_box-->
    <div class="write_box">
        <p class="write_email_name">�ռ��ˣ�</p>
        <?php if ($mail_type == 1) {?>
        <p class="write_email_content"><?php echo $mailto_name; ?></p>
        <input id="mailto" class="write_input" type="hidden" value="<?php echo $mailto; ?>" onfocus="set_tishi(1,this)" onBlur="set_tishi(2,this)" />
        <?php } else { ?>
        <input id="mailto" class="write_input" type="text" value="<?php echo $mailto; ?>" onfocus="set_tishi(1,this)" onBlur="set_tishi(2,this)" />
        <?php } ?>
        <p class="write_email_name">���⣺</p>
        <input id="title" class="write_input" type="text" value="<?php echo $title; ?>" />
        <p class="write_email_name">���ģ�</p>
        <textarea id="mail_content"></textarea>
        <input class="email_submit" type="submit" value="����" onclick="return sendmail();"/>
    </div>
    <script type="text/javascript">
        function set_tishi(action, obj) {
            if(action == 1 && obj.value == "���10���ռ���,�ռ���֮���Էֺż��") {
                obj.value='';
                obj.style.color='#000'
            }

            if(action == 2 && !obj.value) {
                obj.value="���10���ռ���,�ռ���֮���Էֺż��";
                obj.style.color='#999'
            }

            return false;
        }

        function sendmail() {
            var curr_url = "<?php echo $curr_url; ?>";
            var currentuser = "<?php echo $currentuser["userid"]; ?>";
            var mail_type = "<?php echo $mail_type; ?>";
            if (currentuser == "guest") {
                document.cookie = "before_login="+curr_url;
                window.location = "login.php";
                return false;
            }

            // ��ȡ������
            var obj = document.getElementById("mailto");
            var mailto = obj.value;
            if (mailto == null || mailto.length < 1) {
                Alert("����д�ռ���", 1);
                return false;
            }

            // ��ȡ����
            obj = document.getElementById("title");
            var title = obj.value;
            if (title == null || title.length < 1) {
                Alert("�ʼ����ⲻ��Ϊ��!", 1);
                return false;
            }
            if (mail_type == "1") {
                // ��ѯ�����ʼ��ı���ǰ����� "��ѯ��"
                title = "��ѯ��"+title;
            } else {
                // ��ͨ�ʼ�������ѯ��ͷ ��ð�Ÿ�ΪӢ��
                title = title.replace(/^��ѯ��(.*)/, "��ѯ:$1");
            }

            // ��ȡ����
            obj = document.getElementById("mail_content");
            var content = obj.value;
            if (content == null || content.length < 1) {
                Alert("�ʼ����ݲ���Ϊ��!", 1);
                return false;
            }

            post_email(mailto, title, content);
            go_last_page();
            return false;
        }
    </script>
<?php
include_once("foot.php");
?>