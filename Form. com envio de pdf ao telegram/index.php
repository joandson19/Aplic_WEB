<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
  <title>Formulário AlagoinhasTelecom</title>
	<link rel="stylesheet" href="style.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    // Função para lidar com o envio do formulário
    function handleSubmit(event) {
      event.preventDefault();

      // Verifique o reCAPTCHA
      grecaptcha.execute('<?php	require_once('dados.php');	echo $chave_site	?>', {action: 'enviar_formulario'})
        .then(function(token) {
          // Adicione o token reCAPTCHA ao formulário
          document.getElementById('recaptcha-response').value = token;

          // Envie o formulário
          document.getElementById('formulario').submit();
        });
    }
  </script>
</head>
<body>
	<img src="../../images/logo.png" alt="Logo" class="logo">
  <div class="container">
    <h2>Formulário AlagoinhasTelecom</h2>

    <form action="enviar_dados.php" method="POST">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="tel">Telefone:</label>
        <input type="tel" id="tel" name="tel" required>
        <label for="Endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>		
<!-- Campo oculto para armazenar o token do reCAPTCHA -->
      
      </div>
	<input type="hidden" id="recaptcha-response" name="g-recaptcha-response">  
<?php
    // Verificar se há uma mensagem de erro no parâmetro da URL
    if (isset($_GET['erro']) && $_GET['erro'] === 'recaptcha') {
        echo '<p style="color: red;">Por favor, marque o reCAPTCHA antes de enviar o formulário.</p>';
    } 
	if (isset($_GET['r']) && $_GET['r'] === 'sucesso') {
        echo '<p style="color: green;">Formulário Enviado!</p>';
	}
?>
      <!-- Campo oculto para armazenar o token do reCAPTCHA -->
      <input type="hidden" id="recaptcha-response" name="recaptcha-response">

      <div class="g-recaptcha" data-sitekey="<?php	require_once('dados.php');	echo $chave_site	?>" data-callback="handleSubmit" data-action="submit"></div>

      <div class="form-group">
        <button type="submit">Enviar</button>
      </div>
    </form>
  </div>
</body>
</html>
