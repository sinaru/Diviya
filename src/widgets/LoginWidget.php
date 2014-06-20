<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginWidget
 *
 * @author sinaru
 */
class LoginWidget extends Widget {

    private $form;

    public function __construct() {
        $this->form = new LoginForm();
        parent::__construct();
    }

    private function renderForm() {
        if (!Diviya::App()->user->isLoggedIn()) {
?>       
            <form onsubmit="LoginWidget(); return false;" action="" method="post">
                <fieldset>
                    <legend>Login Form</legend>
        <?php echo $this->form->label('username'); ?>
        <?php echo $this->form->field('username'); ?>

        <?php echo $this->form->label('password'); ?>
        <?php echo $this->form->field('password'); ?>
    
        <?php echo $this->form->submit('button'); ?>
        <?php echo $this->form->errors(); ?>
            <div id="LoginWidget-result"></div>
        </fieldset>
    </form>
<?php
        } else {
?>
            <div id="LoginWidget">
                <form onsubmit="LoginWidgetLogout(); return false;" action="" method="post">
                    You are logged in as <?php
            echo $app->user->getUsername();
            if ($app->user->isAdmin())
                echo '*';
?>
            <input type="submit" value="Logout"/>
        </form>
        <div id="LoginWidget-result"></div>
    </div>
<?php
        }
    }

    function render() {
        if (isset($_POST['LoginForm'])) {
            $this->form->attributes = $_POST['LoginForm'];
            $this->form->authenticate();
        }

        $this->renderForm();
        parent::render();
    }

}
?>
