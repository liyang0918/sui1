<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");

$mailto = $_GET["mailto"];
$title = urldecode($_GET["title"]);

$curr_url = $_SERVER["REQUEST_URI"];

?>
    <div class="ds_box border_bottom">
        <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        д�ʼ�
    </div><!--<End ds_box-->
    <div class="write_box">
        <p class="write_email_name">�ռ��ˣ�</p>
        <input id="mailto" class="write_input" type="text" value="<?php echo $mailto; ?>" onfocus="set_tishi(1,this)" onBlur="set_tishi(2,this)" />
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