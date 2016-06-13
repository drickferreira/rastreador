@if(isset($new_user))
<h2>Bem Vindo</h2>
<p>Caro(a) {!! $user->name !!}</p>
<p>Seu cadastro no Sistema de Rastreamento foi efetuado</p>
<p>Clique no link abaixo para cadastrar sua senha: </p>
@else
<p>Caro(a) {!! $user->name !!}</p>
<p>Clique no link abaixo para alterar sua senha: </p>
@endif
{!! url('password/reset/'.$token) !!}