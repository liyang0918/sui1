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
        "name"=>"谢正权",
        "link"=>"XieLaw",
        "alt"=>"美国乔治亚州注册律师；乔治亚州立大学法学博士；中国政法大学学士、法学硕士；美国密苏里大学司法学硕士；原美国圣路易大学法学院访问学者；原中国政法大学教师、在职法学博士生；原美国乔治亚州政府律师；美国律师协会会员；美国移民律师协会会员；美国乔治亚州律师协会会员。",
        "picname"=>"pic2.gif"
    ),

    "1" => array(
        "name"=>"张哲瑞",
        "link"=>"hooyou",
        "alt"=>"张哲瑞联合律师事务所是全美最大的移民律师事务所之一，擅长办理杰出技能人才(EB-1a)，杰出教授和研究人员(EB-1b)以及国家利益豁免移民(NIW)绿卡,PERM职业移民及H-1B等签证，已成功帮助数千客户获得绿卡。拥有25位美国执照律师，在硅谷、纽约、洛杉矶、芝加哥、休斯顿、奥斯汀和西雅图七个城市的商业中心区设有办公室，致力于为客户提供最佳的服务，成功率高。网址：www.hooyou.com; email：info@hooyou.com;tel：1-800-230-7040.",
        "picname"=>"hooyou.jpg"
    ),

    "2" => array(
        "name"=>"戚博雄",
        "link"=>"sqilaw",
        "alt"=>"J.D., Western State University, College of Law, California M.A., Journalism, University of Southern California B. A., World Economy, Fudan University, Shanghai, China.",
        "picname"=>"pic3.gif"
    ),

    "3" => array(
        "name"=>"精诚",
        "link"=>"jingchenglaw",
        "alt"=>"精诚联合律师事务所于1987年成立于加州洛杉矶市。为来自中国大 陆、台湾、香港及美国各州的华人客户提供全面的移民业务服务。此外，我们还为来自印度、韩国、俄罗斯、日本、加拿大、法国、英国、泰国、马来西亚和中美洲地区国家的人士办理各项移民及工作签证申请。",
        "picname"=>"pic4.gif"
    ),

    "4" => array(
        "name"=>"刘宗坤",
        "link"=>"LiuLaw",
        "alt"=>"A graduate of Peking University (Ph.D.) and Valparaiso University School of Law (J.D.), Dr. Liu is a licensed member of the Illinois State Bar. He was also admitted to practice law in the U.S. District Courts for Southern District of Texas and Northern District of Illinois. His practice includes employment-based immigrant and non-immigrant petitions, and administrative and judiciary appeals. Prior to practicing law as a licensed attorney, Dr. Liu interned as law clerk in the Law Division of Cook County Circuit Court, Chicago, Illinois, and served on the editorial board of Valparaiso University Law Review, Valparaiso, Indiana.",
        "picname"=>"pic7.gif"
    ),

    "5" => array(
        "name"=>"孙虹",
        "link"=>"SunLaw",
        "alt"=>"Alice H. Sun 律师，全美移民律师协会会员(AILA)，加州律师协会会员（1994）。美国法学博士（1994）原中国社会科学院法学硕士，法学所研究人员。办理职业移民杰出人才，教授及研究人员，国家利益豁免, PERM劳工审查尤为成功。为美国全国名校研究人员和博士学生及NIH, RAND等著名研究所研究人员申办绿卡及工作签证。律师事务所向本所客户提供寻找和协商高科技风险投资以及专利和知识产权全方位服务。我们的宗旨是帮助您在美国立足，在美国成功。网站信息：www.sunlawfirm.us.",
        "picname"=>"pic11.jpg"
    ),

    "6" => array(
        "name"=>"陈帆",
        "link"=>"ChenLaw",
        "alt"=>"陈帆联合律师事务所(Nguyen & Chen, LLP)专业受理各类民事、刑事诉讼。该综合律师事务所尤其擅长提供移民法、人事雇佣法、以及劳工法等领域的优质服务。
			作为该所创始人之一,陈帆律师具备扎实的法学基础和丰富的实践经验。陈帆律师曾在德克萨斯州哈里斯郡政府、州上诉法院实习工作，并在全美知名律师事务所供职多年。
			陈帆律师毕业于休斯敦大学法学院，是德克萨斯州执照律师、美国联邦地区法院出庭律师、美国移民律师协会会员。",
        "picname"=>"pic14.jpg"
    ),

    "7" => array(
        "name"=>"FYZ",
        "link"=>"fyzlaw",
        "alt"=>"FYZ律师事务所(FYZ Law Group LLP)是向各大教育/研究机构，私营企业及个人提供全方位移民法律服务的事务所。我们的律师拥有多年美国移民法律服务经验。我们专精科技职业移民申请和非移民工作签证申请，诸如：EB-1A， EB-1B， EB-1C, NIW, PERM, H-1B, L-1 and O-1. 我们在旧金山湾区，芝加哥和纽约设有办公室， 是为数不多的跨美职业移民律师事务所之一。网址: www.fyzlaw.com  Email: info@fyzlaw.com  Tel: 650-312-8668(CA); 630-577-9060(IL); 646-288-7129(NY)",
        "picname"=>"pic16.jpg"
    ),

    "8" => array(
        "name"=>"Annie杨",
        "link"=>"yanglaw",
        "alt"=>"Ms. Annie Yang 杨静宜律师是杨律师联合律师事务所(Yang and Associates, LLP)的创办人之一和主要律师。杨律师在美国移民法方面有着非常丰富的经验。她上十年高效率，高质量，高水平和全心投入的服务深得客户广泛好评。
			服务宗旨是提供个性化服务，做一个可以值得您信赖的律师。Immigration Attorneys You Can Trust!
			主要代理案件类型：杰出人才(EB-1a)，杰出教授和研究人员(EB-1b)，国家利益豁免(NIW)，劳工证 (PERM)EB-2/EB-3移民申请；H-1B, L-1, O, TN等工作签证申请; 投资移民(EB-5), J-1 Waiver, B延期, 家庭移民, I-485以及各种有关移民的疑难问题咨询。",
        "picname"=>"pic17.jpg"
    ),

    "9" => array(
        "name"=>"北美联合",
        "link"=>"WeGreened",
        "alt"=>"北美联合律师事务所(WeGreened.com)由美国TOP-10顶尖名校法学院法律博士(J.D.)组成，本所专精国家利益豁免绿卡(NIW)，第一优先杰出人才绿卡(EB1A)，和杰出教授研究人员移民(EB1B),由於对文件品质及人员素质的高标准要求, 和本事务所针对各种请愿案收集大量有助於论证的系统化资料库,使得本所一年有500个以上 EB1/NIW 大量成功案例，平均 EB1/NIW 案件成功率高达98.5%,同时在EB1/NIW申请领域提供全方位的服务(Letters/PL/RFE)和极具竞争力的品质保证方案(Approval or Refund Service),详情请参考我所大量成功经验和批准通知http://cn.wegreened.com/eb1_niw_approvals网址：cn.wegreened.com; 免费评估：law@wegreened.com; 中文热线：888.666.0969 ext.380(免费专线)",
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
        "BoardsCnName" => "测试用例: 习近平今启程访问西雅图",
        "content" => "尽管该属性的名称如此，但在这种情况下，它不需要也不应该为唯一的，这个语句不需要任何 WHERE 子句，因为我们希望检索所有的行。",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "9988",
        "reply_num" => "998",
        "author" => "小白无敌",
        "postTime" => "09-18"
    );

    $article_list[1] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=183",
        "BoardsCnName" => "测试用例: 邹正89分钟绝杀进球，广州恒大客场2-1逆转广州富力",
        "content" => "2015年9月21日19:35中超第26轮最后一场比赛,广州富力坐镇越秀山体育场迎战来访的广州恒大，恒大在先丢一球情况下最终由高拉特、邹正的进球逆转取胜，富力方面...",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "10086",
        "reply_num" => "10010",
        "author" => "新闻编辑部",
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
