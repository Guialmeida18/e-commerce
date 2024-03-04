<?php
session_start();

// Destroi todas as variáveis de sessão
$_SESSION = array();
 session_destroy();
// Redireciona o usuário de volta para a página de login
header("Location: login.php");
exit;