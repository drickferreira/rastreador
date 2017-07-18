<h2>Fechamento ADM Assistência Ref: {!! $start->format("m/Y") !!}</h2>
<p>Aparelhos ativos na base até {!! $end->format("d/m/Y") !!}: {!! $devices !!}</p>
<p>Aparelhos instalados entre {!! $start->format("d/m/Y") !!} e {!! $end->format("d/m/Y") !!}: {!! $install !!} </p>
<p>Valor por Aparelho: R$ 3,00</p>
<p>Total do Fechamento: R${!! $total !!},00</p>