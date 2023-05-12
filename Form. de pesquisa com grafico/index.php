<?php

require('dados.php');

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if (!$conn) {
  die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Armazena os dados do formulário nas variáveis
  $nome = $_POST["nome"];
  $email = $_POST["email"];
  $servico = $_POST["servico"];
  
// Verifica se o recaptcha foi marcado
	$recaptcha = $_POST['g-recaptcha-response'];
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => $chave_secreta,
		'response' => $recaptcha
);

$options = array(
    'http' => array (
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$valid = json_decode($result)->success;

if ($valid) {
	// Verifica se o e-mail já respondeu à pesquisa
	$sql_check_email = "SELECT COUNT(*) FROM clientes WHERE email = '$email'";
	$result_check_email = mysqli_query($conn, $sql_check_email);
	$count_check_email = mysqli_fetch_array($result_check_email)[0];

  // Insere os dados no banco de dados se o endereço de IP e o e-mail ainda não foram usados
  if ($count_check_ip == 0 && $count_check_email == 0) {
    $sql = "INSERT INTO clientes (nome, email, servico) VALUES ('$nome', '$email', '$servico')";

    if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Dados da pesquisa armazenados com sucesso, obrigado por participar!')</script>";
    } else {
      echo "Erro ao armazenar os dados da pesquisa: " . mysqli_error($conn);
    }
  } else {
    echo "<script>alert('Esse endereço de e-mail já foi usado para responder a pesquisa.')</script>";
  }
 } else {
    //echo "Por favor, verifique o reCAPTCHA";
	echo "<script>alert('Por favor, verifique o reCAPTCHA.')</script>";
	}
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pesquisa de Serviços</title>
			<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR' async defer></script>
	<script>
		function validarFormulario() {
		var recaptcha = grecaptcha.getResponse();
		if (recaptcha.length == 0) {
			alert("Por favor, marque o reCaptcha.");
			return false;
		} else {
    return true;
  }
}
</script>
</head>
<body>
	<img src="../images/logo.png" alt="Logo" class="logo">
	<h1>Pesquisa de AlagoinhasTelecom</h1>
	<p>Por favor, responda algumas perguntas sobre os serviços que você gostaria de ver adicionados à nossa empresa.</p>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label for="nome">Nome:</label>
		<input type="text" name="nome" id="nome" required>
		<label for="email">E-mail:</label>
		<input type="email" name="email" id="email" required
		
		<div class="question-group">
			<label for="servico">Qual serviço você gostaria de ver adicionado à nossa empresa?</label><br>
			<input type="radio" name="servico" value="Telefone fixo">Telefone fixo<br>
			<input type="radio" name="servico" value="App de Livros">App de Livros<br>
			<input type="radio" name="servico" value="App de Filmes">App de Filmes<br>
			<input type="radio" name="servico" value="App de Música">App de Música<br>
			<input type="radio" name="servico" value="App de Consulta Médica">App de Consulta Médica<br>
		
			<div class="g-recaptcha" data-sitekey="<?php echo $chave_de_site; ?>"></div><br>
			<input type="submit" value="Enviar">
			
		</div>
	</form>
</body>

</html>

