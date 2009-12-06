<?php
require_once('bootstrap.php');
require_once('My_Form_User.php');
require_once('My_Form_Renderer_Edit.php');
require_once('My_Form_Renderer_View.php');

// create form & validate
$Form = new My_Form_User();
if (isset($_POST) && count($_POST)>0) {
    $isValid = $Form->isValid($_POST);
}

// display page
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
    <head>
        <title>Zend_Form demo</title>
        <link rel="stylesheet" type="text/css" href="zend_form.css" />
    </head>
    <body>
        <div id="formSwitch" class="edit">
            <div class="tabs">
                <a class="edit" onclick="document.getElementById('formSwitch').className='edit';">edit</a>
                <a class="view" onclick="document.getElementById('formSwitch').className='view';">view</a>
            </div>
<?php
$RendererEdit = new My_Form_Renderer_Edit($Form, 'user_edit');
echo $RendererEdit->render();

$RendererView = new My_Form_Renderer_View($Form, 'user_view');
echo $RendererView->render();

?>

            <dl id="form_view">
                <dt>Username</dt>
                <dd>the name</dd>
                <dt>Email</dt>
                <dd>The Email address</dd>
            </dl>
        </div>
    </body>
</html>