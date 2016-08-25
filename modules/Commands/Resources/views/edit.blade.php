@extends('layouts.base')
@section('main')
<div class="editview">
{!! $form !!}
@if(isset($responses))
  <div id="response" class="table-responsive">
  <table class="table table-bordered table-striped table-condensed">
    <thead>
      <tr>
        <th>Resposta N&ordm;</th>
        <th>Tentativa</th>
        <th>Data/Hora</th>
        <th>Status</th>
        <th>Descri&ccedil;&atilde;o</th>
      </tr>
    </thead>
    <tbody>
  @forelse ($responses as $response)
      <tr>
        <td>{!! $response->fragment_number .'/'. $response->fragment_count !!}</td>
        <td>{!! $response->attempt !!}</td>
        <td>{!! $response->timestamp !!}</td>
        <td>{!! fieldValue("commands_response_status",$response->sts_id) !!}</td>
        <td>{!! $response->desc !!}</td>
      </tr>
  @empty
      <tr>
        <td colspan="5">Nenhuma resposta</td>
      </tr>
  @endforelse
    </tbody>
  </table>
  </div>
  </div>
@endif
</div>
@endsection