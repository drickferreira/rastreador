@if(isset($new_user))
<h2>Bem Vindo</h2>
<p>Caro(a) {!! $user->name !!}</p>
<p>Seu cadastro no Sistema de Rastreamento foi efetuado</p>
<p>Seu nome de Usuário: <b>{!! $user->username !!}</b></p>
<p>Clique no link abaixo para cadastrar sua senha: </p>
@else
<p>Caro(a) {!! $user->name !!}</p>
<p>Clique no link abaixo para alterar sua senha: </p>
@endif
<p>{!! url('password/reset/'.$token) !!}</p>
<p><i>Essa é uma mensagem automática. Favor não responder a esse email.</i></p>