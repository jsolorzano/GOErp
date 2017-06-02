<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
} else {
header("location: login");
}
?>
<div id="footer" style="text-align:center; margin-left: 20%">
    <img style="width: 100%;" src="<?= base_url() ?>/static/img/footer.png"/>
</div>
    </body>
</html>