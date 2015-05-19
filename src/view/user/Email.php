<p>Olá, <?= $data['user']->getCompleteName() ?>!</p>

<p>Este e-mail foi enviado porque recebemos uma solicitação de cadastro em nosso sistema.<br>
    Para confirmar seu cadastro, por favor, clique no link abaixo ou copie e cole-o na barra de endereço do seu navegador:</p>

<p><a href="<?= $data['confirmationLink'] ?>"><?= $data['confirmationLink'] ?></a></p>

<p>Se você não solicitou cadastro em nosso sistema, contate-nos através do e-mail <a href="mailto:natanael.simoes@ifro.edu.br">natanael.simoes@ifro.edu.br</a> e desconsidere este e-mail.</p>

<p><span style="font-size: small">Este é um e-mail automático. Não o responda.</span></p>
