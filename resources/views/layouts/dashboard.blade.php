<div class="row">
@foreach($itens as $item)
    <div class="col-lg-3 col-md-6 col-xs-12">
        <div class="panel {{$item['class']}}">
            <div class="panel-heading">
            <a href="{{$item['link']}}">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-{{$item['icon']}} fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$item['text']}}</div>
                    </div>
                </div>
            </a>
            </div>
            <div class="panel-footer">
  	          @foreach($item['lines'] as $line)
		            <a href="{!! $line['link'] !!}" class="bottom-space btn btn-lg btn-{{$line['color']}}">
                  <span class="pull-left">{!! $line['title'] !!}</span>
                  <span class="pull-right badge">{!! $line['count'] !!}</span>
	              </a>
	            @endforeach
            </div>
        </div>
    </div>
@endforeach
</div>