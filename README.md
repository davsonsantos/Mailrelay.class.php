# Mailrelay.class.php
Classe de Integração com a Plataforma Mailrelay - Email em massa

Olá Devs, eu qui novamente. Hoje trago uma plataforma que particulamente testei e gostei para envio de newslatter, a conta free da plataforma tem um start de 3k leads e 15k envios por mês, mas esses valores podem chegar a 15k de leads e 75k de envios no mês, deve apenas conectar sua conta com suas redes sociais.

Após criar sua conta acesse o menu "PROMOÇÕES -> ATUALIZAÇÃO DE LIMITES PARA REDES SOCIAIS" e faça o login em suas redes sociais, após 24 horas sua conta e atualizada e é só aproveita.

A classe que estou disponibilizando integra sua aplicação a plataforma, com os seguintes serviços:

Funções de Gerenciamento de Grupos de Emails
Lista;
Adicionar;
Editar;
Excluir.
Funções de Gerenciamento de Leads
Lista;
Adiciona (em grupos ou não);
Edita;
Excluir.
Acesso as estatisticas de sua conta (Quantidade total de inscritos e enviados).
Vamos ao que interessa:

Toda conexão e via JSON, na classe deve ser informado apenas a URL da sua conta:

var $URL = 'http://url-da-sua-conta.com/ccm/admin/api/version/2/&type=json';
e na função getAuthentication da classe inform seu login e senha:

$postData = array(
 'function' => 'doAuthentication',
 'username' => 'username',
 'password' => 'password,
 );
Para usar é muito simples, crie o objeto da classe.

require 'Mailrelay.class.php';
$mailrelay = new Mailrelay;
Criando, Editando e excluindo grupos:

//Listando os Grupos
$mailrelay->getGroup();//retrorna um array com todos os grupos

//Criando Grupos
$Data = ['groupName' => 'Nome do Grupo',
               'groupDescription' => 'Descrição do Grupo',
               'enable' => false, //grupo habilitado
               'visible' => false //grupo visivel
];
$mailrelay->addGroup($Data);

//Editando Grupos
$Data = ['groupName' => 'Editando nome do grupo',
               'groupDescription' => 'Editando descrição do grupo',
               'groupId' => 18 //Id do grupo
 ];
$mailrelay->updateGroup($Data); 

//Excluindo Grupos
$Data = ['groupId' => 18];
$mailrelay->deleteGroup($Data);
Trabalhando com leads:

//lista todos os leads cadastrados
$mailrelay->getSubscripts();

//Adiciona leads
 $Data = ['user_email' => 'emaildolead@dominio.com,
                'user_name' => 'Nome do Lead',
                'group' => array(1,11,14) //Id dos grupo(s)
 ];
$mailrelay->addSubscript($Data);

//Editando Lead
$Data = ['id' => 1, //Id do lead
               'user_name' => 'nome do lead',
               'user_email' => 'email@dominio.com'
              'groups' => array(1,2,3) // caso queira add o lead em outros grupos
];
$mailrelay->updateSubscript($Data);

//Ativando e Desativando assinaturas
$Data = ['ids' => array(1,2,3) //Ids dos Leads
               'status' => 0 // 0 - Inativa, 1 - Ativa
 ];
$mailrelay->statusSubscribers($Data);

//Excluindo Leads
$Data = ['email' => 'email@dominio.com.br'];
$mailrelay->deleteSubscribers($Data);
Estatistica da conta, mostra a quantidade total da sua conta e quantidade de e-mails disponivel e enviados

$mailrelay->package();
E por fim a função que dispara os emails:

//Enviando Email
$Data = ['name' => 'Nome do Destinatario', 
               'email'=>'emaildestiantario@dominio.com',
               'subject'=>'Assunto do Email',
               'message'=>'<h1>Corpo do Email</h1>'];
$mailrelay->sendEmail($Data);
Lembrando que o remente e o email cadastrado na plataforma do Mailrelay.

Faça downalod da classe aqui e não esqueça de compartilhar esse post com seus amigo e deixe seu comentário.
