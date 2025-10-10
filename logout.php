<?php

session_start();

//limpa as variáveis da sessão.
session_unset();

//destroi a sessão.
session_destroy();

//envia o usuário pra tela de login
header('Location: login.html');
exit();
?>