<?php
require_once('fpdf/fpdf.php');

// Verifique o reCAPTCHA
$recaptchaResponse = $_POST['g-recaptcha-response'];
$chave_secreta = '6Le_8gMmAAAAAEO8KU5fgE-dm_ZOmgxDOy4dfRbz'; // Substitua pela sua chave secreta correta do reCAPTCHA

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

// Obtenha os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
//$cpf = $_POST['cpf'];
$tel = $_POST['tel'];
$endereco = $_POST['endereco'];

// Crie um novo objeto FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Defina a fonte e o tamanho do texto
$pdf->SetFont('Arial', '', 12);

// Escreva o conteúdo no PDF
$pdf->Cell(0, 10, utf8_decode('Formulário'), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode('Nome: ') . $nome, 0, 1);
$pdf->Cell(0, 10, utf8_decode('Email: ') . $email, 0, 1);
//$pdf->Cell(0, 10, utf8_decode('CPF: ') . $cpf, 0, 1);
$pdf->Cell(0, 10, utf8_decode('TEL: ') . $tel, 0, 1);
$pdf->Cell(0, 10, utf8_decode('Endereço: ') . $endereco, 0, 1);

// Defina o nome do arquivo PDF com base no nome do cliente
$nomeArquivo = 'formulario_' . strtolower(str_replace(' ', '_', $nome)) . '.pdf';

// Defina o caminho completo para salvar o PDF
$pdfFilePath = "/tmp/$nomeArquivo";

// Salve o PDF no caminho especificado
$pdf->Output($pdfFilePath, 'F');

// Envie o PDF via Telegram usando o cURL
$telegramBotToken = '492230424:AAH70Sd-J1trfPiFrUbDrECVOEN-FzhORsY';
$chatId = '-506621348';
$telegramApiUrl = "https://api.telegram.org/bot$telegramBotToken/sendDocument";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $telegramApiUrl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => array(
    'chat_id' => $chatId,
    'document' => new CURLFile($pdfFilePath),
    'caption' => 'Formulário de ' . $nome
  )
));

$response = curl_exec($curl);
$error = curl_error($curl);

curl_close($curl);

if ($error) {
  echo "<script>alert('Ocorreu um erro ao enviar o PDF via Telegram: $error')</script>";
} else {
  echo "<script>alert('O PDF foi enviado com sucesso via Telegram!')</script>";
	sleep (5);
  	  // Redirecionar o usuário de volta ao formulário com uma mensagem de erro
    header("Location: index.php?r=sucesso");
    exit;
}
  } else {
	  // Redirecionar o usuário de volta ao formulário com uma mensagem de erro
    header("Location: index.php?erro=recaptcha");
    exit;
}
?>