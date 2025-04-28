<?php
define("ROOT",dirname(__DIR__));
function checkAccessActivation($datas)
{//validate access Restrictions for Businesses
    global $conn;
    global $spurl;
    if ($datas['usercate'] != 1) {
        $shopid = checkOwner($datas);
        $qy = $conn->prepare("SELECT * FROM bsm_shops WHERE shop_id=:shopid AND delete_status=:delstatus");
        $qy->execute(array("shopid" => $shopid, "delstatus" => 0));
        if ($qy->rowCount() > 0) {
            $r = $qy->fetchAll(PDO::FETCH_ASSOC);
            $feedTime = compareDate(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i")) + (3600 * 2)), $r[0]['shop_access_restriction_date']);
            if ($feedTime == 'previous' || $feedTime == 'equals') {
                //Order to Pay for the System
                echo json_encode(array("message" => "expired", "at" => $r[0]['shop_access_restriction_date'], "url" => "payment?cate=expired&at=" . encodeGetParams($r[0]['shop_access_restriction_date'])));
                exit;
            }
        }
    }//check if its concerned to business
}
function compareDate($a, $b)
{
    if ($a > $b) {
        $feed = "previous";//Now or Date A is Next to Date B
    } elseif ($a < $b) {
        $feed = "future";
    } else {
        $feed = "equals";
    }
    return $feed;
}

function safeInput($str)
{
    return stripslashes($str);
}
function encodeGetParams($getpars)
{
    $chars = null;
    $ngetpars = null;
    echo is_array($getpars);
    if (strlen($getpars) > 0) {
        for ($i = strlen($getpars) - 1; $i >= 0; $i--) {//reverse text string
            $ngetpars .= substr($getpars, $i, 1);
        }
    } else {
        $ngetpars = $getpars;
    }
    for ($i = 0; $i < strlen($ngetpars); $i++) {//encrypt reversed string
        if ($i % 2 == 0) {
            $chars .= base64_encode("%") . base64_encode(substr($ngetpars, $i, 1));
        } elseif ($i % 2 == 1) {
            $chars .= base64_encode("%") . base64_encode(base64_encode(substr($ngetpars, $i, 1)));
        }
    }
    return base64_encode($chars);
}

function decodeGetParams($getpars)
{
    $chars = null;
    $txt = null;
    $getp = explode(base64_encode("%"), base64_decode($getpars));

//decrypt reversed text string
    for ($i = 0; $i < count($getp); $i++) {
        if ($i % 2 == 1) {
            $chars .= base64_decode($getp[$i]);
        } elseif ($i % 2 == 0) {
            $chars .= base64_decode(base64_decode($getp[$i]));
        }
    }
//reorder text string
    for ($i = strlen($chars) - 1; $i >= 0; $i--) {
        $txt .= substr($chars, $i, 1);
    }
    return $txt;
}

function encryptPwd($getpars)
{
    $chars = null;
    $ngetpars = null;
    for ($i = strlen($getpars) - 1; $i >= 0; $i--) {//reverse text string
        $ngetpars .= substr($getpars, $i, 1);
    }
    for ($i = 0; $i < strlen($ngetpars); $i++) {//encrypt reversed string
        if ($i % 2 == 0) {
            $chars .= base64_encode("%") . sha1(substr($ngetpars, $i, 1));
        } elseif ($i % 2 == 1) {
            $chars .= base64_encode("%") . md5(base64_encode(substr($ngetpars, $i, 1)));
        }
    }

    return md5($chars);
}

function fixURLAttack($getParameters)
{//check possible value for the data
    $prepIfData = null;
    for ($i = 0; $i < count($getParameters); $i++) {
        if ($i == 0) {
            $prepIfData = $getParameters[$i];
        } else {
            $prepIfData = " || " . $getParameters[$i];
        }
    }
    if (!($prepIfData)) {
        header("location:" . $_SERVER['HTTP_REFERER']);
    }
}

function sessionManager($allowed, $redirect)
{
    global $spurl;
    $arr = explode("/", $_SERVER['REQUEST_URI']);
    if (!isset($_SESSION[$allowed])) {
        if (!isset($_COOKIE['shopmsuid'])) {
            header("location:" . $spurl . $redirect);
//echo "<script>console.log('No Session Exist')</script>";
        } else {
            $cookies = explode(",", $_COOKIE['shopmsuid']);
            $_SESSION['shopmsuid'] = $cookies[0];
            $_SESSION['usercate'] = $cookies[1];
            checkPriviledges($_SESSION['usercate']);
            echo "<script>console.log('Cookie Worked Exist')</script>";
        }
    } else {
//echo "<script>console.log('Session Exist')</script>";
        checkPriviledges($_SESSION['usercate']);
    }
}

function checkPriviledges($usercate)
{
    global $spurl;
    $redirect = "includes/logout";
    $arr = explode("/", $_SERVER['REQUEST_URI']);
    $accesspage = $arr[count($arr) - 1];
    $admin = array("adhome", "user", "categories", "modes", "structure", "payment");
    $businessowner = array("bohome", "agents", "retailers", "partners", "business", "payment", "products", "invoices", "stock?cate=in", "stock?cate=pay", "cashflows?cate=in", "cashflows?cate=out", "expenses", "incomes"
    , "stockorder", "stockorder?cate=stock", "stockorder?cate=pay", "history", "history?cate=order", "distributions", "notifications", "reports", "archive", "help");
    $business = array("bohome", "payment", "products", "invoices", "stock?cate=in", "notifications", "reports", "history", "archive", "help");
    switch ($usercate) {
        case 1:
            $feed = in_array($accesspage, $admin) ? 1 : 0;
            break;
        case 2:
            $feed = in_array($accesspage, $businessowner) ? 1 : 0;
            break;
        case 3:
            $feed = in_array($accesspage, $business) ? 1 : 0;
            break;
        default:
            $feed = 0;
    }
    if (!$feed) {
        header("location:" . $spurl . $redirect);
    }
}

function weekRange()
{
    $date = date("Y-m-d");
    $day = date("D");
    $days = array("Mon" => 1, "Tue" => 2, "Wed" => 3, "Thu" => 4, "Fri" => 5, "Sat" => 6, "Sun" => 7);
    $addDaysEndWeek = 7 - $days[$day];
    $startWeek = $days[$day] - 1;
    $startWeekDate = (strtotime($date) - (60 * 60 * 24 * $startWeek));
    $endWeekDate = (strtotime($date) + (60 * 60 * 24 * $addDaysEndWeek));
//$daydiff=floor((strtotime($deadline)-strtotime($frm))/(60*60*24));
    return array("start" => date("Y-m-d", $startWeekDate), "end" => date("Y-m-d", $endWeekDate));
}

function monthRange()
{
    return array("start" => date("Y-m") . "-01", "end" => date("Y-m") . "-31");
}

?>