<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();
$type = substr($_GET["type"], 5);
$groupid = $club_class_list[$type]["id"];
$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

$lawyer_list = array(
    "0" => array(
        "name"=>"л��Ȩ",
        "link"=>"XieLaw",
        "alt"=>"������������ע����ʦ��������������ѧ��ѧ��ʿ���й�������ѧѧʿ����ѧ˶ʿ�������������ѧ˾��ѧ˶ʿ��ԭ����ʥ·�״�ѧ��ѧԺ����ѧ�ߣ�ԭ�й�������ѧ��ʦ����ְ��ѧ��ʿ����ԭ������������������ʦ��������ʦЭ���Ա������������ʦЭ���Ա����������������ʦЭ���Ա��",
        "picname"=>"pic2.gif"
    ),

    "1" => array(
        "name"=>"������",
        "link"=>"hooyou",
        "alt"=>"������������ʦ��������ȫ������������ʦ������֮һ���ó�����ܳ������˲�(EB-1a)���ܳ����ں��о���Ա(EB-1b)�Լ����������������(NIW)�̿�,PERMְҵ����H-1B��ǩ֤���ѳɹ�������ǧ�ͻ�����̿���ӵ��25λ����ִ����ʦ���ڹ�ȡ�ŦԼ����ɼ��֥�Ӹ硢��˹�١���˹͡������ͼ�߸����е���ҵ���������а칫�ң�������Ϊ�ͻ��ṩ��ѵķ��񣬳ɹ��ʸߡ���ַ��www.hooyou.com; email��info@hooyou.com;tel��1-800-230-7040.",
        "picname"=>"hooyou.jpg"
    ),

    "2" => array(
        "name"=>"�ݲ���",
        "link"=>"sqilaw",
        "alt"=>"J.D., Western State University, College of Law, California M.A., Journalism, University of Southern California B. A., World Economy, Fudan University, Shanghai, China.",
        "picname"=>"pic3.gif"
    ),

    "3" => array(
        "name"=>"����",
        "link"=>"jingchenglaw",
        "alt"=>"����������ʦ��������1987������ڼ�����ɼ��С�Ϊ�����й��� ½��̨�塢��ۼ��������ݵĻ��˿ͻ��ṩȫ�������ҵ����񡣴��⣬���ǻ�Ϊ����ӡ�ȡ�����������˹���ձ������ô󡢷�����Ӣ����̩�����������Ǻ������޵������ҵ���ʿ����������񼰹���ǩ֤���롣",
        "picname"=>"pic4.gif"
    ),

    "4" => array(
        "name"=>"������",
        "link"=>"LiuLaw",
        "alt"=>"A graduate of Peking University (Ph.D.) and Valparaiso University School of Law (J.D.), Dr. Liu is a licensed member of the Illinois State Bar. He was also admitted to practice law in the U.S. District Courts for Southern District of Texas and Northern District of Illinois. His practice includes employment-based immigrant and non-immigrant petitions, and administrative and judiciary appeals. Prior to practicing law as a licensed attorney, Dr. Liu interned as law clerk in the Law Division of Cook County Circuit Court, Chicago, Illinois, and served on the editorial board of Valparaiso University Law Review, Valparaiso, Indiana.",
        "picname"=>"pic7.gif"
    ),

    "5" => array(
        "name"=>"���",
        "link"=>"SunLaw",
        "alt"=>"Alice H. Sun ��ʦ��ȫ��������ʦЭ���Ա(AILA)��������ʦЭ���Ա��1994����������ѧ��ʿ��1994��ԭ�й�����ѧԺ��ѧ˶ʿ����ѧ���о���Ա������ְҵ����ܳ��˲ţ����ڼ��о���Ա�������������, PERM�͹������Ϊ�ɹ���Ϊ����ȫ����У�о���Ա�Ͳ�ʿѧ����NIH, RAND�������о����о���Ա����̿�������ǩ֤����ʦ�����������ͻ��ṩѰ�Һ�Э�̸߿Ƽ�����Ͷ���Լ�ר����֪ʶ��Ȩȫ��λ�������ǵ���ּ�ǰ��������������㣬�������ɹ�����վ��Ϣ��www.sunlawfirm.us.",
        "picname"=>"pic11.jpg"
    ),

    "6" => array(
        "name"=>"�·�",
        "link"=>"ChenLaw",
        "alt"=>"�·�������ʦ������(Nguyen & Chen, LLP)רҵ����������¡��������ϡ����ۺ���ʦ�����������ó��ṩ���񷨡����¹�Ӷ�����Լ��͹�������������ʷ���
			��Ϊ������ʼ��֮һ,�·���ʦ�߱���ʵ�ķ�ѧ�����ͷḻ��ʵ�����顣�·���ʦ���ڵ¿���˹�ݹ���˹�������������߷�Ժʵϰ����������ȫ��֪����ʦ��������ְ���ꡣ
			�·���ʦ��ҵ����˹�ش�ѧ��ѧԺ���ǵ¿���˹��ִ����ʦ���������������Ժ��ͥ��ʦ������������ʦЭ���Ա��",
        "picname"=>"pic14.jpg"
    ),

    "7" => array(
        "name"=>"FYZ",
        "link"=>"fyzlaw",
        "alt"=>"FYZ��ʦ������(FYZ Law Group LLP)����������/�о�������˽Ӫ��ҵ�������ṩȫ��λ�����ɷ���������������ǵ���ʦӵ�ж������������ɷ����顣����ר���Ƽ�ְҵ��������ͷ�������ǩ֤���룬���磺EB-1A�� EB-1B�� EB-1C, NIW, PERM, H-1B, L-1 and O-1. �����ھɽ�ɽ������֥�Ӹ��ŦԼ���а칫�ң� ��Ϊ������Ŀ���ְҵ������ʦ������֮һ����ַ: www.fyzlaw.com  Email: info@fyzlaw.com  Tel: 650-312-8668(CA); 630-577-9060(IL); 646-288-7129(NY)",
        "picname"=>"pic16.jpg"
    ),

    "8" => array(
        "name"=>"Annie��",
        "link"=>"yanglaw",
        "alt"=>"Ms. Annie Yang �����ʦ������ʦ������ʦ������(Yang and Associates, LLP)�Ĵ�����֮һ����Ҫ��ʦ������ʦ���������񷨷������ŷǳ��ḻ�ľ��顣����ʮ���Ч�ʣ�����������ˮƽ��ȫ��Ͷ��ķ�����ÿͻ��㷺������
			������ּ���ṩ���Ի�������һ������ֵ������������ʦ��Immigration Attorneys You Can Trust!
			��Ҫ���������ͣ��ܳ��˲�(EB-1a)���ܳ����ں��о���Ա(EB-1b)�������������(NIW)���͹�֤ (PERM)EB-2/EB-3�������룻H-1B, L-1, O, TN�ȹ���ǩ֤����; Ͷ������(EB-5), J-1 Waiver, B����, ��ͥ����, I-485�Լ������й����������������ѯ��",
        "picname"=>"pic17.jpg"
    ),

    "9" => array(
        "name"=>"��������",
        "link"=>"WeGreened",
        "alt"=>"����������ʦ������(WeGreened.com)������TOP-10������У��ѧԺ���ɲ�ʿ(J.D.)��ɣ�����ר��������������̿�(NIW)����һ���Ƚܳ��˲��̿�(EB1A)���ͽܳ������о���Ա����(EB1B),��춶��ļ�Ʒ�ʼ���Ա���ʵĸ߱�׼Ҫ��, �ͱ���������Ը�����Ը���ռ������������֤��ϵͳ�����Ͽ�,ʹ�ñ���һ����500������ EB1/NIW �����ɹ�������ƽ�� EB1/NIW �����ɹ��ʸߴ�98.5%,ͬʱ��EB1/NIW���������ṩȫ��λ�ķ���(Letters/PL/RFE)�ͼ��߾�������Ʒ�ʱ�֤����(Approval or Refund Service),������ο����������ɹ��������׼֪ͨhttp://cn.wegreened.com/eb1_niw_approvals��ַ��cn.wegreened.com; ���������law@wegreened.com; �������ߣ�888.666.0969 ext.380(���ר��)",
        "picname"=>"WeGreened.png"
    )
);

function getMainPageLawyers($lawyer_list) {
    $ret = array();

    foreach ($lawyer_list as $each) {
        $ret[] = array(
            "name"=>$each["name"],
            "href"=>"",
            "img"=>'/yimin/images/'.$each["picname"]
        );
    }

    return $ret;
}

function getSpecialColumnArticle($link) {
    $xmlfile = BBS_HOME.'/xml/lawyer_1.xml';
    $result = read_xmlfile_content_web($xmlfile, 3);
    $ret = array();
    $i = 0;
    foreach ($result as $i=>$each) {
        if ($i == 20)
            break;

        $title = urldecode($each["title"]);
        $postTime = str_replace("/", "-", $each["date"]);
        $href = url_generate(4, array(
                "action"=>"/mobile/forum/i_group.php",
                "args"=>array("board"=>$each["board"], "groupid"=>$each["groupid"])
            ));
        $boardname = $each["board"];
        $ret[] = array(
            "title" => $title,
            "postTime" => $postTime,
            "href" => $href,
            "boardname" => $boardname
        );
    }

    return $ret;
}

// carouselfigure start
$str_img = '<div class="carouselfigure">';
$str_img .= '<div class="club_list_wrap"><div class="club_div">';
$str_img_dot = '';
$t_data = array();
$t_data = getMainPageLawyers($lawyer_list);
$each = array();
$in_list_flag = 0;
foreach ($t_data as $i=>$each) {
    if (($i & (4-1)) == 0) // ($i % 4)
        if ($in_list_flag) {
            $str_img_dot .= '<span></span>';
            $str_img .= '</li><li class="club_list_li">';
        } else {
            $str_img_dot .= '<div class="club_dot"><span class="act"></span>';
            $str_img .= '<ul><li class="club_list_li">';
        }

    $in_list_flag = 1;
    $str_img .= '<div class="club_item">';
    $str_img .= '<a href="'.$each["href"].'"><img class="club_img" src="'.$each["img"].'" alt="club_img" /></a>';
    $str_img .= '<p>'.$each["name"].'</p>';
    $str_img .= '</div>';
}

if ($in_list_flag) {
    $str_img .= '</li></ul>';
    $str_img_dot .= '</div>';
}
$str_img .= $str_img_dot;

$str_img .= '</div></div></div>';

// carouselfigure end

// detail start
$article_list = getSpecialColumnArticle($link);
if (count($article_list) == 0) {
    $article_list[0] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=138",
        "BoardsCnName" => "��������: ϰ��ƽ�����̷�������ͼ",
        "content" => "���ܸ����Ե�������ˣ�������������£�������ҪҲ��Ӧ��ΪΨһ�ģ������䲻��Ҫ�κ� WHERE �Ӿ䣬��Ϊ����ϣ���������е��С�",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "9988",
        "reply_num" => "998",
        "author" => "С���޵�",
        "postTime" => "09-18"
    );

    $article_list[1] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=183",
        "BoardsCnName" => "��������: ����89���Ӿ�ɱ���򣬹��ݺ��ͳ�2-1��ת���ݸ���",
        "content" => "2015��9��21��19:35�г���26�����һ������,���ݸ�������Խ��ɽ������ӭս���õĹ��ݺ�󣬺�����ȶ�һ������������ɸ����ء������Ľ�����תȡʤ����������...",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "10086",
        "reply_num" => "10010",
        "author" => "���ű༭��",
        "postTime" => "09-22"
    );
}

$str_article = '<ul class="hot_recommend">';
$each = array();
foreach ($article_list as $each) {
    $str_article .= '<li class="hot_li hot_list_wrap im_conter_box">';
    $str_article .= '<div class="content_list nopic padding10 ">';
    $str_article .= '<h4><a href="'.$each["href"].'">'.$each["title"].'</a></h4>';
    $str_article .= '<p class="commen_p padding-bottom border_bottom">';
    $str_article .= '<span class="commen_margin im_l">'.$each["boardname"].'</span>';
    $str_article .= '<span class="commen_right ">'.$each["postTime"].'</span>';
    $str_article .= '</p></div></li>';
    $str_article .= '<hr />';
}
$str_article .= '</ul>';

//detail end

$str_img = mb_convert_encoding($str_img, "UTF-8", "GBK");
$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["carouselfigure"] = $str_img;
$all_arr["detail"] = $str_article;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_article));
mysql_close($link);
?>
